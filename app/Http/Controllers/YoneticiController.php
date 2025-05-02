<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Etkinlik_bilgi;
use Illuminate\Support\Facades\Auth;
class YoneticiController extends Controller
{
    public function giris(Request $request)
    {
        $curl = curl_init();
        $tc = $request->input('tc');
        $sifre = $request->input('sifre');

        $jsonVerisi = [
            "Tip" => "ogrenci",
            "TcKn" => $tc,
            "Sifre" => $sifre,
        ];
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.erbakan.edu.tr/LDap/getGirisDogrula',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => json_encode($jsonVerisi),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic a2lzbWl6YW1hbmxpOlRRZ0FSajlTRUItdmg0MQ=='
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = curl_exec($curl);

        $data = json_decode($response, true);
        if (isset($data['Durum'])) {
            $ogrenci = DB::table('ogrenci_bilgi')
                ->where('tc', $tc)
                ->first();
            if ($ogrenci) {
                $uye = DB::table('uyeler')
                    ->where('ogr_id', $ogrenci->id)
                    ->whereIn('rol', [2, 3])
                    ->first();

                if (!$uye) {
                    return back()->with('error', 'Bu kullanıcıya yönetici yetkisi tanımlanmamış');
                }
                $topluluk = DB::table('topluluklar')
                    ->where('id', $uye->top_id)
                    ->first();
                $rol = DB::table('rol')
                    ->where('id', $uye->rol)
                    ->first();
                session([
                    'ogrenci_id' => $ogrenci->id,
                    'isim' => $ogrenci->isim,
                    't_id' => $uye->top_id,
                    'topluluk' => $topluluk->isim,
                    'rol' => $rol->rol
                ]);
                return redirect()->route('yonetici.panel');
            }
            else
                $jsonVerisi = [
                    "Tip" => "personel",
                    "TcKn" => $tc,
                    "Sifre" => $sifre,
                ];
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://api.erbakan.edu.tr/LDap/getGirisDogrula',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => json_encode($jsonVerisi),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic a2lzbWl6YW1hbmxpOlRRZ0FSajlTRUItdmg0MQ=='
                 ),
               ));
                curl_close($curl);
                $response = curl_exec($curl);
                $data = json_decode($response, true);
                if (isset($data['Durum'])) {
                    $personel = DB::table('personel')
                        ->where('tc', $tc)
                        ->first();
                    if($personel){
                        return redirect()->route('kesfet');
                    }
                    else
                    {
                        return redirect()->route('anasayfa');
                    }
                }
                else{
                    return redirect()->route('anasayfa');
                }
        }
        else {
            return redirect()->route('anasayfa');
        }

    }
    public function yoneticiPanel()
    {
        $topluluk_id = session('topluluk');
        $veri = DB::table('topluluklar')
            ->where('isim', $topluluk_id)
            ->first();

        return view('yonetici_panel', compact('veri'));

    }
    public function etkinlikEkle(Request $request)
    {
        if ($request->hasFile('afis')) {
            $afis = $request->file('afis');
            $tarih = date('Y-m-d', strtotime($request->input('tarih')));
            $afisAdi = $tarih . '_' . $request->input('baslik').'png';
            $afis->move(public_path('images\etkinlik'), $afisAdi);
            $afisYolu = 'images/etkinlik/' . $afisAdi;
            DB::table('etkinlik_bilgi')->insert([
                'isim' => $request->input('baslik'),
                'bilgi' => $request->input('kisa_bilgi'),
                'metin' => $request->input('aciklama'),
                't_id' => session('t_id'),
                'gorsel' => $afisAdi,
                'tarih' => $tarih,

            ]);
        }

        return back()->with('success', 'Etkinlik başarıyla eklendi!');
    }
    public function etkinlikIslemleri()
    {
        $toplulukId = session('t_id');
        
        // Onaylanmış etkinlikleri getir
        $onaylanmisEtkinlikler = DB::table('etkinlik_onay')
            ->join('etkinlik_bilgi', 'etkinlik_onay.e_id', '=', 'etkinlik_bilgi.id')
            ->where('etkinlik_onay.onay', 1)
            ->where('etkinlik_bilgi.t_id', $toplulukId)
            ->select('etkinlik_bilgi.*')
            ->get();

        return view('etkinlik_islemleri', compact('onaylanmisEtkinlikler'));
    }
    public function basvuruGuncelle(Request $request)
    {
        $etkinlik = Etkinlik_bilgi::findOrFail($request->etkinlik_id);

        // Başvuru durumunu tersine çevir: 1 → 0, 0 → 1
        $etkinlik->b_durum = $etkinlik->b_durum == 1 ? 0 : 1;
        $etkinlik->save();

        return back()->with('success', 'Başvuru durumu güncellendi.');
    }

    public function yoklamaIslemleri()
    {
        $toplulukId = session('t_id');

        // Onaylanmış etkinlikleri getir
        $onaylanmisEtkinlikler = DB::table('etkinlik_onay')
            ->join('etkinlik_bilgi', 'etkinlik_onay.e_id', '=', 'etkinlik_bilgi.id')
            ->where('etkinlik_onay.onay', 1)
            ->where('etkinlik_bilgi.t_id', $toplulukId)
            ->select('etkinlik_bilgi.*')
            ->get();

        return view('etkinlik_islemleri', compact('onaylanmisEtkinlikler'));
    }

    public function yoklamaGuncelle(Request $request)
    {
        $toplulukId = session('t_id');
        $etkinlikId = $request->etkinlik_id;

        $etkinlik = etkinlik_bilgi::where('id', $etkinlikId)
            ->where('t_id', $toplulukId)
            ->first();

        if (!$etkinlik) {
            return back()->with('error', 'Etkinlik bulunamadı.');
        }

        // Mevcut durumu tersine çevir
        $etkinlik->y_durum = $etkinlik->y_durum ? 0 : 1;
        $etkinlik->save();

        return back()->with('success', 'Yoklama durumu güncellendi.');
    }

    public function paylasilabilirEtkinlikler()
    {
        $toplulukId = session('t_id');

        // Onaylanmış etkinlikleri getir
        $onaylanmisEtkinlikler = DB::table('etkinlik_onay')
            ->join('etkinlik_bilgi', 'etkinlik_onay.e_id', '=', 'etkinlik_bilgi.id')
            ->where('etkinlik_onay.onay', 1)
            ->where('etkinlik_bilgi.t_id', $toplulukId)
            ->select('etkinlik_bilgi.*')
            ->get();

        return view('etkinlik_islemleri', compact('onaylanmisEtkinlikler'));
    }

    public function etkinlikPaylas(Request $request)
    {
        // Debug için gelen verileri kontrol edelim
        \Log::info('Paylaşım isteği:', $request->all());
        
        $etkinlik = Etkinlik_bilgi::find($request->paylasEtkinlikSec);
        
        if (!$etkinlik) {
            \Log::error('Etkinlik bulunamadı. ID:', ['id' => $request->paylasEtkinlikSec]);
            return back()->with('danger', 'Etkinlik bulunamadı.');
        }

        \Log::info('Bulunan etkinlik:', ['etkinlik' => $etkinlik->toArray()]);

        try {
            // Yeni görsel yükleme
            if ($request->hasFile('paylasResim')) {
                $afis = $request->file('paylasResim');
                $afisAdi = time() . '_' . $afis->getClientOriginalName();
                $afis->move(public_path('images/etkinlik'), $afisAdi);
                $etkinlik->gorsel = $afisAdi;
            }

            // Güncellemeler
            $etkinlik->bilgi = $request->paylasKisaBilgi;
            $etkinlik->metin = $request->paylasAciklama;
            $etkinlik->p_durum = 1;

            // Değişiklikleri kaydet
            $saved = $etkinlik->save();

            \Log::info('Kaydetme sonucu:', ['saved' => $saved, 'etkinlik' => $etkinlik->toArray()]);

            if ($saved) {
                return back()->with('success', 'Etkinlik başarıyla paylaşıldı.');
            } else {
                \Log::error('Kaydetme başarısız oldu');
                return back()->with('danger', 'Etkinlik paylaşılırken bir hata oluştu.');
            }
        } catch (\Exception $e) {
            \Log::error('Paylaşım hatası:', ['error' => $e->getMessage()]);
            return back()->with('danger', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }
    public function basvurular(Request $request)
    {
        $toplulukId = session('t_id');

        // Onaylanmış etkinlikleri getir
        $onaylanmisEtkinlikler = DB::table('etkinlik_onay')
            ->join('etkinlik_bilgi', 'etkinlik_onay.e_id', '=', 'etkinlik_bilgi.id')
            ->where('etkinlik_onay.onay', 1)
            ->where('etkinlik_bilgi.t_id', $toplulukId)
            ->select('etkinlik_bilgi.*')
            ->get();

        $basvurular = DB::table('etkinlik_basvuru')
            ->join('uyeler', 'etkinlik_basvuru.u_id', '=', 'uyeler.id')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->select('ogrenci_bilgi.isim', 'ogrenci_bilgi.numara', 'ogrenci_bilgi.bolum', 'ogrenci_bilgi.tel', 'etkinlik_basvuru.u_id')
            ->where('etkinlik_basvuru.e_id', $request->etkinlik_id)
            ->get();

        return view('etkinlik_islemleri', compact('onaylanmisEtkinlikler', 'basvurular'));
    }
    public function getir(Request $request)
    {
        \Log::info('Başvuru listeleme isteği:', $request->all());
        
        $etkinlikId = $request->etkinlik_id;
        
        try {
            $basvurular = DB::table('etkinlik_basvuru')
                ->join('uyeler', 'etkinlik_basvuru.u_id', '=', 'uyeler.id')
                ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
                ->select(
                    'ogrenci_bilgi.isim',
                    'ogrenci_bilgi.numara',
                    'ogrenci_bilgi.bolum',
                    'ogrenci_bilgi.tel',
                    DB::raw('(SELECT COUNT(*) FROM etkinlik_basvuru WHERE u_id = uyeler.id) as toplam_katilim')
                )
                ->where('etkinlik_basvuru.e_id', $etkinlikId)
                ->get();

            \Log::info('Bulunan başvurular:', ['basvurular' => $basvurular]);

            return response()->json($basvurular);
        } catch (\Exception $e) {
            \Log::error('Başvuru listeleme hatası:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Başvurular alınırken bir hata oluştu'], 500);
        }
    }
    public function cikis(Request $request)
    {
        // Oturumu sonlandır
        Auth::logout();

        // Tüm session verilerini temizle
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Anasayfaya yönlendir
        return redirect('/')->with('success', 'Başarıyla çıkış yapıldı.');
    }
}

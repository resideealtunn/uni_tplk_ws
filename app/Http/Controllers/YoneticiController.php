<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Etkinlik_bilgi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class YoneticiController extends Controller
{
    public function giris(Request $request)
    {
        $response = $request->input('g-recaptcha-response');
        $secretKey = "6LcFD6YpAAAAAA8rNdPgqJMQvPfTY7GqSnFS4voH";
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $response;
        $recaptchaResponse = Http::asForm()->post($url);
        $recaptcha = $recaptchaResponse->json();
        if ($recaptcha["success"]) {
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

        $data = json_decode($response, true);
        if (isset($data['Durum']) and $data['Durum'] == true) {
            $ogrenci = DB::table('ogrenci_bilgi')
                ->where('tc', $tc,)
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
            } else {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://api.erbakan.edu.tr/KismiZamanli/getOgrenciOzlukBilgi?tcKimlikNo=' . $tc,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Basic a2lzbWl6YW1hbmxpOlRRZ0FSajlTRUItdmg0MQ=='
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $data = json_decode($response, true);
                if ($data) {
                    DB::table('ogrenci_bilgi')->insert([
                        'isim' => $data[0]['AD'],
                        'soyisim' => $data[0]['SOYAD'],
                        'tc' => $request->tc,
                        'numara' => $data[0]['OGR_NO'],
                        'fak_ad' => $data[0]['FAK_AD'],
                        'bol_ad' => $data[0]['BOL_AD'],
                        'prog_ad' => $data[0]['PROG_AD'],
                        'sınıf' => $data[0]['SINIF'],
                        'kay_tar' => date("Y-m-d", strtotime(str_replace('.', '-', $data[0]['KAY_TAR']))),
                        'ogrenim_durum' => $data[0]['OGRENIM_DURUM'],
                        'ogrenim_tip' => $data[0]['OGRENIM_TIP'],
                        'ayr_tar' => $data[0]['AYR_TAR'],
                        'tel' => $data[0]['TELEFON'],
                        'tel2' => $data[0]['TELEFON2'],
                        'eposta' => $data[0]['EPOSTA1'],
                        'eposta2' => $data[0]['EPOSTA2'],
                        'program_tip' => $data[0]['PROGRAM_TIP'],
                        'durum' => $data[0]['DURUM'],
                    ]);
                }
                return redirect()->route('yonetici.giris');
            }
        } else {
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
            $response = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($response, true);
            if (isset($data['Durum']) and $data['Durum'] == true) {
                $personel = DB::table('personel')
                    ->where('tc', $tc)
                    ->first();
                if ($personel) {
                    session([
                        'personel_id' => $personel->id,
                        'isim' => $personel->isim,
                        'unvan' => $personel->unvan,
                        'birim' => $personel->birim
                    ]);
                    return redirect()->route('denetim.panel');
                } else {
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://api.erbakan.edu.tr/KismiZamanli/getPersonelBilgi?tc=' . $request->tc,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Authorization: Basic a2lzbWl6YW1hbmxpOlRRZ0FSajlTRUItdmg0MQ=='
                        ),
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $data = json_decode($response, true);
                    if ($data) {
                        DB::table('personel')->insert([
                            'isim' => $data['personelAd'] . ' ' . $data['personelSoyad'],
                            'tc' => $request->tc,
                            'unvan' => $data['personelUnvan'],
                            'birim' => $data['personelBirim']
                        ]);
                        $personel = DB::table('personel')
                            ->where('tc', $tc)
                            ->first();
                        session([
                            'personel_id' => $personel->id,
                            'isim' => $personel->isim,
                            'unvan' => $personel->unvan,
                            'birim' => $personel->birim
                        ]);
                        return redirect()->route('denetim.panel');
                    }
                }
            } else {
                return redirect()->route('yonetici.giris');
            }
        }
    }
        else{
            return redirect()->back()->with('danger', 'Üniversiteye Kaydınız Bulunamadı!');
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
        $basvurular = DB::table('etkinlik_basvuru')
            ->join('uyeler', 'etkinlik_basvuru.u_id', '=', 'uyeler.id') // Assuming 'id' is the primary key in 'uyeler'
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id') // Assuming 'id' is the primary key in 'ogrencii_bilgi'
            ->select('ogrenci_bilgi.isim', 'ogrenci_bilgi.numara', 'ogrenci_bilgi.bolum', 'ogrenci_bilgi.tel', 'etkinlik_basvuru.u_id')
            ->where('etkinlik_basvuru.e_id',1 ) // Ensure to replace 'event_id' with the actual event column
            ->get();

        return response()->json($basvurular);
    }

    public function guncelle(Request $request)
    {
        $topluluk_id = session('topluluk_id');

        // Topluluk bilgilerini al
        $topluluk = DB::table('topluluklar')->where('id', $topluluk_id)->first();

        // === LOGO GÜNCELLEME ===
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $logo_file = $request->file('logo');
            $logo_name = session('$topluluk');
            $logo_path = 'images/logo/' . $logo_name;

            $logo_file->move(public_path('images/logo'), $logo_name);

            // Eğer logo değiştiyse, güncelle
            if ($topluluk->logo !== $logo_name) {
                DB::table('topluluklar')->where('id', $topluluk_id)->update(['logo' => $logo_name]);

                // logo_onay tablosuna kaydet
                DB::table('logo_onay')->insert([
                    't_id' => $topluluk_id,
                    'logo' => $logo_name,
                    'onay' => 0
                ]);
            }
        }
        // === ARKA PLAN GÜNCELLEME ===
        if ($request->hasFile('bg') && $request->file('bg')->isValid()) {
            $bg_file = $request->file('bg');
            $bg_name = session('topluluk');
            $bg_path = 'images/bg/' . $bg_name;

            $bg_file->move(public_path('images/background'), $bg_name);
            DB::table('topluluklar')->where('id', $topluluk_id)->update(['background' => $bg_name]);
        }

        // === SLOGAN GÜNCELLEME ===
        if ($request->filled('slogan')) {
            DB::table('topluluklar')->where('id', $topluluk_id)->update(['slogan' => $request->input('slogan')]);
        }

        // === VİZYON ===
        if ($request->filled('vizyon')) {
            DB::table('topluluklar')->where('id', $topluluk_id)->update(['vizyon' => $request->input('vizyon')]);
        }

        // === MİSYON ===
        if ($request->filled('misyon')) {
            DB::table('topluluklar')->where('id', $topluluk_id)->update(['misyon' => $request->input('misyon')]);
        }

        // === TÜZÜK ===
        if ($request->hasFile('tuzuk') && $request->file('tuzuk')->isValid()) {
            $tuzuk_file = $request->file('tuzuk');
            $tuzuk_name = time() . '_tuzuk_' . Str::random(5) . '.' . $tuzuk_file->getClientOriginalExtension();
            $tuzuk_file->move(public_path('files/tuzuk'), $tuzuk_name);
            DB::table('topluluklar')->where('id', $topluluk_id)->update(['tuzuk' => $tuzuk_name]);
        }

        return redirect()->back()->with('success', 'Bilgiler güncellendi.');
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

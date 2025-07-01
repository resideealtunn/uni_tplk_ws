<?php

// app/Http/Controllers/ToplulukController.php

namespace App\Http\Controllers;

use App\Models\OgrenciBilgi;
use App\Models\Topluluk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Uye;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Exports\UyelerExport;
use Maatwebsite\Excel\Facades\Excel;

class ToplulukController extends Controller
{
    public function show($topluluk_isim, $topluluk_id)
    {
        $topluluk = Topluluk::find($topluluk_id);
        if (!$topluluk) {
            return abort(404, 'Topluluk bulunamadı');
        }
        // Onay durumlarını çek
        $logo_onay = DB::table('logo_onay')->where('t_id', $topluluk_id)->value('onay');
        $bg_onay = DB::table('bg_onay')->where('t_id', $topluluk_id)->value('onay');
        $slogan_onay = DB::table('slogan_onay')->where('t_id', $topluluk_id)->value('onay');
        $vizyon_onay = DB::table('vizyon_onay')->where('t_id', $topluluk_id)->value('onay');
        $misyon_onay = DB::table('misyon_onay')->where('t_id', $topluluk_id)->value('onay');
        $tuzuk_onay = DB::table('tuzuk_onay')->where('t_id', $topluluk_id)->value('onay');
        $sosyal_medya = $this->getSosyalMedyaLinks($topluluk_id);
        
        // Üye sayısını hesapla (rol 1, 2, 3, 6 olan üyeler)
        $uye_sayisi = DB::table('uyeler')
            ->where('top_id', $topluluk_id)
            ->whereIn('rol', [1, 2, 3, 6])
            ->count();
        
        // Etkinlik sayısını hesapla (talep_onay = 3 olan etkinlikler)
        $etkinlik_sayisi = DB::table('etkinlik_bilgi')
            ->where('t_id', $topluluk_id)
            ->where('talep_onay', 3)
            ->count();
        
        return view('tplk_anasayfa', [
            'topluluk' => $topluluk,
            'logo_onay' => $logo_onay,
            'bg_onay' => $bg_onay,
            'slogan_onay' => $slogan_onay,
            'vizyon_onay' => $vizyon_onay,
            'misyon_onay' => $misyon_onay,
            'tuzuk_onay' => $tuzuk_onay,
            'sosyal_medya' => $sosyal_medya,
            'uye_sayisi' => $uye_sayisi,
            'etkinlik_sayisi' => $etkinlik_sayisi,
        ]);
    }
    public function uyeIslemleri($isim, $id)
    {
        $topluluk = Topluluk::find($id);

        if (!$topluluk) {
            abort(404, 'Topluluk bulunamadı.');
        }
        $sosyal_medya = $this->getSosyalMedyaLinks($id);
        return view('tplk_uyeislemleri', compact('topluluk', 'sosyal_medya'));
    }
    public function kayitol(Request $request)
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
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($curlError) {
            \Log::error('LDAP API Error: ' . $curlError);
            return redirect()->back()->with('danger', 'Bağlantı hatası: ' . $curlError);
        }

        if ($httpCode !== 200) {
            \Log::error('LDAP API HTTP Error: ' . $httpCode . ' Response: ' . $response);
            return redirect()->back()->with('danger', 'Üniversite kimlik doğrulama servisi şu anda kullanılamıyor. Lütfen daha sonra tekrar deneyin.');
        }

        $data = json_decode($response, true);
        if (is_array($data) && isset($data['response'])) {
            $data = json_decode($data['response'], true);
        }

        if (!is_array($data) || !isset($data['Durum'])) {
            \Log::error('LDAP API Invalid Response: ' . $response);
            return redirect()->back()->with('danger', 'Kimlik doğrulama yanıtı geçersiz. Lütfen daha sonra tekrar deneyin.');
        }

        if (!$data['Durum']) {
            return redirect()->back()->with('danger', 'TC Kimlik No veya şifre hatalı. Lütfen bilgilerinizi kontrol edip tekrar deneyin.');
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.erbakan.edu.tr/KismiZamanli/getOgrenciOzlukBilgi?tcKimlikNo=' . $tc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic a2lzbWl6YW1hbmxpOlRRZ0FSajlTRUItdmg0MQ=='
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 30
        ));
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($curlError) {
            \Log::error('Student Info API Error: ' . $curlError);
            return redirect()->back()->with('danger', 'Bağlantı hatası: ' . $curlError);
        }

        if ($httpCode !== 200) {
            \Log::error('Student Info API HTTP Error: ' . $httpCode . ' Response: ' . $response);
            return redirect()->back()->with('danger', 'Öğrenci bilgileri alınamıyor. Lütfen daha sonra tekrar deneyin.');
        }

        $data = json_decode($response, true);
        if (is_array($data) && isset($data['response'])) {
            $data = json_decode($data['response'], true);
        }

        if (!is_array($data) || !isset($data[0])) {
            return redirect()->back()->with('danger', 'Öğrenci bilgileri bulunamadı. Lütfen TC Kimlik No\'nuzu kontrol edin.');
        }

        $yeniTarih = \Carbon\Carbon::createFromFormat('d.m.Y', $data[0]["KAY_TAR"])->format('Y-m-d');
        $tc = $data[0]["TCK"];
        $ogrenci = DB::table('ogrenci_bilgi')->where('tc', $tc)->first();
        if(!$ogrenci) {
            $id = DB::table('ogrenci_bilgi')->insertGetId([
                'numara' => $data[0]["OGR_NO"],
                'tc' => $data[0]["TCK"],
                'isim' => $data[0]["AD"],
                'soyisim' => $data[0]["SOYAD"],
                'fak_ad' => $data[0]["FAK_AD"],
                'bol_ad' => $data[0]["BOL_AD"],
                'prog_ad' => $data[0]["PROG_AD"],
                'sınıf' => $data[0]["SINIF"],
                'kay_tar' => !empty($data[0]["KAY_TAR"]) ? \Carbon\Carbon::createFromFormat('d.m.Y', $data[0]["KAY_TAR"])->format('Y-m-d') : null,
                'ogrenim_durum' => $data[0]["OGRENIM_DURUM"],
                'ogrenim_tip' => $data[0]["OGRENIM_TIP"],
                'ayr_tar' => !empty($data[0]["AYR_TAR"]) ? \Carbon\Carbon::createFromFormat('d.m.Y', $data[0]["AYR_TAR"])->format('Y-m-d') : null,
                'tel' => $data[0]["TELEFON"],
                'tel2' => $data[0]["TELEFON2"],
                'eposta' => $data[0]["EPOSTA1"],
                'eposta2' => $data[0]["EPOSTA2"],
                'program_tip' => $data[0]["PROGRAM_TIP"],
                'durum' => $data[0]["DURUM"]
            ]);
        }
        else{
            $id=$ogrenci->id;
        }
        $top=$request->topluluk;
        $uyeVarMi = DB::table('uyeler')
            ->where('ogr_id', $id)
            ->where('top_id', $top)
            ->first();
        if ($uyeVarMi) {
            return redirect()->back()->with('danger', 'Zaten Bu Topluluğa Üyesiniz!');
        }
        else {
            $membershipForm = $request->file('membership_form');
            $tarih = now()->format('Y-m-d_H-i-s');
            $dosyaAdi = $tc . '_' . $tarih . '.' . $membershipForm->getClientOriginalExtension();
            DB::table('uyeler')->insert([
                'ogr_id' => $id,
                'top_id' => $top,
                'tarih' => $tarih,
                'belge' => $dosyaAdi,
                'rol' => 4,
                'durum' => 1
            ]);
            $membershipForm->move(public_path('docs/kayit_belge'), $dosyaAdi);
            return redirect()->back()->with('success', 'Kayıt Başarıyla Tamamlandı!');
        }
    }
    public function index()
    {
        $topluluklar = Topluluk::where('durum', 1)->get();
        return view('denetim_uye', compact('topluluklar'));
    }
    public function indextopluluk()
    {
        $perPage = 9;
        $currentPage = request()->query('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $topluluklar = DB::table('topluluklar')
            ->where('durum','=','1')
            ->skip($offset)
            ->take($perPage)
            ->get();
        $totalForms = DB::table('topluluklar')
            ->where('durum','=','1')
            ->count();
        $lastPage = ceil($totalForms / $perPage);
        $pperPage = 9;
        $pcurrentPage = request()->query('sayfa', 1);
        $poffset = ($pcurrentPage - 1) * $pperPage;
        $ptopluluklar = DB::table('topluluklar')
            ->where('durum','=','2')
            ->skip($poffset)
            ->take($pperPage)
            ->get();
        $ptotalForms = DB::table('topluluklar')
            ->where('durum','=','2')
            ->count();
        $plastPage = ceil($ptotalForms / $perPage);
        $pcurrentPage = request()->query('sayfa', 1);
        return view('denetim_topluluk', compact('topluluklar', 'currentPage', 'lastPage', 'perPage', 'totalForms','ptopluluklar', 'ptotalForms', 'plastPage', 'pcurrentPage'));
    }
    public function uyeListesi($id)
    {
        $topluluklar = Topluluk::all();
        $uyeler = DB::table('uyeler')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('uyeler.top_id', $id)
            ->whereIn('uyeler.rol', [1,2,3,6])
            ->select(
                'uyeler.id as id',
                'uyeler.tarih as tarih',
                'ogrenci_bilgi.numara as numara',
                'ogrenci_bilgi.isim as isim',
                'ogrenci_bilgi.soyisim as soyisim',
                'ogrenci_bilgi.fak_ad as fak_ad',
                'ogrenci_bilgi.bol_ad as bol_ad',
                'ogrenci_bilgi.tel as tel',
                'uyeler.belge as belge',
                'uyeler.rol as rol'
            )
            ->get();
        return response()->json($uyeler);
    }
    public function basvuruListesi($id)
    {
        $topluluklar = Topluluk::all();
        $uyeler = DB::table('uyeler')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('uyeler.top_id', $id)
            ->where('uyeler.rol', 4)
            ->select(
                'uyeler.id as id',
                'ogrenci_bilgi.id as ogr_id',
                'uyeler.tarih as tarih',
                'ogrenci_bilgi.numara as numara',
                'ogrenci_bilgi.isim as isim',
                'ogrenci_bilgi.soyisim as soyisim',
                'ogrenci_bilgi.fak_ad as fak_ad',
                'ogrenci_bilgi.bol_ad as bol_ad',
                'ogrenci_bilgi.tel as tel',
                'uyeler.belge as belge'
            )
            ->get();
        return response()->json($uyeler);
    }

    public function updateApplicationStatus(Request $request)
    {
        $id = $request->input('id');
        $durum = $request->input('durum');
        $t_id = $request->input('t_id');
        $affected = DB::table('uyeler')
            ->where('ogr_id', $id)
            ->where('top_id', $t_id)
            ->update(['durum' => $durum]);
        if ($affected) {
            return response()->json(['success' => true, 'message' => 'Başvuru durumu güncellendi.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Güncelleme başarısız oldu.']);
        }
    }
    public function updateRol(Request $request)
    {
        $id = $request->input('id');
        $durum = $request->input('rol');
        $affected = DB::table('uyeler')
            ->where('id', $id)
            ->update(['rol' => $durum]);
        if ($affected) {
            return response()->json(['success' => true, 'message' => 'Rol durumu güncellendi.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Güncelleme başarısız oldu.']);
        }
    }
    public function yeniUyeEkle(Request $request)
    {
        $tc = $request->post('tcno');
        $toplulukId = $request->input('topluluk_id');
        $tarih = $request->input('basvuru_tarihi');
        $belge = $request->file('belge');

        // Önce ogrenci_bilgi tablosunda var mı kontrol et
        $ogrenci = DB::table('ogrenci_bilgi')->where('tc', $tc)->first();
        if (!$ogrenci) {
            // API'den öğrenci bilgisi çek
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
            if (is_array($data) && isset($data['response'])) {
                $data = json_decode($data['response'], true);
            }
            if (!is_array($data) || !isset($data[0]) || ($data[0]['DURUM'] ?? '') !== 'Aktif') {
                return response()->json(['success' => false, 'message' => 'API boş döndü, öğrenci bulunamadı veya öğrenci aktif değil.']);
            }
            // ogrenci_bilgi'ye ekle
            $ogrenciId = DB::table('ogrenci_bilgi')->insertGetId([
                'numara' => $data[0]["OGR_NO"],
                'tc' => $data[0]["TCK"],
                'isim' => $data[0]["AD"],
                'soyisim' => $data[0]["SOYAD"],
                'fak_ad' => $data[0]["FAK_AD"],
                'bol_ad' => $data[0]["BOL_AD"],
                'prog_ad' => $data[0]["PROG_AD"],
                'sınıf' => $data[0]["SINIF"],
                'kay_tar' => !empty($data[0]["KAY_TAR"]) ? \Carbon\Carbon::createFromFormat('d.m.Y', $data[0]["KAY_TAR"])->format('Y-m-d') : null,
                'ogrenim_durum' => $data[0]["OGRENIM_DURUM"],
                'ogrenim_tip' => $data[0]["OGRENIM_TIP"],
                'ayr_tar' => !empty($data[0]["AYR_TAR"]) ? \Carbon\Carbon::createFromFormat('d.m.Y', $data[0]["AYR_TAR"])->format('Y-m-d') : null,
                'tel' => $data[0]["TELEFON"],
                'tel2' => $data[0]["TELEFON2"],
                'eposta' => $data[0]["EPOSTA1"],
                'eposta2' => $data[0]["EPOSTA2"],
                'program_tip' => $data[0]["PROGRAM_TIP"],
                'durum' => $data[0]["DURUM"]
            ]);
            $ogrenci = DB::table('ogrenci_bilgi')->where('id', $ogrenciId)->first();
        }
        // Bu kişinin bu toplulukta herhangi bir üyeliği veya başvurusu var mı?
        $uyeVarMi = DB::table('uyeler')
            ->where('ogr_id', $ogrenci->id)
            ->where('top_id', $toplulukId)
            ->first();
        if ($uyeVarMi) {
            return response()->json(['success' => false, 'message' => 'Bu öğrenci zaten bu topluluğun üyesi veya başvurusu var.']);
        }
        // --- uyeler tablosuna mutlaka ekle ---
        $belgeAdi = null;
        if ($belge && $belge->isValid()) {
            $belgeAdi = time() . '_' . $belge->getClientOriginalName();
            $belge->move(public_path('docs/kayit_belge'), $belgeAdi);
        }
        DB::table('uyeler')->insert([
            'ogr_id' => $ogrenci->id,
            'top_id' => $toplulukId,
            'rol' => 1,
            'tarih' => $tarih,
            'belge' => $belgeAdi,
            'durum' => 1
        ]);
        return response()->json(['success' => true]);
    }
    public function getSilinecekUyeler($toplulukId)
    {
        $uyeler = DB::table('uyeler')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('uyeler.top_id', $toplulukId)
            ->whereIn('uyeler.rol', [1, 2, 3])
            ->where('uyeler.durum', 1)
            ->select(
                'uyeler.id as uye_id',
                'uyeler.tarih as tarih',
                'ogrenci_bilgi.numara as numara',
                'ogrenci_bilgi.isim as isim',
                'ogrenci_bilgi.soyisim as soyisim',
                'ogrenci_bilgi.tel as tel',
                'ogrenci_bilgi.fak_ad as fakulte',
                'ogrenci_bilgi.bol_ad as bolum',
                'uyeler.belge as belge'
            )
            ->get();
        return response()->json($uyeler);
    }
    public function deleteUye(Request $request)
    {
        $uyeId = $request->input('id');
        $sebep = $request->input('silinme_sebebi');
        $uye = DB::table('uyeler')->where('id', $uyeId)->first();
        if (!$uye) {
            return response()->json([
                'success' => false,
                'message' => 'Üye bulunamadı.'
            ], 404);
        }
        DB::table('uyeler')->where('id', $uyeId)->update([
            'rol' => 4,
            'silinme_sebebi' => $sebep
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Üyenin durumu başarıyla güncellendi.'
        ], 200);
    }
    public function Iletisim(Request $request)
    {
        $tc = $request->input('tckno');
        $mesaj = $request->input('message');
        // Öğrenci var mı kontrol et
        $ogrenci = DB::table('ogrenci_bilgi')->where('tc', $tc)->first();
        if (!$ogrenci) {
            // API'den öğrenci bilgisi çek
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
            if (is_array($data) && isset($data['response'])) {
                $data = json_decode($data['response'], true);
            }
            if (!is_array($data) || !isset($data[0]) || ($data[0]['DURUM'] ?? '') !== 'Aktif') {
                return back()->with('danger', 'API boş döndü, öğrenci bulunamadı veya öğrenci aktif değil.');
            }
            // ogrenci_bilgi'ye ekle
            $ogrenciId = DB::table('ogrenci_bilgi')->insertGetId([
                'numara' => $data[0]["OGR_NO"],
                'tc' => $data[0]["TCK"],
                'isim' => $data[0]["AD"],
                'soyisim' => $data[0]["SOYAD"],
                'fak_ad' => $data[0]["FAK_AD"],
                'bol_ad' => $data[0]["BOL_AD"],
                'prog_ad' => $data[0]["PROG_AD"],
                'sınıf' => $data[0]["SINIF"],
                'kay_tar' => !empty($data[0]["KAY_TAR"]) ? \Carbon\Carbon::createFromFormat('d.m.Y', $data[0]["KAY_TAR"])->format('Y-m-d') : null,
                'ogrenim_durum' => $data[0]["OGRENIM_DURUM"],
                'ogrenim_tip' => $data[0]["OGRENIM_TIP"],
                'ayr_tar' => !empty($data[0]["AYR_TAR"]) ? \Carbon\Carbon::createFromFormat('d.m.Y', $data[0]["AYR_TAR"])->format('Y-m-d') : null,
                'tel' => $data[0]["TELEFON"],
                'tel2' => $data[0]["TELEFON2"],
                'eposta' => $data[0]["EPOSTA1"],
                'eposta2' => $data[0]["EPOSTA2"],
                'program_tip' => $data[0]["PROGRAM_TIP"],
                'durum' => $data[0]["DURUM"]
            ]);
        } else {
            $ogrenciId = $ogrenci->id;
        }
        // Mesajı kaydet
        DB::table('mesaj')->insert([
            'ogr_id' => $ogrenciId,
            'mesaj' => $mesaj
        ]);
        return back()->with('success', 'Geri bildiriminiz başarıyla kaydedildi.');
    }
    public function form()
    {
        $perPage = 10;
        $currentPage = request()->query('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $forms = DB::table('formlar')
            ->select('isim', 'dosya')
            ->where('durum','=','1')
            ->skip($offset)
            ->take($perPage)
            ->get();

        $totalForms = DB::table('formlar')->count();
        $lastPage = ceil($totalForms / $perPage);

        return view('formlar', compact('forms', 'currentPage', 'lastPage'));
    }
    public function searchTopluluk(Request $request)
    {
        $query = $request->input('q');
        $topluluklar = DB::table('topluluklar')
            ->where('durum', 1)
            ->where('isim', 'LIKE', "%{$query}%")
            ->select('id', 'isim', 'gorsel')
            ->get();
        return response()->json($topluluklar);
    }
    public function searchUye(Request $request)
    {
        $query = $request->input('q');
        $toplulukId = $request->input('topluluk_id');
        $ogrenciler = DB::table('ogrenci_bilgi')
            ->join('uyeler', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('numara', 'LIKE', "%{$query}%")
            ->where('uyeler.durum', '=', '1')
            ->where('uyeler.top_id', '=',  "{$toplulukId}")
            ->select('uyeler.tarih as tarih', 'numara', 'isim', 'soyisim', 'tel', 'fak_ad as fakülte', 'bol_ad as bolum', 'uyeler.belge as belge','uyeler.rol as rol')
            ->get();
        return response()->json($ogrenciler);
    }
    public function searchApply(Request $request)
    {
        $query = $request->input('q');
        $toplulukId = $request->input('topluluk_id');
        $ogrenciler = DB::table('ogrenci_bilgi')
            ->join('uyeler', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('numara', 'LIKE', "%{$query}%")
            ->where('uyeler.durum', '=', '0')
            ->where('uyeler.top_id', '=',  "{$toplulukId}")
            ->select('ogrenci_bilgi.id','uyeler.tarih as tarih', 'numara', 'isim', 'soyisim', 'tel', 'fak_ad as fakülte', 'bol_ad as bolum', 'uyeler.belge as belge','uyeler.rol as rol')
            ->get();
        return response()->json($ogrenciler);
    }
    public function toplulukEkle(Request $request) {
        if (!$request->hasFile('kurulus_belge') || !$request->file('kurulus_belge')->isValid()) {
            return back()->with('danger', 'Kuruluş başvuru belgesi yüklenmedi veya geçersiz.');
        }
        $belgeAdi = time() . '.' . $request->kurulus_belge->extension();
        $request->kurulus_belge->move(public_path('belgeler/kurulus'), $belgeAdi);

        $tc = $request->input('baskan_no');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api.erbakan.edu.tr/KismiZamanli/getOgrenciOzlukBilgi?tcKimlikNo='.$tc,
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

        if (is_array($data) && isset($data['response'])) {
            $data = json_decode($data['response'], true);
        }
        if (!is_array($data) || !isset($data[0])) {
            return response()->json(['success' => false, 'message' => 'API boş döndü veya öğrenci bulunamadı.']);
        }
        $data = json_decode($response, true);
        $yeniTarih = \Carbon\Carbon::createFromFormat('d.m.Y', $data[0]["KAY_TAR"])->format('Y-m-d');
        $ogrenci = DB::table('ogrenci_bilgi')->where('tc', $tc)->first();
        if(!$ogrenci) {
            $id = DB::table('ogrenci_bilgi')->insertGetId([
                'numara' => $data[0]["OGR_NO"],
                'tc' => $data[0]["TCK"],
                'isim' => $data[0]["AD"],
                'soyisim' => $data[0]["SOYAD"],
                'fak_ad' => $data[0]["FAK_AD"],
                'bol_ad' => $data[0]["BOL_AD"],
                'prog_ad' => $data[0]["PROG_AD"],
                'sınıf' => $data[0]["SINIF"],
                'kay_tar' => $yeniTarih,
                'ogrenim_durum' => $data[0]["OGRENIM_DURUM"],
                'ogrenim_tip' => $data[0]["OGRENIM_TIP"],
                'ayr_tar' => !empty($data[0]["AYR_TAR"]) ? \Carbon\Carbon::createFromFormat('d.m.Y', $data[0]["AYR_TAR"])->format('Y-m-d') : null,
                'tel' => $data[0]["TELEFON"],
                'tel2' => $data[0]["TELEFON2"],
                'eposta' => $data[0]["EPOSTA1"],
                'eposta2' => $data[0]["EPOSTA2"],
                'program_tip' => $data[0]["PROGRAM_TIP"],
                'durum' => $data[0]["DURUM"]
            ]);
        }
        else{
            $id=$ogrenci->id;
        }
        if($id) {
            // Get default values from default_web
            $defaultWeb = DB::table('default_web')->first();
            // Check if a community with the same name already exists
            $varMi = DB::table('topluluklar')->where('isim', $request->isim)->first();
            if ($varMi) {
                return back()->with('danger', 'Bu isimde bir topluluk zaten mevcut!');
            }
            $toplulukId = DB::table('topluluklar')->insertGetId([
                'isim' => $request->isim,
                'gorsel' => $defaultWeb ? $defaultWeb->logo : null,
                'slogan' => $defaultWeb ? $defaultWeb->slogan : null,
                'vizyon' => $defaultWeb ? $defaultWeb->vizyon : null,
                'misyon' => $defaultWeb ? $defaultWeb->misyon : null,
                'tuzuk' => $defaultWeb ? $defaultWeb->tuzuk : null,
                'bg' => $defaultWeb ? $defaultWeb->bg : null,
                'kurulus' => Carbon::now(),
                'durum' => 1
            ]);
            if($toplulukId){
                // Insert onay records for all relevant tables with onay=4 (default)
                DB::table('bg_onay')->insert(['t_id' => $toplulukId, 'onay' => 4]);
                DB::table('slogan_onay')->insert(['t_id' => $toplulukId, 'onay' => 4]);
                DB::table('vizyon_onay')->insert(['t_id' => $toplulukId, 'onay' => 4]);
                DB::table('tuzuk_onay')->insert(['t_id' => $toplulukId, 'onay' => 4]);
                DB::table('misyon_onay')->insert(['t_id' => $toplulukId, 'onay' => 4]);
                DB::table('logo_onay')->insert(['t_id' => $toplulukId, 'onay' => 4]);
                $uye=DB::table('uyeler')->insert([
                    'ogr_id' => $id,
                    'top_id' => $toplulukId,
                    'tarih' => Carbon::now(),
                    'rol' => 2
                ]);
                if($uye){
                    return back()->with('success', 'Topluluk Eklendi, Başkan Atandı');
                }
                else{
                    return back()->with('success', 'Topluluk Eklendi.');

                }
            }
            else{
                return back()->with('success', 'Topluluk eklenemedi.');
            }
        }
        else{
            return back()->with('success', 'Öğrenci Bulunamadı.');
        }
    }
    public function formlistele()
    {
        $perPage = 10;
        $currentPage = request()->query('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $forms = DB::table('formlar')
            ->select('id','isim', 'dosya')
            ->where('durum','=','1')
            ->skip($offset)
            ->take($perPage)
            ->get();

        $totalForms = DB::table('formlar')->count();
        $lastPage = ceil($totalForms / $perPage);

        return view('denetim_formlar', compact('forms', 'currentPage', 'lastPage'));
    }
    public function formSil($id)
    {
        // Formu veritabanından bul
        $form = DB::table('formlar')->where('id', $id)->first();

        if (!$form) {
            return back()->with('danger', 'Form Bulunamadı.');
        }

        DB::table('formlar')->where('id', $id)->update(['durum' => 0]);

        return back()->with('success', 'Form Başarıyla Silindi');
    }
    public function topluluksil(Request $request)
    {
        $id = $request->input('id');
        $sebep = $request->input('sebep');
        $topluluk = Topluluk::find($id);
        if ($topluluk) {
            $topluluk->durum = 2;
            $topluluk->silinme_sebebi = $sebep;
            $topluluk->save();
            return response()->json([
                'success' => true,
                'message' => 'Topluluk başarıyla silindi.'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Topluluk silinemedi.'
        ]);
    }
    public function toplulukSilinmeSebebi($id)
    {
        $topluluk = Topluluk::find($id);
        return response()->json(['sebep' => $topluluk ? $topluluk->silinme_sebebi : null]);
    }
    public function toplulukAktifeAl(Request $request)
    {
        $id = $request->input('id');
        $aciklama = $request->input('aciklama');
        $topluluk = Topluluk::find($id);
        if ($topluluk) {
            $topluluk->durum = 1;
            if ($aciklama) $topluluk->silinme_sebebi = $aciklama;
            $topluluk->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Topluluk bulunamadı.']);
    }
    public function denetimPanel()
    {
        $topluluklar = DB::table('topluluklar')
            ->leftJoin('logo_onay', 'topluluklar.id', '=', 'logo_onay.t_id')
            ->leftJoin('bg_onay', 'topluluklar.id', '=', 'bg_onay.t_id')
            ->leftJoin('slogan_onay', 'topluluklar.id', '=', 'slogan_onay.t_id')
            ->leftJoin('vizyon_onay', 'topluluklar.id', '=', 'vizyon_onay.t_id')
            ->leftJoin('misyon_onay', 'topluluklar.id', '=', 'misyon_onay.t_id')
            ->leftJoin('tuzuk_onay', 'topluluklar.id', '=', 'tuzuk_onay.t_id')
            ->where('topluluklar.durum', 1)
            ->where(function($query) {
                $query->where('logo_onay.onay', 2)
                    ->orWhere('bg_onay.onay', 2)
                    ->orWhere('slogan_onay.onay', 2)
                    ->orWhere('vizyon_onay.onay', 2)
                    ->orWhere('misyon_onay.onay', 2)
                    ->orWhere('tuzuk_onay.onay', 2);
            })
            ->distinct()
            ->select('topluluklar.*')
            ->get();
        return view('denetim_panel', compact('topluluklar'));
    }
    public function panelOnayla(Request $request) {
        $id = $request->input('topluluk_id');
        $tables = ['logo_onay', 'bg_onay', 'misyon_onay', 'vizyon_onay', 'slogan_onay', 'tuzuk_onay'];
        foreach($tables as $table) {
            DB::table($table)->updateOrInsert(
                ['t_id' => $id],
                ['onay' => 1, 'red' => null]
            );
        }
        return response()->json(['success'=>true]);
    }
    public function panelReddet(Request $request) {
        $id = $request->input('topluluk_id');
        $fields = $request->input('fields');
        $aciklama = $request->input('aciklama');
        $tableMap = [
            'logo' => 'logo_onay',
            'bg' => 'bg_onay',
            'slogan' => 'slogan_onay',
            'vizyon' => 'vizyon_onay',
            'misyon' => 'misyon_onay',
            'tuzuk' => 'tuzuk_onay',
        ];
        $allFields = array_keys($tableMap);
        // Önce işaretlenenleri red (onay=0), diğerlerini onayla (onay=1)
        foreach($allFields as $field) {
            $table = $tableMap[$field];
            if (in_array($field, $fields)) {
                // Red edilenler
                DB::table($table)->updateOrInsert(
                    ['t_id' => $id],
                    ['onay' => 0, 'red' => $aciklama]
                );
            } else {
                // Onaylananlar
                DB::table($table)->updateOrInsert(
                    ['t_id' => $id],
                    ['onay' => 1, 'red' => null]
                );
            }
        }
        return response()->json(['success'=>true]);
    }
    public function basvuruOnayla(Request $request)
    {
        try {
            $uye_id = $request->input('uye_id');
            if (!$uye_id) {
                return response()->json(['success' => false, 'message' => 'Üye ID gerekli']);
            }

            $uye = DB::table('uyeler')->where('id', $uye_id)->first();
            if (!$uye) {
                return response()->json(['success' => false, 'message' => 'Üye bulunamadı']);
            }

            $updated = DB::table('uyeler')
                ->where('id', $uye_id)
                ->update([
                    'rol' => 1,
                    'red_sebebi' => null
                ]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Başvuru başarıyla onaylandı']);
            } else {
                return response()->json(['success' => false, 'message' => 'Güncelleme yapılamadı']);
            }
        } catch (\Exception $e) {
            \Log::error('Başvuru onaylama hatası: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
    }
    public function basvuruReddet(Request $request)
    {
        try {
            $uye_id = $request->input('uye_id');
            $sebep = $request->input('red_sebebi');

            if (!$uye_id) {
                return response()->json(['success' => false, 'message' => 'Üye ID gerekli']);
            }
            if (!$sebep) {
                return response()->json(['success' => false, 'message' => 'Red sebebi gerekli']);
            }

            $uye = DB::table('uyeler')->where('id', $uye_id)->first();
            if (!$uye) {
                return response()->json(['success' => false, 'message' => 'Üye bulunamadı']);
            }

            $updated = DB::table('uyeler')
                ->where('id', $uye_id)
                ->update([
                    'rol' => 7,
                    'red_sebebi' => $sebep
                ]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Başvuru başarıyla reddedildi']);
            } else {
                return response()->json(['success' => false, 'message' => 'Güncelleme yapılamadı']);
            }
        } catch (\Exception $e) {
            \Log::error('Başvuru reddetme hatası: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
    }
    public function uyeSilDenetim(Request $request)
    {
        try {
            $uye_id = $request->input('uye_id');
            $silinme_sebebi = $request->input('silinme_sebebi');

            if (!$uye_id) {
                return response()->json(['success' => false, 'message' => 'Üye ID gerekli']);
            }

            if (!$silinme_sebebi) {
                return response()->json(['success' => false, 'message' => 'Silinme sebebi gerekli']);
            }

            $uye = DB::table('uyeler')->where('id', $uye_id)->first();
            if (!$uye) {
                return response()->json(['success' => false, 'message' => 'Üye bulunamadı']);
            }

            $updated = DB::table('uyeler')
                ->where('id', $uye_id)
                ->update([
                    'rol' => 5,
                    'silinme_sebebi' => $silinme_sebebi
                ]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Üye başarıyla silindi']);
            } else {
                return response()->json(['success' => false, 'message' => 'Üye silinemedi']);
            }
        } catch (\Exception $e) {
            \Log::error('Üye silme hatası: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
    }
    public function formEkle(Request $request)
    {
        $request->validate([
            'formTitle' => 'required|string|max:255',
            'formFile' => 'required|file|mimes:pdf|max:20480', // max 20MB
        ]);

        $file = $request->file('formFile');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('docs/formlar'), $fileName);

        DB::table('formlar')->insert([
            'isim' => $request->input('formTitle'),
            'dosya' => $fileName,
            'durum' => 1
        ]);

        return redirect()->route('denetim.formlar')->with('success', 'Form başarıyla eklendi.');
    }
    public function uyeMesajlari() {
        $mesajlar = DB::table('mesaj')
            ->join('ogrenci_bilgi', 'mesaj.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('mesaj.durum', 1)
            ->select(
                'mesaj.id as mesaj_id',
                'ogrenci_bilgi.numara',
                'ogrenci_bilgi.isim',
                'ogrenci_bilgi.soyisim',
                'ogrenci_bilgi.tel',
                'ogrenci_bilgi.eposta',
                'ogrenci_bilgi.fak_ad',
                'ogrenci_bilgi.bol_ad',
                'mesaj.mesaj'
            )
            ->orderByDesc('mesaj.id')
            ->get();
        return response()->json($mesajlar);
    }
    public function mesajSil(Request $request) {
        $mesaj_id = $request->input('mesaj_id');
        $updated = DB::table('mesaj')->where('id', $mesaj_id)->update(['durum' => 0]);
        return response()->json(['success' => $updated ? true : false]);
    }
    // Yönetim Kurulu Başvurusu
    public function yonetimKuruluBasvuru(Request $request)
    {
        $tc = $request->input('tc');
        $sifre = $request->input('sifre');
        $niyet_metni = $request->input('niyet_metni');
        $topluluk_id = $request->input('topluluk_id');

        // 1. LDAP doğrulama
        $jsonVerisi = [
            "Tip" => "ogrenci",
            "TcKn" => $tc,
            "Sifre" => $sifre,
        ];
        $curl = curl_init();
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
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($curlError) {
            \Log::error('LDAP API Error: ' . $curlError);
            return redirect()->back()->with('danger', 'Bağlantı hatası: ' . $curlError);
        }

        if ($httpCode !== 200) {
            \Log::error('LDAP API HTTP Error: ' . $httpCode . ' Response: ' . $response);
            return redirect()->back()->with('danger', 'Üniversite kimlik doğrulama servisi şu anda kullanılamıyor. Lütfen daha sonra tekrar deneyin.');
        }

        $data = json_decode($response, true);
        if (is_array($data) && isset($data['response'])) {
            $data = json_decode($data['response'], true);
        }
        if (!is_array($data) || !isset($data['Durum']) || !$data['Durum']) {
            return redirect()->back()->with('danger', 'Kimlik doğrulama başarısız.');
        }
        // 2. ogrenci_bilgi tablosunda var mı?
        $ogrenci = DB::table('ogrenci_bilgi')->where('tc', $tc)->first();
        if (!$ogrenci) {
            // API'den detaylı bilgi çek
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
            if (is_array($data) && isset($data['response'])) {
                $data = json_decode($data['response'], true);
            }
            if (!is_array($data) || !isset($data[0])) {
                return redirect()->back()->with('danger', 'Öğrenci bilgisi alınamadı.');
            }
            $ogrenciId = DB::table('ogrenci_bilgi')->insertGetId([
                'numara' => $data[0]["OGR_NO"],
                'tc' => $data[0]["TCK"],
                'isim' => $data[0]["AD"],
                'soyisim' => $data[0]["SOYAD"],
                'fak_ad' => $data[0]["FAK_AD"],
                'bol_ad' => $data[0]["BOL_AD"],
                'prog_ad' => $data[0]["PROG_AD"],
                'sınıf' => $data[0]["SINIF"],
                'kay_tar' => !empty($data[0]["KAY_TAR"]) ? \Carbon\Carbon::createFromFormat('d.m.Y', $data[0]["KAY_TAR"])->format('Y-m-d') : null,
                'ogrenim_durum' => $data[0]["OGRENIM_DURUM"],
                'ogrenim_tip' => $data[0]["OGRENIM_TIP"],
                'ayr_tar' => !empty($data[0]["AYR_TAR"]) ? \Carbon\Carbon::createFromFormat('d.m.Y', $data[0]["AYR_TAR"])->format('Y-m-d') : null,
                'tel' => $data[0]["TELEFON"],
                'tel2' => $data[0]["TELEFON2"],
                'eposta' => $data[0]["EPOSTA1"],
                'eposta2' => $data[0]["EPOSTA2"],
                'program_tip' => $data[0]["PROGRAM_TIP"],
                'durum' => $data[0]["DURUM"]
            ]);
            $ogrenci = DB::table('ogrenci_bilgi')->where('id', $ogrenciId)->first();
        }
        // 3. uyeler tablosunda bu topluluk için kayıt var mı?
        $uye = DB::table('uyeler')
            ->where('ogr_id', $ogrenci->id)
            ->where('top_id', $topluluk_id)
            ->first();
        if (!$uye) {
            return redirect()->back()->with('danger', 'Önce bu topluluğa üye olmalısınız.');
        }
        // 4. Eğer zaten yönetim kurulu başvurusu varsa tekrar ekleme
        if ($uye->rol == 6) {
            return redirect()->back()->with('danger', 'Bu topluluğun yönetimine başvurunuz bulunmakta.');
        }
        if ($uye->rol == 2 || $uye->rol == 3) {
            return redirect()->back()->with('danger', 'Zaten bu topluluktasınız.');
        }
        // 5. Yönetim kurulu başvurusu olarak güncelle
        DB::table('uyeler')
            ->where('id', $uye->id)
            ->update([
                'rol' => 6,
                'niyet_metni' => $niyet_metni
            ]);
        return redirect()->back()->with('success', 'Yönetim kurulu başvurunuz alındı.');
    }
    // --- SOSYAL MEDYA DENETİM İŞLEMLERİ ---
    public function sosyalMedyaListesi()
    {
        try {
            $veriler = DB::table('sosyal_medya')
                ->join('topluluklar', 'sosyal_medya.t_id', '=', 'topluluklar.id')
                ->select(
                    'sosyal_medya.t_id',
                    'topluluklar.isim as topluluk_adi',
                    'topluluklar.gorsel as logo',
                    'sosyal_medya.instagram',
                    'sosyal_medya.whatsapp',
                    'sosyal_medya.linkedln',
                    'sosyal_medya.i_onay',
                    'sosyal_medya.w_onay',
                    'sosyal_medya.l_onay',
                    'sosyal_medya.i_red',
                    'sosyal_medya.w_red',
                    'sosyal_medya.l_red'
                )
                ->get();
            return response()->json($veriler);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sosyalMedyaOnayla(Request $request)
    {
        $t_id = $request->input('t_id') ?? $request->json('t_id');
        $type = $request->input('type') ?? $request->json('type');
        $column = null;
        if ($type === 'instagram') $column = 'i_onay';
        if ($type === 'whatsapp') $column = 'w_onay';
        if ($type === 'linkedln') $column = 'l_onay';
        if (!$column) return response()->json(['success'=>false, 'message'=>'Geçersiz tip']);
        DB::table('sosyal_medya')->where('t_id', $t_id)->update([
            $column => 1
        ]);
        return response()->json(['success'=>true]);
    }

    public function sosyalMedyaReddet(Request $request)
    {
        $t_id = $request->input('t_id') ?? $request->json('t_id');
        $type = $request->input('type') ?? $request->json('type');
        $sebep = $request->input('sebep') ?? $request->json('sebep');
        $onayCol = null; $redCol = null;
        if ($type === 'instagram') { $onayCol = 'i_onay'; $redCol = 'i_red'; }
        if ($type === 'whatsapp') { $onayCol = 'w_onay'; $redCol = 'w_red'; }
        if ($type === 'linkedln') { $onayCol = 'l_onay'; $redCol = 'l_red'; }
        if (!$onayCol || !$redCol) return response()->json(['success'=>false, 'message'=>'Geçersiz tip']);
        DB::table('sosyal_medya')->where('t_id', $t_id)->update([
            $onayCol => 0,
            $redCol => $sebep
        ]);
        return response()->json(['success'=>true]);
    }

    public function getSosyalMedyaLinks($topluluk_id)
    {
        return \DB::table('sosyal_medya')->where('t_id', $topluluk_id)->first();
    }

    // Excel indirme fonksiyonu
    public function uyeExcelIndir($topluluk_id)
    {
        $topluluk = DB::table('topluluklar')->where('id', $topluluk_id)->first();
        if (!$topluluk) {
            return redirect()->back()->with('danger', 'Topluluk bulunamadı.');
        }
        $filename = $topluluk->isim . '_uyeler_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new UyelerExport($topluluk_id), $filename);
    }

    // Topluluk listesi için AJAX endpoint
    public function getTopluluklar()
    {
        $topluluklar = DB::table('topluluklar')
            ->where('durum', 1)
            ->select('id', 'isim')
            ->orderBy('isim')
            ->get();
        
        return response()->json($topluluklar);
    }
}
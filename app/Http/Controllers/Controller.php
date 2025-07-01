<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Topluluk;
use App\Models\OgrenciBilgi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Topluluklar sayfası
    public function topluluklarIndex()
    {
        $topluluklar = Topluluk::where('durum', 1)->get();
        $uye_sayisi = \DB::table('uyeler')->whereIn('rol', [1,2,3,6])->count();
        return view('topluluklar', compact('topluluklar','uye_sayisi'));
    }

    // Ana sayfa
    public function anasayfaIndex()
    {
        // İstatistikleri hesapla
        $topluluk_sayisi = \DB::table('topluluklar')->where('durum', 1)->count();
        $uye_sayisi = \DB::table('uyeler')->whereIn('rol', [1,2,3,6])->count();
        
        // Toplam etkinlik sayısı (etkinlik_gecmis tablosunda e_onay=1 olanlar)
        $etkinlik_sayisi = \DB::table('etkinlik_gecmis')
            ->where('e_onay', 1)
            ->count();
        
        // Etkinlik slider için geçmiş etkinlikler (onaylı)
        $gecmis_etkinlikler = \DB::table('etkinlik_gecmis as eg')
            ->join('etkinlik_bilgi as eb', 'eg.e_id', '=', 'eb.id')
            ->join('topluluklar as t', 'eb.t_id', '=', 't.id')
            ->where('eg.e_onay', 1)
            ->select('eg.id', 'eg.e_id', 'eb.isim as etkinlik_adi', 'eg.bilgi', 'eg.resim', 'eg.aciklama', 't.id as topluluk_id', 't.isim as topluluk_adi')
            ->orderByDesc('eg.id')
            ->limit(12)
            ->get();
        
        return view('anasayfa', compact('topluluk_sayisi', 'uye_sayisi', 'etkinlik_sayisi', 'gecmis_etkinlikler'));
    }

    public function kesfetIndex()
    {
        $kesfet = DB::table('topluluklar')
        ->join('etkinlik_bilgi', 'etkinlik_bilgi.t_id', '=', 'topluluklar.id')
        ->where('etkinlik_bilgi.k_durum', '=', '1')
        ->where('etkinlik_bilgi.talep_onay', '=', '1')
        ->orderBy('etkinlik_bilgi.tarih', 'asc')
        ->select(
            'etkinlik_bilgi.id as eb_id',
            'etkinlik_bilgi.isim as eb_isim',
            'topluluklar.id as t_id',
            'topluluklar.isim as t_isim',
            'etkinlik_bilgi.gorsel as eb_gorsel',
            'etkinlik_bilgi.bilgi as eb_bilgi',
            'etkinlik_bilgi.metin as eb_metin',
            'etkinlik_bilgi.tarih as eb_tarih',
            'etkinlik_bilgi.bitis_tarihi as eb_bitis_tarihi',
            'etkinlik_bilgi.konum as eb_konum'
        )
        ->paginate(12);

        return view('kesfet', compact('kesfet'));
    }

    public function etkinlikBasvuru(Request $request)
    {
        $tckn = $request->input('tckn');
        $sifre = $request->input('sifre');
        $e_id = $request->input('e_id');
        $t_id = $request->input('t_id');

        // 1. LDAP/Üniversite API ile doğrulama
        $jsonVerisi = [
            "Tip" => "ogrenci",
            "TcKn" => $tckn,
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
            return response()->json(['success' => false, 'message' => 'Bağlantı hatası: ' . $curlError]);
        }

        if ($httpCode !== 200) {
            \Log::error('LDAP API HTTP Error: ' . $httpCode . ' Response: ' . $response);
            return response()->json(['success' => false, 'message' => 'Üniversite kimlik doğrulama servisi şu anda kullanılamıyor. Lütfen daha sonra tekrar deneyin.']);
        }

        $data = json_decode($response, true);
        if (is_array($data) && isset($data['response'])) {
            $data = json_decode($data['response'], true);
        }

        if (!is_array($data) || !isset($data['Durum'])) {
            \Log::error('LDAP API Invalid Response: ' . $response);
            return response()->json(['success' => false, 'message' => 'Kimlik doğrulama yanıtı geçersiz. Lütfen daha sonra tekrar deneyin.']);
        }

        if (!$data['Durum']) {
            return response()->json(['success' => false, 'message' => 'TC Kimlik No veya şifre hatalı. Lütfen bilgilerinizi kontrol edip tekrar deneyin.']);
        }

        // 2. Etkinlik başvuru durumu kontrolü
        $etkinlik = DB::table('etkinlik_bilgi')->where('id', $e_id)->first();
        if (!$etkinlik) {
            return response()->json(['success' => false, 'message' => 'Etkinlik bulunamadı.']);
        }
        
        if ($etkinlik->b_durum != 1) {
            return response()->json(['success' => false, 'message' => 'Etkinlik başvurusu şu anda açık değildir.']);
        }

        // 3. ogrenci_bilgi tablosunda var mı?
        $ogrenci = DB::table('ogrenci_bilgi')->where('tc', $tckn)->first();
        if (!$ogrenci) {
            // API'den detaylı bilgi çek
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://api.erbakan.edu.tr/KismiZamanli/getOgrenciOzlukBilgi?tcKimlikNo=' . $tckn,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic a2lzbWl6YW1hbmxpOlRRZ0FSajlTRUItdmg0MQ=='
                ),
            ));
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpCode !== 200) {
                return response()->json(['success' => false, 'message' => 'Öğrenci bilgileri alınamıyor. Lütfen daha sonra tekrar deneyin.']);
            }

            $data = json_decode($response, true);
            if (is_array($data) && isset($data['response'])) {
                $data = json_decode($data['response'], true);
            }

            if (!$data || !isset($data[0])) {
                return response()->json(['success' => false, 'message' => 'Öğrenci bilgileri bulunamadı. Lütfen TC Kimlik No\'nuzu kontrol edin.']);
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

        // 4. Üyelik kontrolü
        $uye = DB::table('uyeler')
            ->where('ogr_id', $ogrenci->id)
            ->where('top_id', $t_id)
            ->orderByDesc('rol')
            ->first();

        if (!$uye) {
            $toplulukIsim = DB::table('topluluklar')->where('id', $t_id)->value('isim');
            return response()->json([
                'success' => false,
                'message' => 'Etkinliğe başvurmak için önce topluluğa üye olmalısınız.',
                'redirect' => route('uyeislemleri', ['isim' => \Str::slug($toplulukIsim), 'id' => $t_id])
            ]);
        }

        // Rol kontrolü
        if (in_array($uye->rol, [1,2,3,6])) {
            // 5. Daha önce başvuru var mı?
            $varMi = DB::table('etkinlik_basvuru')
                ->where('u_id', $uye->id)
                ->where('e_id', $e_id)
                ->first();
            if ($varMi) {
                return response()->json(['success' => false, 'message' => 'Bu etkinliğe zaten başvurdunuz.']);
            }
            // 6. Başvuru kaydı ekle
            DB::table('etkinlik_basvuru')->insert([
                'u_id' => $uye->id,
                'e_id' => $e_id,
                'tarih' => now(),
            ]);
            return response()->json(['success' => true, 'message' => 'Etkinlik başvurunuz başarıyla alındı.']);
        } elseif ($uye->rol == 4) {
            return response()->json(['success' => false, 'message' => 'Üyelik başvurunuzun onaylanması beklenmektedir.']);
        } elseif ($uye->rol == 5) {
            return response()->json(['success' => false, 'message' => 'Bu topluluğa üyeliğiniz silinmiştir.']);
        } elseif ($uye->rol == 7) {
            return response()->json(['success' => false, 'message' => 'Üyelik başvurunuz kabul edilmemiştir.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Topluluk üyeliğiniz etkinlik başvurusu için uygun değil.']);
        }
    }

    // AJAX: YOKLAMA için etkinlikleri getir
    public function yoklamaEtkinlikler(Request $request)
    {
        $t_id = $request->query('t_id');
        $etkinlikler = DB::table('etkinlik_bilgi')
            ->where('t_id', $t_id)
            ->where('y_durum', 1)
            ->select('id', 'isim')
            ->get();
        return response()->json($etkinlikler);
    }

    // AJAX: Yoklama kaydet
    public function yoklamaKaydet(Request $request)
    {
        $tc = $request->input('tc');
        $sifre = $request->input('sifre');
        $e_id = $request->input('e_id');
        $katildim = $request->input('katildim') ? 1 : 0;

        // 1. Kimlik doğrulama (API)
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
        curl_close($curl);
        $data = json_decode($response, true);
        if (is_array($data) && isset($data['response'])) {
            $data = json_decode($data['response'], true);
        }
        if (!is_array($data) || !isset($data['Durum']) || !$data['Durum']) {
            return response()->json(['success' => false, 'message' => 'Kimlik doğrulama başarısız. TC veya şifre hatalı.']);
        }
        // 2. ogrenci_bilgi tablosunda var mı?
        $ogrenci = DB::table('ogrenci_bilgi')->where('tc', $tc)->first();
        if (!$ogrenci) {
            return response()->json(['success' => false, 'message' => 'Öğrenci bulunamadı. Önce topluluğa üye olun.']);
        }
        // 3. Etkinlik bilgisi kontrolü (talep_onay=1 ve y_durum=1)
        $etkinlik = DB::table('etkinlik_bilgi')->where('id', $e_id)->where('talep_onay', 1)->where('y_durum', 1)->first();
        if (!$etkinlik) {
            return response()->json(['success' => false, 'message' => 'Etkinlik başvurusu açık değil veya etkinlik aktif değil.']);
        }
        // 4. Uye tablosunda bul (aktif üye, topluluk id'si etkinliğin t_id'si ile eşleşmeli)
        $uye = DB::table('uyeler')
            ->where('ogr_id', $ogrenci->id)
            ->where('top_id', $etkinlik->t_id)
            ->where('durum', 1)
            ->first();
        if (!$uye) {
            return response()->json(['success' => false, 'message' => 'Lütfen önce bu topluluğa üye olunuz.']);
        }
        // 5. Yoklama kaydı eklemeden önce kontrol et
        $varMi = DB::table('etkinlik_yoklama')
            ->where('u_id', $uye->id)
            ->where('e_id', $e_id)
            ->first();
        if ($varMi) {
            return response()->json(['success' => false, 'message' => 'Bu etkinlik için yoklama kaydınız bulunmaktadır.']);
        }
        DB::table('etkinlik_yoklama')->insert([
            'u_id' => $uye->id,
            'e_id' => $e_id,
            'yoklama' => $katildim,
            'tarih' => now(),
        ]);
        return response()->json(['success' => true, 'message' => 'Yoklama başarıyla kaydedildi.']);
    }

    // Kullanıcıya ait yoklama durumlarını döndür
    public function yoklamaDurumuGoruntule(Request $request)
    {
        $tc = $request->input('tc');
        $sifre = $request->input('sifre');
        // 1. Kimlik doğrulama (LDAP API ile)
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
        curl_close($curl);
        $data = json_decode($response, true);
        if (is_array($data) && isset($data['response'])) {
            $data = json_decode($data['response'], true);
        }
        if (!is_array($data) || !isset($data['Durum']) || !$data['Durum']) {
            return response()->json(['success' => false, 'message' => 'Kimlik doğrulama başarısız.']);
        }
        // 2. ogrenci_bilgi tablosundan id bul
        $ogrenci = \DB::table('ogrenci_bilgi')->where('tc', $tc)->first();
        if (!$ogrenci) {
            return response()->json(['success' => false, 'message' => 'Öğrenci bulunamadı.']);
        }
        // 3. Kullanıcının üye olduğu topluluklar
        $uyelikler = \DB::table('uyeler')
            ->where('ogr_id', $ogrenci->id)
            ->pluck('top_id');
        $sonuclar = [];
        foreach ($uyelikler as $top_id) {
            $topluluk = \DB::table('topluluklar')->where('id', $top_id)->first();
            if (!$topluluk) continue;
            // Etkinlik_gecmis tablosunda e_onay=1 olan etkinlikler
            $etkinlikler = \DB::table('etkinlik_gecmis as eg')
                ->join('etkinlik_bilgi as eb', 'eg.e_id', '=', 'eb.id')
                ->where('eb.t_id', $top_id)
                ->where('eg.e_onay', 1)
                ->select('eb.id', 'eb.isim', 'eb.tarih')
                ->get();
            $etkinlik_ids = $etkinlikler->pluck('id');
            $toplam = $etkinlikler->count();
            // Kullanıcının katıldığı etkinlikler (yoklama=1)
            $uye_ids = \DB::table('uyeler')->where('ogr_id', $ogrenci->id)->where('top_id', $top_id)->pluck('id');
            $katildigi = \DB::table('etkinlik_yoklama')
                ->whereIn('u_id', $uye_ids)
                ->whereIn('e_id', $etkinlik_ids)
                ->where('yoklama', 1)
                ->count();
            // Detay: etkinlik bazında katılım
            $detaylar = [];
            foreach ($etkinlikler as $etk) {
                $yoklama = \DB::table('etkinlik_yoklama')
                    ->whereIn('u_id', $uye_ids)
                    ->where('e_id', $etk->id)
                    ->orderByDesc('tarih')
                    ->first();
                $detaylar[] = [
                    'etkinlik' => $etk->isim,
                    'tarih' => $etk->tarih,
                    'katildi' => $yoklama && $yoklama->yoklama == 1 ? true : false
                ];
            }
            $sonuclar[] = [
                'topluluk' => $topluluk->isim,
                'toplam_etkinlik' => $toplam,
                'katildigi' => $katildigi,
                'oran' => $toplam > 0 ? ($katildigi . '/' . $toplam) : '0/0',
                'detaylar' => $detaylar
            ];
        }
        return response()->json(['success' => true, 'sonuclar' => $sonuclar]);
    }

}

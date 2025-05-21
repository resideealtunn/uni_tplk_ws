<?php

// app/Http/Controllers/ToplulukController.php

namespace App\Http\Controllers;

use App\Models\Topluluk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Uye;

class ToplulukController extends Controller
{
    public function show($topluluk_isim, $topluluk_id)
    {
        $topluluk = Topluluk::find($topluluk_id);
        if (!$topluluk) {
            return abort(404, 'Topluluk bulunamadı');
        }

        return view('tplk_anasayfa', ['topluluk' => $topluluk]);
    }
    public function uyeIslemleri($isim, $id)
    {
        $topluluk = Topluluk::find($id);

        if (!$topluluk) {
            abort(404, 'Topluluk bulunamadı.');
        }

        return view('tplk_uyeislemleri', compact('topluluk'));
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
        curl_close($curl);
        $data = json_decode($response, true);
        if($data["Durum"]) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://api.erbakan.edu.tr/KismiZamanli/getOgrenciOzlukBilgi?tcKimlikNo=' . "123",
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
            @dd($response);
            curl_close($curl);
            $data = json_decode($response, true);
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
                    'kay_tar' => $yeniTarih,
                    'ogrenim_durum' => $data[0]["OGRENIM_DURUM"],
                    'ogrenim_tip' => $data[0]["OGRENIM_TIP"],
                    'ayr_tar' => $data[0]["AYR_TAR"],
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
            $uye = DB::table('uyeler')
                ->where('ogr_id', $id)
                ->where('top_id', $top)
                ->first();
            if ($uye) {
                return redirect()->back()->with('danger', 'Zaten Bu Topluluğa Üyesiniz!');
            }
            else {
                $membershipForm = $request->file('membership_form');
                $tarih = now()->format('Y-m-d_H-i-s');
                $dosyaAdi = $tc . '_' . $tarih . '.' . $membershipForm->getClientOriginalExtension();
                $uye = new Uye();
                $uye->ogr_id = $id;
                $uye->top_id = $top;
                $uye->tarih = $tarih;
                $uye->belge = $dosyaAdi;
                $uye->save();
                $membershipForm->move(public_path('docs/kayit_belge'), $dosyaAdi);
                return redirect()->back()->with('success', 'Kayıt Başarıyla Tamamlandı!');
            }
        }
        else
        {
            return redirect()->back()->with('danger', 'Üniversiteye Kaydınız Bulunamadı!');
        }
    }
    public function index()
    {
        $topluluklar = Topluluk::all();
        return view('denetim_uye', compact('topluluklar'));
    }
    public function uyeListesi($id)
    {
        $topluluklar = Topluluk::all();
        $uyeler = DB::table('uyeler')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('uyeler.top_id', $id)
            ->select(
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


}



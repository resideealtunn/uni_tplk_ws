<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Etkinlik_bilgi;

class YoneticiController extends Controller
{
    public function giris(Request $request)
    {
        $tc = $request->input('tc');
        $sifre = $request->input('sifre');

        $ogrenci = DB::table('ogrenci_bilgi')
            ->where('tc', $tc)
            ->where('sifre', $sifre)
            ->first();

        if (!$ogrenci) {
            return back()->with('error', 'TC veya Şifre yanlış');
        }

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
        @dd($topluluk);
        session([
            'ogrenci_id' => $ogrenci->id,
            'isim'=>$ogrenci->isim,
            't_id'=>$uye->t_id,
            'topluluk'=>$topluluk->isim,
            'rol' => $rol->rol
        ]);
        return redirect()->route('yonetici.panel');
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
            $afisAdi = time() . '_' . $afis->getClientOriginalName();
            $afis->move(public_path('images/etkinlik'), $afisAdi);
            $afisYolu = 'images/etkinlik/' . $afisAdi;
        } else {
            $afisYolu = null;
        }

        // Veritabanına kaydet
        DB::table('etkinlik_bilgi')->insert([
            'isim' => $request->input('baslik'),
            'bilgi' => $request->input('kisa_bilgi'),
            'metin' => $request->input('aciklama'),
            'gorsel' => $afisAdi,
            'tarih' => $request->input('tarih'),

        ]);

        return back()->with('success', 'Etkinlik başarıyla eklendi!');
    }
    public function etkinlikIslemleri()
    {
        $toplulukId = session('t_id');
        $etkinlikler = etkinlik_bilgi::where('t_id', $toplulukId)->get();

        return view('etkinlik_islemleri', compact('etkinlikler'));
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

        // Etkinlikler ve yetkinlikler verilerini al
        $etkinlikler = etkinlik_bilgi::where('t_id', $toplulukId)->get();
        $yetkinlikler = etkinlik_bilgi::where('t_id', $toplulukId)
            ->where('b_durum', 0)
            ->get();

        // Her iki veri kümesini view'e gönder
        return view('etkinlik_islemleri', compact('etkinlikler', 'yetkinlikler'));
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

        // Gerekli tüm veri setlerini çek
        $etkinlikler = etkinlik_bilgi::where('t_id', $toplulukId)->get();

        $yetkinlikler = etkinlik_bilgi::where('t_id', $toplulukId)
            ->where('b_durum', 0)
            ->get();

        $petkinlikler = etkinlik_bilgi::where('t_id', $toplulukId)
            ->where('p_durum', 0)
            ->get(); // Bu satırda .first() değil ->get() olmalı!

        return view('etkinlik_islemleri', compact('etkinlikler', 'yetkinlikler', 'petkinlikler'));
    }

    public function etkinlikPaylas(Request $request)
    {
        $etkinlik = Etkinlik_bilgi::find($request->paylasEtkinlikSec);

        // Yeni görsel yükleme
        if ($request->hasFile('paylasResim')) {
            $afis = $request->file('paylasResim');
            $afisAdi = time() . '_' . $afis->getClientOriginalName();
            $afis->move(public_path('images/etkinlik'), $afisAdi);
            $etkinlik->gorsel = 'images/etkinlik/' . $afisAdi;
        }
        // Güncellemeler
        $etkinlik->bilgi = $request->paylasKisaBilgi;
        $etkinlik->metin = $request->paylasAciklama;
        $etkinlik->gorsel=$afisAdi;
        $etkinlik->p_durum = 1;
        $etkinlik->save();

        return back()->with('success', 'Etkinlik başarıyla paylaşıldı.');
    }
    public function basvurular(Request $request)
    {
        $toplulukId = session('t_id');

        // Gerekli tüm veri setlerini çek
        $etkinlikler = etkinlik_bilgi::where('t_id', $toplulukId)->get();

        $yetkinlikler = etkinlik_bilgi::where('t_id', $toplulukId)
            ->where('b_durum', 0)
            ->get();

        $petkinlikler = etkinlik_bilgi::where('t_id', $toplulukId)
            ->where('p_durum', 0)
            ->get(); // Bu satırda .first() değil ->get() olmalı!

        //$etkinlik_id=$request->etkinlik->id;
        $basvurular = DB::table('etkinlik_basvuru')
            ->join('uyeler', 'etkinlik_basvuru.u_id', '=', 'uyeler.id') // Assuming 'id' is the primary key in 'uyeler'
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id') // Assuming 'id' is the primary key in 'ogrencii_bilgi'
            ->select('ogrenci_bilgi.isim', 'ogrenci_bilgi.numara', 'ogrenci_bilgi.bolum', 'ogrenci_bilgi.tel', 'etkinlik_basvuru.u_id')
            ->where('etkinlik_basvuru.e_id',1 ) // Ensure to replace 'event_id' with the actual event column
            ->get();

        return view('etkinlik_islemleri', compact('etkinlikler', 'yetkinlikler', 'petkinlikler', 'basvurular'));
    }

}


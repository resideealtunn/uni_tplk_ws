<?php


namespace App\Http\Controllers;
use App\Models\Etkinlik_bilgi;
use App\Models\Topluluk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EtkinlikController extends Controller
{
    public function show(Request $request, $topluluk_isim, $topluluk_id)
    {
        $topluluk = Topluluk::find($topluluk_id);

        if (!$topluluk) {
            return abort(404, 'Topluluk bulunamadı.');
        }

        // Aktif etkinlikler: talep_onay = 1 ve k_durum = 1
        $activeEvents = Etkinlik_bilgi::where('t_id', $topluluk_id)
            ->where('talep_onay', 1)
            ->where('k_durum', 1)
            ->select('*') // Tüm alanları getir, tarih ve bitis_tarihi dahil
            ->get();

        // Geçmiş etkinlikler: etkinlik_gecmis.e_onay = 1
        $pastEvents = DB::table('etkinlik_gecmis as eg')
            ->join('etkinlik_bilgi as eb', 'eg.e_id', '=', 'eb.id')
            ->where('eb.t_id', $topluluk_id)
            ->where('eg.e_onay', 1)
            ->select('eb.*', 'eg.resim', 'eg.bilgi as gecmis_bilgi', 'eg.aciklama', 'eg.e_onay', 'eg.red_sebebi')
            ->get();

        // Sosyal medya linklerini çek
        $sosyal_medya = app(\App\Http\Controllers\ToplulukController::class)->getSosyalMedyaLinks($topluluk_id);

        return view('tplk_etkinlikler', compact('topluluk', 'activeEvents', 'pastEvents', 'sosyal_medya'));
    }
    public function onayBekleyenEtkinlikler()
    {
        // Talep etkinlikler: sadece talep_onay=2 olanlar
        $onayBekleyenEtkinlikler = DB::table('etkinlik_bilgi as tb')
            ->join('topluluklar as t', 't.id', '=', 'tb.t_id')
            ->where('tb.talep_onay', 2)
            ->select(
                'tb.id as onay_id',
                'tb.id as etkinlik_id',
                't.isim as topluluk_adi',
                'tb.isim as etkinlik_adi',
                'tb.tarih as tarih',
                'tb.bitis_tarihi as bitis_tarihi',
                'tb.konum as konum',
                'tb.gorsel as gorsel',
                'tb.bilgi as bilgi',
                'tb.metin as metin',
                't.gorsel as logo'
            )
            ->get();

        // Gerçekleşmiş etkinlikler: sadece e_onay=2 olanlar
        $aktifEtkinlikler = DB::table('etkinlik_gecmis as eg')
            ->join('etkinlik_bilgi as tb', 'eg.e_id', '=', 'tb.id')
            ->join('topluluklar as t', 't.id', '=', 'tb.t_id')
            ->where('eg.e_onay', 2)
            ->select(
                'eg.id as onay_id',
                'tb.id as etkinlik_id',
                't.isim as topluluk_adi',
                't.gorsel as logo',
                'tb.isim as etkinlik_adi',
                'tb.tarih',
                'tb.bitis_tarihi',
                'tb.konum as konum',
                'eg.resim as gorsel',
                'eg.bilgi',
                'eg.aciklama as metin',
                'eg.red_sebebi'
            )
            ->get();

        return view('denetim_etkinlik', compact('onayBekleyenEtkinlikler', 'aktifEtkinlikler'));
    }
    public function onayIslemi(Request $request)
    {
        if ($request->input('onay') === null) {
            return redirect()->back()->with('error', 'Onay veya Red işlemi seçilmedi. Lütfen tekrar deneyin.');
        }
        $tip = $request->input('tip');
        $onayId = $request->input('onay_id');
        $onayDurumu = $request->input('onay');
        $mesaj = $onayDurumu == 1 ? 'Onaylandı' : $request->input('mesaj', ' ');

        if ($tip == 1) {
            // Etkinlik bilgisini doğrudan güncelle
                if ($onayDurumu == 1) {
                DB::table('etkinlik_bilgi')->where('id', $onayId)->update(['talep_onay' => 1, 'talep_red' => null]);
                } else {
                DB::table('etkinlik_bilgi')->where('id', $onayId)->update([
                        'talep_onay' => 0,
                        'talep_red' => $mesaj
                    ]);
            }
        } elseif ($tip == 2) {
            // Gerçekleşmiş etkinlikler için onay/red işlemi
            $etkinlikGecmis = DB::table('etkinlik_gecmis')->where('id', $onayId)->first();
            
            if ($etkinlikGecmis) {
                if ($onayDurumu == 1) {
                    // etkinlik_gecmis tablosunu güncelle
                    DB::table('etkinlik_gecmis')->where('id', $onayId)->update([
                        'e_onay' => 1,
                        'red_sebebi' => null
                    ]);
        
                    // etkinlik_bilgi tablosunu da güncelle (aynı etkinlik_id kullanılarak)
                    DB::table('etkinlik_bilgi')->where('id', $etkinlikGecmis->e_id)->update([
                        'talep_onay' => 3
                    ]);
                } else {
                    DB::table('etkinlik_gecmis')->where('id', $onayId)->update([
                        'e_onay' => 0,
                        'red_sebebi' => $mesaj
                    ]);
                }
            }
        }
        else {
            return redirect()->back()->with('error', 'Geçersiz işlem türü.');
        }
        $successMessage = $onayDurumu == 1 ? 'Etkinlik başarıyla onaylandı.' : 'Etkinlik reddedildi.';

        return redirect()->back()->with('success', $successMessage);
    }
    // Topluluğa ait aktif etkinlikleri JSON olarak döndür
    public function aktifEtkinlikler($topluluk_id)
    {
        $etkinlikler = DB::table('etkinlik_bilgi')
            ->where('t_id', $topluluk_id)
            ->where('talep_onay', 1)
            ->where('b_durum', 1)
            ->select('id', 'isim')
            ->get();
        return response()->json($etkinlikler);
    }
}


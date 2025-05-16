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

        $activeEvents = Etkinlik_bilgi::where('t_id', $topluluk_id)
            ->where('b_durum', 0)
            ->get();

        $pastEvents = Etkinlik_bilgi::where('t_id', $topluluk_id)
            ->where('b_durum', 1)
            ->get();

        return view('tplk_etkinlikler', compact('topluluk', 'activeEvents', 'pastEvents'));
    }
    public function onayBekleyenEtkinlikler()
    {
        $onayBekleyenEtkinlikler = DB::table('etkinlik_onay as eo')
            ->join('etkinlik_bilgi as tb', 'eo.e_id', '=', 'tb.id')
            ->join('topluluklar as t', 't.id', '=', 'tb.t_id')
            ->where('eo.onay', 0)
            ->select(
                'eo.id as onay_id',
                'tb.id as etkinlik_id',
                't.isim as topluluk_adi',
                'tb.isim as etkinlik_adi',
                'tb.tarih as tarih',
                'tb.gorsel as gorsel',
                'tb.bilgi as bilgi',
                'tb.metin as metin',
                't.gorsel as logo'
            )
            ->get();

        $aktifEtkinlikler = DB::table('etkinlik_bilgi as tb')
            ->join('topluluklar as t', 't.id', '=', 'tb.t_id')
            ->join('paylasim_onay as p', 'p.etkinlik_id', '=', 'tb.id')
            ->where('p.onay', 0)
            ->select(
                'p.id as onay_id',
                'tb.id as etkinlik_id',
                't.isim as topluluk_adi',
                't.gorsel as logo',
                'tb.isim as etkinlik_adi',
                'tb.tarih',
                'tb.gorsel',
                'tb.bilgi',
                'tb.metin'
            )
            ->get();

        return view('denetim_etkinlik', compact('onayBekleyenEtkinlikler', 'aktifEtkinlikler'));
    }
    public function onayIslemi(Request $request)
    {
        $tip = $request->input('tip');
        $onayId = $request->input('onay_id');
        $onayDurumu = $request->input('onay');
        $mesaj = $onayDurumu == 1 ? 'Onaylandı' : $request->input('mesaj', ' ');

        if ($tip == 1) {
            DB::table('etkinlik_onay')
                ->where('id', $onayId)
                ->update([
                    'onay' => $onayDurumu,
                    'mesaj' => $mesaj
                ]);
        } elseif ($tip == 2) {
            DB::table('paylasim_onay')
                ->where('id', $onayId)
                ->update([
                    'onay' => $onayDurumu,
                    'mesaj' => $mesaj
                ]);
        } else {
            return redirect()->back()->with('error', 'Geçersiz işlem türü.');
        }
        $successMessage = $onayDurumu == 1 ? 'Etkinlik başarıyla onaylandı.' : 'Etkinlik reddedildi.';

        return redirect()->back()->with('success', $successMessage);
    }
}


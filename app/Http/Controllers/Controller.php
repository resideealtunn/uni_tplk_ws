<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Topluluk;
use App\Models\Etkinlik_bilgi;
use App\Models\Etkinlikler;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Topluluklar sayfası
    public function topluluklarIndex()
    {
        $topluluklar = Topluluk::all();
        return view('topluluklar', compact('topluluklar'));
    }

    // Etkinlikler bilgisi sayfası
    public function etkinlikBilgiIndex()
    {
        $e_bilgi = Etkinlik_bilgi::all();
        return view('etkinlikler.index', compact('e_bilgi'));
    }

    // Etkinlikler sayfası
    public function etkinliklerIndex()
    {
        $etkinlikler = Etkinlikler::all();
        return view('etkinlikler.index', compact('etkinlikler'));
    }

    public function kesfetIndex()
    {
        $kesfet = DB::table('topluluklar')
            ->join('etkinlikler', 'etkinlikler.top_id', '=', 'topluluklar.id')
            ->join('etkinlik_bilgi', 'etkinlik_bilgi.id', '=', 'etkinlikler.e_id')
            ->where('etkinlik_bilgi.b_durum', '=', '1')
            ->orderBy('etkinlikler.b_tarih', 'desc')
            ->select(
                'etkinlik_bilgi.id as eb_id',
                'etkinlik_bilgi.isim as eb_isim',
                'topluluklar.id as t_id',
                'topluluklar.isim as t_isim',
                'etkinlik_bilgi.gorsel as eb_gorsel',
                'etkinlik_bilgi.metin as eb_metin'
            )
            ->get();

        return view('kesfet', compact('kesfet')); // DİKKAT: değişken adı "kesfet"
    }

}

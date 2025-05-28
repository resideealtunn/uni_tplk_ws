<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Topluluk;
use App\Models\OgrenciBilgi;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Topluluklar sayfası
    public function topluluklarIndex()
    {
        $topluluklar = Topluluk::all();
        $uyeler = OgrenciBilgi::all();
        return view('topluluklar', compact('topluluklar','uyeler'));
    }
    public function kesfetIndex()
    {
        $kesfet = DB::table('topluluklar')
            ->join('etkinlik_bilgi', 'etkinlik_bilgi.t_id', '=', 'topluluklar.id')
            ->join('etkinlik_onay', 'etkinlik_bilgi.id', '=', 'etkinlik_onay.e_id')
            ->where('etkinlik_bilgi.b_durum', '=', '1')
            ->where('etkinlik_onay.onay', '=', '1')
            ->orderBy('etkinlik_bilgi.tarih', 'desc')
            ->select(
                'etkinlik_bilgi.id as eb_id',
                'etkinlik_bilgi.isim as eb_isim',
                'topluluklar.id as t_id',
                'topluluklar.isim as t_isim',
                'etkinlik_bilgi.gorsel as eb_gorsel',
                'etkinlik_bilgi.bilgi as eb_bilgi',
                'etkinlik_bilgi.metin as eb_metin'
            )
            ->get();

        return view('kesfet', compact('kesfet'));
    }

}

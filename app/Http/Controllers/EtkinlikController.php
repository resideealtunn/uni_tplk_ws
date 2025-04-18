<?php


namespace App\Http\Controllers;

use App\Models\Etkinlik_bilgi;
use App\Models\Topluluk;
use Illuminate\Http\Request;

class EtkinlikController extends Controller
{
    public function show($topluluk_isim, $topluluk_id)
    {
        $topluluk = Topluluk::find($topluluk_id);
        if (!$topluluk) {
            return abort(404, 'Topluluk bulunamadı');
        }

        $activeEvents = etkinlik_bilgi::where('t_id', $topluluk_id)
            ->where('b_durum', 0)
            ->get();
        $pastEvents = etkinlik_bilgi::where('t_id', $topluluk_id)
            ->where('p_durum', 1)
            ->get();

        return view('tplk_etkinlikler', ['topluluk' => $topluluk, 'activeEvents' => $activeEvents, 'pastEvents' => $pastEvents]);
    }
}


<?php


namespace App\Http\Controllers;

use App\Models\Etkinlik_bilgi;
use App\Models\Topluluk;
use Illuminate\Http\Request;


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
}


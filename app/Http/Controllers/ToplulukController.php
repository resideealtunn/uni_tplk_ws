<?php

// app/Http/Controllers/ToplulukController.php

namespace App\Http\Controllers;

use App\Models\Topluluk;
use Illuminate\Http\Request;

class ToplulukController extends Controller
{
    public function show($topluluk_isim, $topluluk_id)
    {
        // Topluluk id'si ile topluluğu buluyoruz
        $topluluk = Topluluk::find($topluluk_id);

        // Eğer topluluk bulunamazsa 404 döndürüyoruz
        if (!$topluluk) {
            return abort(404, 'Topluluk bulunamadı');
        }

        return view('tplk_anasayfa', ['topluluk' => $topluluk]);
    }
}



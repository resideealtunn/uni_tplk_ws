<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YoneticiController;
use App\Http\Controllers\ToplulukController;
use App\Http\Controllers\EtkinlikController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [App\Http\Controllers\Controller::class, 'kesfetIndex'])->name('anasayfa');
Route::get('/kesfet', [App\Http\Controllers\Controller::class, 'kesfetIndex'])->name('kesfet');


Route::get('/topluluklar', [App\Http\Controllers\Controller::class, 'topluluklarIndex']);

Route::get('yonetici_panel', function () {
    return view('yonetici_panel');
})->name('yonetici_panel');

Route::get('etkinlik_islemleri', function () {
    return view('etkinlik_islemleri');
})->name('etkinlik_islemleri');

Route::post('/yonetici/giris', [YoneticiController::class, 'giris'])->name('yonetici.giris.post');
Route::get('/yonetici/giris', function () {
    return view('yonetici_giris');
})->name('yonetici.giris');
Route::get('yonetici_panel', [YoneticiController::class, 'yoneticiPanel'])->name('yonetici.panel');

Route::post('/etkinlik-ekle', [YoneticiController::class, 'etkinlikEkle'])->name('etkinlik.ekle');
Route::get('etkinlik_islemleri', [YoneticiController::class, 'etkinlikIslemleri'])->name('etkinlik_islemleri');
Route::post('/basvuru-guncelle', [YoneticiController::class, 'basvuruGuncelle'])->name('basvuru.guncelle');

Route::get('etkinlik_islemleri', [YoneticiController::class, 'yoklamaIslemleri'])->name('etkinlik_islemleri');
Route::post('/yoklama-guncelle', [YoneticiController::class, 'yoklamaGuncelle'])->name('yoklama.guncelle');

Route::get('etkinlik_islemleri', [YoneticiController::class, 'paylasilabilirEtkinlikler'])->name('etkinlik_islemleri');
Route::post('/etkinlik-paylas', [YoneticiController::class, 'etkinlikPaylas'])->name('etkinlik.paylas');

Route::post('/basvuru-goster', [YoneticiController::class, 'getir'])->name('basvuru.göster');

Route::get('uye_islemleri', function () {
    return view('panel_uye_islemleri');
})->name('uye_islemleri');

Route::post('/cikis', [App\Http\Controllers\YoneticiController::class, 'cikis'])->name('cikis');

Route::get('etkinlikler', function () {
    return view('tplk_etkinlikler');
})->name('etkinlikler');

Route::get('/topluluklar/{isim}/{id}', [ToplulukController::class, 'show'])->name('topluluk_anasayfa');
Route::get('/etkinlikler/{topluluk_isim}/{topluluk_id}', [EtkinlikController::class, 'show'])->name('etkinlikler');


Route::get('/yonetici.giris', [YoneticiController::class, 'giris']);

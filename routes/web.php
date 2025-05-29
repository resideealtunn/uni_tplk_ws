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

Route::post('/basvuru-goster', [YoneticiController::class, 'getir'])->name('basvuru.goster');

Route::get('uye_islemleri', function () {
    return view('panel_uye_islemleri');
})->name('uye_islemleri');

Route::post('/cikis', [App\Http\Controllers\YoneticiController::class, 'cikis'])->name('cikis');

Route::get('etkinlikler', function () {
    return view('tplk_etkinlikler');
})->name('etkinlikler');

Route::get('/topluluklar/{isim}/{id}', [ToplulukController::class, 'show'])->name('topluluk_anasayfa');
Route::post('/topluluklar/iletisim', [ToplulukController::class, 'Iletisim'])->name('iletisim');

Route::get('/etkinlikler/{topluluk_isim}/{topluluk_id}', [EtkinlikController::class, 'show'])->name('etkinlikler');

Route::get('/uyeislemleri/{isim}/{id}', [ToplulukController::class, 'uyeIslemleri'])->name('uyeislemleri');

Route::get('/yonetici.giris', [YoneticiController::class, 'giris']);

Route::post('/kayitol', [ToplulukController::class, 'kayitOl'])->name('kayitol');

Route::get('/formlar', [ToplulukController::class, 'form'])->name('formlar');

Route::post('/yonetici_panel', [YoneticiController::class, 'guncelle'])->name('yonetici.guncelle');
Route::get('/denetim/panel', function () {
    return view('denetim_panel');
})->name('denetim.panel');
Route::get('denetim/etkinlik', function () {
    return view('denetim_etkinlik');
})->name('denetim.etkinlik');
Route::get('denetim/etkinlik', [EtkinlikController::class, 'onayBekleyenEtkinlikler'])->name('denetim.etkinlik');

Route::post('/onay', [EtkinlikController::class, 'onayIslemi'])->name('onay.islemi');
Route::get('/denetim/uye', [ToplulukController::class, 'index'])->name('denetim.uye');
Route::get('/denetim/uye/{id}', [ToplulukController::class, 'uyeListesi'])->name('topluluk.uyeler');
Route::get('/denetim/uye/basvuru/{id}', [ToplulukController::class, 'basvuruListesi'])->name('topluluk.basvurular');
Route::post('/denetim/uye/onayla', [ToplulukController::class, 'updateApplicationStatus'])->name('topluluk.update');
Route::post('/denetim/uye/rol', [ToplulukController::class, 'updateRol'])->name('rol.update');

Route::post('/denetim/uye/ekle', [ToplulukController::class, 'yeniUyeEkle'])->name('uye.ekle');
Route::get('/denetim/uye/sil/{id}', [ToplulukController::class, 'getSilinecekUyeler'])->name('uye.sil');
Route::post('/denetim/uye/sil', [ToplulukController::class, 'deleteUye'])->name('uye.delete');
Route::get('/topluluk-ara', [ToplulukController::class, 'searchTopluluk']);

Route::GET('/ogrenci-ara', [ToplulukController::class, 'searchUye']);
Route::GET('/basvuru-ara', [ToplulukController::class, 'searchApply']);


Route::get('denetim/topluluk', function () {
    return view('denetim_topluluk');
})->name('denetim.topluluk');

Route::get('/denetim/topluluk', [ToplulukController::class, 'indextopluluk'])->name('denetim.topluluk');
Route::any('/denetim/topluluk-ekle', [ToplulukController::class, 'toplulukEkle'])->name('denetim.topluluk-ekle');

Route::get('/denetim/formlar', [ToplulukController::class, 'formlistele'])->name('denetim.formlar');

Route::get('/denetim/form-sil/{id}', [ToplulukController::class, 'formSil'])->name('denetim.form-sil');

Route::post('denetim/topluluk-sil', [ToplulukController::class, 'topluluksil'])->name('topluluk.sil');


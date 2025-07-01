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
Route::get('/', [App\Http\Controllers\Controller::class, 'anasayfaIndex'])->name('anasayfa');
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

Route::post('/kesfet-guncelle', [YoneticiController::class, 'kesfetGuncelle'])->name('kesfet.guncelle');

Route::get('etkinlik_islemleri', [YoneticiController::class, 'paylasilabilirEtkinlikler'])->name('etkinlik_islemleri');
Route::post('/etkinlik-paylas', [YoneticiController::class, 'etkinlikPaylas'])->name('etkinlik.paylas');

Route::post('/basvuru-goster', [YoneticiController::class, 'getir'])->name('basvuru.goster');

Route::get('uye_islemleri', function () {
    return view('panel_uye_islemleri');
})->name('uye_islemleri');

Route::post('/cikis', [App\Http\Controllers\YoneticiController::class, 'cikis'])->name('cikis');

// Route::get('etkinlikler', function () {
//     return view('tplk_etkinlikler');
// })->name('etkinlikler');

Route::get('/topluluklar/{isim}/{id}', [ToplulukController::class, 'show'])->name('topluluk_anasayfa');
Route::post('/topluluklar/iletisim', [ToplulukController::class, 'Iletisim'])->name('iletisim');

Route::get('/etkinlikler/{topluluk_id}/aktif', [App\Http\Controllers\EtkinlikController::class, 'aktifEtkinlikler']);
Route::get('/etkinlikler/{topluluk_isim}/{topluluk_id}', [EtkinlikController::class, 'show'])->name('etkinlikler');

Route::get('/uyeislemleri/{isim}/{id}', [ToplulukController::class, 'uyeIslemleri'])->name('uyeislemleri');

Route::get('/yonetici.giris', [YoneticiController::class, 'giris']);

Route::post('/kayitol', [ToplulukController::class, 'kayitol'])->name('kayitol');

Route::get('/formlar', [ToplulukController::class, 'form'])->name('formlar');

Route::post('/yonetici_panel', [YoneticiController::class, 'guncelle'])->name('yonetici.guncelle');
Route::get('/denetim/panel', [App\Http\Controllers\ToplulukController::class, 'denetimPanel'])->name('denetim.panel');
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
Route::post('/denetim/formlar', [ToplulukController::class, 'formEkle'])->name('denetim.form-ekle');

Route::get('/denetim/form-sil/{id}', [ToplulukController::class, 'formSil'])->name('denetim.form-sil');

Route::post('/denetim/topluluk-sil', [\App\Http\Controllers\ToplulukController::class, 'topluluksil']);
Route::get('/denetim/topluluk-silinme-sebebi/{id}', [\App\Http\Controllers\ToplulukController::class, 'toplulukSilinmeSebebi']);
Route::post('/denetim/topluluk-aktife-al', [\App\Http\Controllers\ToplulukController::class, 'toplulukAktifeAl']);

Route::post('/denetim/topluluk/ekle', function () {
    return response('OK', 200);
})->name('denetim.topluluk.ekle');

Route::get('/panel_geribildirim', function () {
    return view('panel_geribildirim');
})->name('panel_geribildirim');

Route::post('/denetim/panel/onayla', [App\Http\Controllers\ToplulukController::class, 'panelOnayla']);
Route::post('/denetim/panel/reddet', [App\Http\Controllers\ToplulukController::class, 'panelReddet']);

Route::get('/uye-listesi', [App\Http\Controllers\YoneticiController::class, 'getUyeListesi']);

Route::get('/basvuru-listesi', [App\Http\Controllers\YoneticiController::class, 'getBasvuruListesi']);

Route::post('/uye-guncelle', [App\Http\Controllers\YoneticiController::class, 'updateUye']);

Route::post('/uye-sil', [App\Http\Controllers\YoneticiController::class, 'uyeSil']);

Route::get('/silinen-uyeler', [App\Http\Controllers\YoneticiController::class, 'silinenUyeler']);

Route::get('/yonetim-basvurulari', [App\Http\Controllers\YoneticiController::class, 'yonetimBasvurulari']);

Route::get('/panel/geribildirim/talep-etkinlik', [App\Http\Controllers\YoneticiController::class, 'panelGeriBildirimTalepEtkinlik']);

Route::post('/panel/geribildirim/talep-etkinlik-guncelle', [App\Http\Controllers\YoneticiController::class, 'panelGeriBildirimTalepEtkinlikGuncelle']);

Route::get('/panel/geribildirim/gerceklesen-etkinlik', [App\Http\Controllers\YoneticiController::class, 'panelGeriBildirimGerceklesenEtkinlik']);

Route::post('/denetim/uye/basvuru/onayla', [App\Http\Controllers\ToplulukController::class, 'basvuruOnayla']);
Route::post('/denetim/uye/basvuru/reddet', [App\Http\Controllers\ToplulukController::class, 'basvuruReddet']);

Route::post('/denetim/uye/sil/denetim', [App\Http\Controllers\ToplulukController::class, 'uyeSilDenetim']);

Route::get('/denetim/uye/excel-indir/{topluluk_id}', [ToplulukController::class, 'uyeExcelIndir'])->name('denetim.uye.excel');

Route::get('/denetim/topluluklar', [ToplulukController::class, 'getTopluluklar'])->name('denetim.topluluklar');

Route::get('/denetim/uye/silinenler/{topluluk_id}', [ToplulukController::class, 'silinenUyelerGeriBildirim']);

Route::get('/uye-mesajlari', [ToplulukController::class, 'uyeMesajlari']);
Route::post('/mesaj-sil', [ToplulukController::class, 'mesajSil']);

// Keşfet ekranı etkinlik başvuru
Route::post('/etkinlik-basvuru', [App\Http\Controllers\Controller::class, 'etkinlikBasvuru'])->name('etkinlik.basvuru');

// Yoklama AJAX endpointleri
Route::get('/yoklama-etkinlikler', [App\Http\Controllers\Controller::class, 'yoklamaEtkinlikler']);
Route::post('/yoklama-kaydet', [App\Http\Controllers\Controller::class, 'yoklamaKaydet']);

Route::post('/yonetimkurulu-basvuru', [ToplulukController::class, 'yonetimKuruluBasvuru'])->name('yonetimkurulu.basvuru');

Route::post('/yoklama-durumu-goruntule', [App\Http\Controllers\Controller::class, 'yoklamaDurumuGoruntule']);

Route::get('/panel/geribildirim/silinen-uyeler', [\App\Http\Controllers\YoneticiController::class, 'silinenUyelerGeriBildirim']);

Route::get('/panel/geribildirim/web-arayuz-bilgileri', [\App\Http\Controllers\YoneticiController::class, 'webArayuzBilgileri']);

// Sosyal Medya Denetim İşlemleri
Route::get('/denetim/sosyal-medya-listesi', [App\Http\Controllers\ToplulukController::class, 'sosyalMedyaListesi']);
Route::post('/denetim/sosyal-medya-onayla', [App\Http\Controllers\ToplulukController::class, 'sosyalMedyaOnayla']);
Route::post('/denetim/sosyal-medya-reddet', [App\Http\Controllers\ToplulukController::class, 'sosyalMedyaReddet']);

Route::get('/panel/geribildirim/sosyal-medya-bilgileri', [\App\Http\Controllers\YoneticiController::class, 'sosyalMedyaGeriBildirim']);

Route::post('/yoklama-uyeler', [App\Http\Controllers\YoneticiController::class, 'yoklamaUyeler']);
Route::post('/yoklama-durum-guncelle', [App\Http\Controllers\YoneticiController::class, 'yoklamaDurumGuncelle']);

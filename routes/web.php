<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('tpl_anasayfa');
});

Route::get('tpl_anasayfa', function () {
    return view('tpl_anasayfa');
})->name('tpl_anasayfa');

Route::get('tpl_etkinlik', function () {
    return view('tpl_etkinlik');
})->name('tpl_etkinlik');

Route::get('tpl_uye', function () {
    return view('tpl_uye');
})->name('tpl_uye');

Route::get('yonetici_panel', function () {
    return view('yonetici_panel');
})->name('yonetici_panel');


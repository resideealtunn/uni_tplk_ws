<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etkinlik_bilgi extends Model
{
    protected $table = 'etkinlik_bilgi';
    protected $fillable = [
        'id', 
        'isim',
        'bilgi',
        'metin',
        'gorsel',
        'tarih',
        'bitis_tarihi',
        't_id',
        'b_durum',
        'y_durum',
        'p_durum',
        'talep_onay',
        'talep_red'
    ];
    public $timestamps = false;
}

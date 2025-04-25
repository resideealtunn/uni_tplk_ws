<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etkinlik_bilgi extends Model
{
    protected $table = 'etkinlik_bilgi';
    protected $sutun = ['id', 'isim','bilgi','metin','gorsel','tarih','t_id','b_durum','y_durum','p_durum'];
    public $timestamps = false;
}

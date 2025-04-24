<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personel extends Model
{
    protected $table = 'personel';
    protected $sutun = ['id', 'isim','tc','tip'];
    public $timestamps = false;
}

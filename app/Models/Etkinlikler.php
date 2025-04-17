<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etkinlikler extends Model
{
    protected $table = 'topluluklar';
    protected $sutun = ['id', 'e_id','top_id','b_tarih'];
    public $timestamps = false;
}

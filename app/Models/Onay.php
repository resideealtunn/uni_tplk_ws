<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onay extends Model
{
    protected $table = 'etkinlik_onay';
    protected $sutun = ['id', 'e_id','onay'];
    public $timestamps = false;
}

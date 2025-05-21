<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topluluk extends Model
{
    protected $table = 'topluluklar';
    protected $sutun = ['id', 'isim','gorsel','vizyon','misyon','tuzuk','kurulus','durum'];
    public $timestamps = false;
    public function uyeler()
    {
        return $this->hasMany(Uye::class, 'topluluk_id');
    }
}

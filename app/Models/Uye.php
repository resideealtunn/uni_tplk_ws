<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uye extends Model
{
    protected $table = 'uyeler';
    protected $sutun = ['id', 'ogr_id','top_id','rol','tarih','belge','durum'];
    public $timestamps = false;

    public function topluluk()
    {
        return $this->belongsTo(Topluluk::class);
    }
}

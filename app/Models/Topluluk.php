<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Topluluk extends Model
{
    protected $table = 'topluluklar';
    protected $sutun = ['id', 'isim','gorsel','vizyon','misyon','tuzuk','kurulus','durum'];
    public $timestamps = false;

    public function uyeler()
    {
        return $this->hasMany(Uye::class, 'topluluk_id');
    }

    // Varsayılan değerleri default_web tablosundan çek
    protected static function getDefaultWeb()
    {
        static $default;
        if (!$default) {
            $default = DB::table('default_web')->first();
        }
        return $default;
    }

    public function getGorselAttribute($value)
    {
        $val = $value ?: (self::getDefaultWeb()->logo ?? null);
        return $val ? preg_replace('/^public\//', '', $val) : null;
    }
    public function getBgAttribute($value)
    {
        $val = $value ?: (self::getDefaultWeb()->bg ?? null);
        return $val ? preg_replace('/^public\//', '', $val) : null;
    }
    public function getVizyonAttribute($value)
    {
        return $value ?: (self::getDefaultWeb()->vizyon ?? null);
    }
    public function getMisyonAttribute($value)
    {
        return $value ?: (self::getDefaultWeb()->misyon ?? null);
    }
    public function getTuzukAttribute($value)
    {
        $val = $value ?: (self::getDefaultWeb()->tuzuk ?? null);
        return $val ? preg_replace('/^public\//', '', $val) : null;
    }
    public function getSloganAttribute($value)
    {
        return $value ?: (self::getDefaultWeb()->slogan ?? null);
    }
}

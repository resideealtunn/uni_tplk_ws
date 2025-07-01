<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class UyelerExport implements FromCollection, WithHeadings
{
    protected $topluluk_id;

    public function __construct($topluluk_id)
    {
        $this->topluluk_id = $topluluk_id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('uyeler')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('uyeler.top_id', $this->topluluk_id)
            ->whereIn('uyeler.rol', [1,2,3,6])
            ->select(
                'ogrenci_bilgi.numara',
                'ogrenci_bilgi.isim',
                'ogrenci_bilgi.soyisim',
                'ogrenci_bilgi.fak_ad',
                'ogrenci_bilgi.bol_ad',
                'ogrenci_bilgi.sınıf'
            )
            ->orderBy('ogrenci_bilgi.numara')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Öğrenci Numarası',
            'Ad',
            'Soyad',
            'Fakülte',
            'Bölüm',
            'Sınıf',
        ];
    }
}

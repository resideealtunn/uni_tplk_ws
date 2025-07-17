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
                'ogrenci_bilgi.sınıf',
                'ogrenci_bilgi.tel',
                'ogrenci_bilgi.eposta',
                'uyeler.tarih',
                'uyeler.rol'
            )
            ->orderBy('ogrenci_bilgi.numara')
            ->get()
            ->map(function ($item) {
                // Rol bilgisini metin olarak ekle
                $rolText = '';
                switch ($item->rol) {
                    case 1:
                        $rolText = 'Üye';
                        break;
                    case 2:
                        $rolText = 'Başkan';
                        break;
                    case 3:
                        $rolText = 'Başkan Yardımcısı';
                        break;
                    case 6:
                        $rolText = 'Yönetim Başvuru';
                        break;
                    default:
                        $rolText = 'Üye';
                }
                
                return [
                    'numara' => $item->numara,
                    'isim' => $item->isim,
                    'soyisim' => $item->soyisim,
                    'fakulte' => $item->fak_ad,
                    'bolum' => $item->bol_ad,
                    'sinif' => $item->sınıf,
                    'telefon' => $item->tel,
                    'email' => $item->eposta,
                    'kayit_tarihi' => $item->tarih ? date('d.m.Y', strtotime($item->tarih)) : '',
                    'rol' => $rolText
                ];
            });
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
            'Telefon',
            'E-posta',
            'Kayıt Tarihi',
            'Rol',
        ];
    }
}

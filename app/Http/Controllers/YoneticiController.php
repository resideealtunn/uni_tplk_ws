<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Etkinlik_bilgi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class YoneticiController extends Controller
{
    public function giris(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tc'        => 'required',
            'sifre'     => 'required',
        ], [
            'tc.required' => 'TC alanı zorunludur.',
            'sifre.required' => 'Şifre alanı zorunludur.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            // $response = $request->input('g-recaptcha-response');
            // $secretKey = "6LcFD6YpAAAAAA8rNdPgqJMQvPfTY7GqSnFS4voH";
            // $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $response;
            // $recaptchaResponse = \Illuminate\Support\Facades\Http::asForm()
            // ->withOptions(['verify' => false])
            // ->post($url);
            // $recaptcha = $recaptchaResponse->json();
            // if ($recaptcha["success"]) {
                $curl   = curl_init();
                $tc     = $request->input('tc');
                $sifre  = $request->input('sifre');
                $is_student     = false;
                $is_personel    = false;

                // ÖĞRENCİ KONTROL
                $jsonVerisi = [
                    "Tip" => "ogrenci",
                    "TcKn" => $tc,
                    "Sifre" => $sifre,
                ];

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://api.erbakan.edu.tr/LDap/getGirisDogrula',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_POSTFIELDS => json_encode($jsonVerisi),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Basic a2lzbWl6YW1hbmxpOlRRZ0FSajlTRUItdmg0MQ=='
                    ),
                ));

                $response       = curl_exec($curl);
                $student_data   = json_decode($response, true);
                
                curl_close($curl);

                if (isset($student_data) AND ($student_data['Durum'] == true)) {
                    $is_student = true;
                } else {
                    $is_student = false;
                }

                // PERSONEL KONTROL
                $jsonVerisi = [
                    "Tip" => "personel",
                    "TcKn" => $tc,
                    "Sifre" => $sifre,
                ];
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://api.erbakan.edu.tr/LDap/getGirisDogrula',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_POSTFIELDS => json_encode($jsonVerisi),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Basic a2lzbWl6YW1hbmxpOlRRZ0FSajlTRUItdmg0MQ=='
                    ),
                ));
                $response = curl_exec($curl);
                $personel_data = json_decode($response, true);

                if (isset($personel_data) AND ($personel_data['Durum'] == true)) {
                    $is_personel = true;
                } else {
                    $is_personel = false;
                }
                curl_close($curl);

                if ($is_student) {

                    $ogrenci = DB::table('ogrenci_bilgi')
                    ->where('tc', $tc,)
                    ->first();

                    if ($ogrenci) {
                        $uye = DB::table('uyeler')
                        ->where('ogr_id', $ogrenci->id)
                        ->whereIn('rol', [2, 3])
                        ->first();

                        if (!$uye) {
                            return back()->with('error', 'Bu kullanıcıya yönetici yetkisi tanımlanmamış');
                        }

                        $topluluk = DB::table('topluluklar')
                        ->where('id', $uye->top_id)
                        ->first();

                        if (!$topluluk) {
                            return back()->with('error', 'Topluluk bulunamadı.');
                        }

                        $rol = DB::table('rol')
                            ->where('id', $uye->rol)
                            ->first();

                        if (!$rol) {
                            return back()->with('error', 'Rol bilgisi bulunamadı.');
                        }

                        session([
                            'ogrenci_id' => $ogrenci->id,
                            'isim' => $ogrenci->isim,
                            't_id' => $uye->top_id,
                            'topluluk' => $topluluk->isim,
                            'rol' => $rol->rol
                        ]);
                        return redirect()->route('yonetici.panel');
                    } else {

                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'http://api.erbakan.edu.tr/KismiZamanli/getOgrenciOzlukBilgi?tcKimlikNo=' . $tc,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: Basic a2lzbWl6YW1hbmxpOlRRZ0FSajlTRUItdmg0MQ=='
                            ),
                        ));

                        $response = curl_exec($curl);

                        curl_close($curl);
                        $data = json_decode($response, true);

                        if ($data) {

                            DB::table('ogrenci_bilgi')->insert([
                                'isim' => $data[0]['AD'],
                                'soyisim' => $data[0]['SOYAD'],
                                'tc' => $request->tc,
                                'numara' => $data[0]['OGR_NO'],
                                'fak_ad' => $data[0]['FAK_AD'],
                                'bol_ad' => $data[0]['BOL_AD'],
                                'prog_ad' => $data[0]['PROG_AD'],
                                'sınıf' => $data[0]['SINIF'],
                                'kay_tar' => date("Y-m-d", strtotime(str_replace('.', '-', $data[0]['KAY_TAR']))),
                                'ogrenim_durum' => $data[0]['OGRENIM_DURUM'],
                                'ogrenim_tip' => $data[0]['OGRENIM_TIP'],
                                'ayr_tar' => $data[0]['AYR_TAR'],
                                'tel' => $data[0]['TELEFON'],
                                'tel2' => $data[0]['TELEFON2'],
                                'eposta' => $data[0]['EPOSTA1'],
                                'eposta2' => $data[0]['EPOSTA2'],
                                'program_tip' => $data[0]['PROGRAM_TIP'],
                                'durum' => $data[0]['DURUM'],
                            ]);

                            return redirect()->route('yonetici.giris');
                        }

                    }


                } else if ($is_personel) {

                    $personel = DB::table('personel')
                    ->where('tc', $tc)
                    ->first();

                    if ($personel) {
                        session([
                            'personel_id' => $personel->id,
                            'isim' => $personel->isim,
                            'unvan' => $personel->unvan,
                            'birim' => $personel->birim
                        ]);
                        return redirect()->route('denetim.topluluk');
                    } else {

                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'http://api.erbakan.edu.tr/KismiZamanli/getPersonelBilgi?tc=' . $request->tc,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Authorization: Basic a2lzbWl6YW1hbmxpOlRRZ0FSajlTRUItdmg0MQ=='
                            ),
                        ));
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $data = json_decode($response, true);
                        
                        if ($data) {
                            DB::table('personel')->insert([
                                'isim' => $data['personelAd'] . ' ' . $data['personelSoyad'],
                                'tc' => $request->tc,
                                'unvan' => $data['personelUnvan'],
                                'birim' => $data['personelBirim']
                            ]);
                            $personel = DB::table('personel')
                                ->where('tc', $tc)
                                ->first();
                            session([
                                'personel_id' => $personel->id,
                                'isim' => $personel->isim,
                                'unvan' => $personel->unvan,
                                'birim' => $personel->birim
                            ]);
                            return redirect()->route('denetim.topluluk');
                        }

                    }

                } else {
                    return back()->with('error', 'Öğrenci veya personel bulunamadı.');
                }
            // } else {
            //     return back()->with('error', 'Güvenlik doğrulaması başarısız oldu. Lütfen tekrar deneyiniz.');
            // }
        }
    }

    public function yoneticiPanel()
    {
        $topluluk_id = session('topluluk');
        $veri = DB::table('topluluklar')
            ->where('isim', $topluluk_id)
            ->first();

        return view('yonetici_panel', compact('veri'));

    }
    public function etkinlikEkle(Request $request)
    {
        // Session kontrolü
        if (!session('t_id')) {
            return back()->with('danger', 'Oturum bilgisi bulunamadı. Lütfen tekrar giriş yapın.');
        }

        // Form validasyonu ve dosya işlemleri...
        if (!$request->hasFile('afis')) { return back()->with('danger', 'Afiş dosyası yüklenmedi.'); }
        $afis = $request->file('afis');
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($afis->getMimeType(), $allowedMimeTypes)) { return back()->with('danger', 'Yalnızca resim formatında afiş yükleyebilirsiniz.');}
        $baslangicTarih = date('Y-m-d H:i:s', strtotime($request->input('tarih')));
        $bitisTarih = date('Y-m-d H:i:s', strtotime($request->input('bitis_tarihi')));
        $safeBaslik = Str::slug($request->input('baslik'));
        $afisAdi = date('Y-m-d', strtotime($request->input('tarih'))) . '_' . $safeBaslik . '.' . $afis->getClientOriginalExtension();
        
        DB::beginTransaction();
        try {
            // Dosyayı taşı
            $afis->move(public_path('images/etkinlik'), $afisAdi);

            // Veriyi bir array'de topla
            $dataToInsert = [
                'isim' => $request->input('baslik'),
                'bilgi' => $request->input('kisa_bilgi'),
                'metin' => $request->input('aciklama'),
                'konum' => $request->input('konum'),
                'gorsel' => $afisAdi,
                'tarih' => $baslangicTarih,
                'bitis_tarihi' => $bitisTarih,
                't_id' => session('t_id'),
                'b_durum' => 0, 'y_durum' => 0, 'p_durum' => 0,
                'talep_onay' => 2, 'talep_red' => null
            ];

            // Veritabanına ekle
            DB::table('etkinlik_bilgi')->insert($dataToInsert);

            // Her şey yolundaysa, işlemi onayla
            DB::commit();

            return back()->with('success', 'Etkinlik talebi başarıyla gönderildi!');

        } catch (\Exception $e) {
            // Hata olursa işlemi geri al
            DB::rollBack();

            // Oluşturulmuş olabilecek dosyayı sil
            if (file_exists(public_path('images/etkinlik/' . $afisAdi))) {
                unlink(public_path('images/etkinlik/' . $afisAdi));
            }

            \Log::error('Etkinlik ekleme hatası: ' . $e->getMessage());
            return back()->with('danger', 'Etkinlik kaydedilirken bir hata oluştu: ' . $e->getMessage());
        }
    }
    public function etkinlikIslemleri()
    {
        $toplulukId = session('t_id');

        // Sadece onaylanmış etkinlikleri getir (talep_onay = 1)
        $onaylanmisEtkinlikler = DB::table('etkinlik_bilgi')
            ->where('t_id', $toplulukId)
            ->where('talep_onay', 1)
            ->select('*') // Tüm alanları getir, k_durum dahil
            ->get();

        return view('etkinlik_islemleri', compact('onaylanmisEtkinlikler'));
    }
    public function basvuruGuncelle(Request $request)
    {
        $etkinlik = Etkinlik_bilgi::findOrFail($request->etkinlik_id);

        // Başvuru durumunu tersine çevir: 1 → 0, 0 → 1
        $etkinlik->b_durum = $etkinlik->b_durum == 1 ? 0 : 1;
        
        // Eğer başvuru açılıyorsa (b_durum = 1), k_durum'u da 1 yap
        if ($etkinlik->b_durum == 1) {
            $etkinlik->k_durum = 1;
        }
        
        $etkinlik->save();

        return back()->with('success', 'Başvuru durumu güncellendi.');
    }

    public function yoklamaIslemleri()
    {
        $toplulukId = session('t_id');

        // Sadece onaylanmış etkinlikleri getir (talep_onay = 1)
        $onaylanmisEtkinlikler = DB::table('etkinlik_bilgi')
            ->where('t_id', $toplulukId)
            ->where('talep_onay', 1)
            ->select('*') // Tüm alanları getir, k_durum dahil
            ->get();

        return view('etkinlik_islemleri', compact('onaylanmisEtkinlikler'));
    }

    public function yoklamaGuncelle(Request $request)
    {
        $toplulukId = session('t_id');
        $etkinlikId = $request->etkinlik_id;

        $etkinlik = etkinlik_bilgi::where('id', $etkinlikId)
            ->where('t_id', $toplulukId)
            ->first();

        if (!$etkinlik) {
            return back()->with('error', 'Etkinlik bulunamadı.');
        }

        // Mevcut durumu tersine çevir
        $etkinlik->y_durum = $etkinlik->y_durum ? 0 : 1;
        $etkinlik->save();

        return back()->with('success', 'Yoklama durumu güncellendi.');
    }

    public function kesfetGuncelle(Request $request)
    {
        $toplulukId = session('t_id');
        $etkinlikId = $request->etkinlik_id;

        \Log::info('Kesfet güncelleme isteği:', [
            'topluluk_id' => $toplulukId,
            'etkinlik_id' => $etkinlikId,
            'request_data' => $request->all()
        ]);

        $etkinlik = Etkinlik_bilgi::where('id', $etkinlikId)
            ->where('t_id', $toplulukId)
            ->first();

        if (!$etkinlik) {
            \Log::error('Etkinlik bulunamadı:', ['etkinlik_id' => $etkinlikId, 'topluluk_id' => $toplulukId]);
            return back()->with('error', 'Etkinlik bulunamadı.');
        }

        \Log::info('Etkinlik bulundu:', [
            'etkinlik_id' => $etkinlik->id,
            'mevcut_k_durum' => $etkinlik->k_durum
        ]);

        // k_durum değerini toggle yap (1 ise 0, 0 ise 1)
        $yeniDurum = $etkinlik->k_durum == 1 ? 0 : 1;
        $etkinlik->k_durum = $yeniDurum;
        
        $saved = $etkinlik->save();

        \Log::info('Güncelleme sonucu:', [
            'saved' => $saved,
            'yeni_k_durum' => $yeniDurum,
            'guncellenen_etkinlik' => $etkinlik->toArray()
        ]);

        if ($saved) {
            $mesaj = $yeniDurum == 1 ? 'Etkinlik keşfette gösterildi.' : 'Etkinlik keşfette gizlendi.';
            return back()->with('success', $mesaj);
        } else {
            return back()->with('error', 'Güncelleme başarısız oldu.');
        }
    }

    public function paylasilabilirEtkinlikler()
    {
        $toplulukId = session('t_id');

        // Onaylanmış etkinlikleri getir (talep_onay = 1) ancak etkinlik_gecmis tablosunda e_onay = 1 veya e_onay = 2 olanları hariç tut
        $onaylanmisEtkinlikler = DB::table('etkinlik_bilgi as eb')
            ->leftJoin('etkinlik_gecmis as eg', 'eb.id', '=', 'eg.e_id')
            ->where('eb.t_id', $toplulukId)
            ->where('eb.talep_onay', 1)
            ->where(function($query) {
                $query->whereNull('eg.e_onay')
                      ->orWhereNotIn('eg.e_onay', [1, 2]);
            })
            ->select('eb.*') // Sadece etkinlik_bilgi tablosundaki alanları getir
            ->get();

        return view('etkinlik_islemleri', compact('onaylanmisEtkinlikler'));
    }

    public function etkinlikPaylas(Request $request)
    {
        \Log::info('Paylaşım isteği:', $request->all());

        $etkinlik = Etkinlik_bilgi::find($request->paylasEtkinlikSec);

        if (!$etkinlik) {
            \Log::error('Etkinlik bulunamadı. ID:', ['id' => $request->paylasEtkinlikSec]);
            return back()->with('danger', 'Etkinlik bulunamadı.');
        }

        \Log::info('Bulunan etkinlik:', ['etkinlik' => $etkinlik->toArray()]);

        try {
            $afisAdi = $etkinlik->gorsel;
            if ($request->hasFile('paylasResim')) {
                $afis = $request->file('paylasResim');
                // Sadece resim dosyalarına izin ver
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($afis->getMimeType(), $allowedMimeTypes)) {
                    return back()->with('danger', 'Yalnızca resim formatında afiş yükleyebilirsiniz.');
                }
                $afisAdi = time() . '_' . $afis->getClientOriginalName();
                $afis->move(public_path('images/etkinlik'), $afisAdi);
            }

            // Sadece etkinlik_gecmis tablosuna ekle
            DB::table('etkinlik_gecmis')->insert([
                'e_id' => $etkinlik->id,
                'bilgi' => $request->paylasKisaBilgi,
                'aciklama' => $request->paylasAciklama,
                'resim' => $afisAdi,
                'e_onay' => 2,
                'red_sebebi' => null
            ]);

            return back()->with('success', 'Etkinlik paylaşım talebi başarıyla gönderildi!');
        } catch (\Exception $e) {
            \Log::error('Paylaşım hatası:', ['error' => $e->getMessage()]);
            return back()->with('danger', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }
    public function basvurular(Request $request)
    {
        $toplulukId = session('t_id');

        // Sadece onaylanmış etkinlikleri getir (talep_onay = 1)
        $onaylanmisEtkinlikler = DB::table('etkinlik_bilgi')
            ->where('t_id', $toplulukId)
            ->where('talep_onay', 1)
            ->select('*') // Tüm alanları getir, k_durum dahil
            ->get();

        if ($request->has('etkinlik_id')) {
            $etkinlikId = $request->input('etkinlik_id');
            
            // Etkinlik başvurularını getir
            $basvurular = DB::table('etkinlik_basvuru')
                ->join('uyeler', 'etkinlik_basvuru.u_id', '=', 'uyeler.id')
                ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
                ->where('etkinlik_basvuru.e_id', $etkinlikId)
                ->select(
                    'ogrenci_bilgi.isim',
                    'ogrenci_bilgi.soyisim',
                    'ogrenci_bilgi.numara',
                    'ogrenci_bilgi.bol_ad as bolum',
                    'ogrenci_bilgi.tel'
                )
                ->get();

            return response()->json($basvurular);
        }

        return view('etkinlik_islemleri', compact('onaylanmisEtkinlikler'));
    }
    public function getir(Request $request)
    {
        $etkinlikId = $request->input('etkinlik_id');
        $basvurular = DB::table('etkinlik_basvuru')
            ->join('uyeler', 'etkinlik_basvuru.u_id', '=', 'uyeler.id')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('etkinlik_basvuru.e_id', $etkinlikId)
            ->select(
                'ogrenci_bilgi.isim',
                'ogrenci_bilgi.soyisim',
                'ogrenci_bilgi.numara',
                'ogrenci_bilgi.bol_ad as bolum',
                'ogrenci_bilgi.tel',
                'uyeler.id as uye_id',
                'uyeler.top_id as topluluk_id'
            )
            ->get();

        // Her başvuru için toplam katılımı hesapla
        $basvurular = $basvurular->map(function($item) {
            $toplam_katilim = DB::table('etkinlik_yoklama')
                ->where('u_id', $item->uye_id)
                ->where('yoklama', 1)
                ->count();
            $item->toplam_katilim = $toplam_katilim;
            return $item;
        });

        return response()->json($basvurular);
    }

    public function guncelle(Request $request)
    {
        $topluluk_id = session('topluluk_id') ?? session('t_id');
        if (!$topluluk_id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Topluluk ID bulunamadı. Lütfen tekrar giriş yapın.']);
            }
            return back()->with('danger', 'Topluluk ID bulunamadı. Lütfen tekrar giriş yapın.');
        }
        $topluluk = DB::table('topluluklar')->where('id', $topluluk_id)->first();
        $success = false;
        // === LOGO GÜNCELLEME ===
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $logo_file = $request->file('logo');
            $logo_name = time() . '_' . $logo_file->getClientOriginalName();
            $logo_file->move(public_path('images/logo'), $logo_name);
            $current = $topluluk ? $topluluk->gorsel : null;
            if ($current == $logo_name) {
                $success = true;
            } else {
                $updated = DB::table('topluluklar')->where('id', $topluluk_id)->update(['gorsel' => $logo_name]);
                DB::table('logo_onay')->updateOrInsert(
                    ['t_id' => $topluluk_id],
                    ['onay' => 2, 'red' => null]
                );
                $success = $updated ? true : false;
            }
        }
        // === ARKA PLAN GÜNCELLEME ===
        if ($request->hasFile('bg') && $request->file('bg')->isValid()) {
            $bg_file = $request->file('bg');
            $bg_name = time() . '_' . $bg_file->getClientOriginalName();
            $bg_file->move(public_path('images/background'), $bg_name);
            $current = $topluluk ? $topluluk->bg : null;
            if ($current == $bg_name) {
                $success = true;
            } else {
                $updated = DB::table('topluluklar')->where('id', $topluluk_id)->update(['bg' => $bg_name]);
                DB::table('bg_onay')->updateOrInsert(
                    ['t_id' => $topluluk_id],
                    ['onay' => 2, 'red' => null]
                );
                $success = $updated ? true : false;
            }
        }
        // === SLOGAN GÜNCELLEME ===
        if ($request->filled('slogan')) {
            $slogan = $request->input('slogan');
            $current = $topluluk ? $topluluk->slogan : null;
            if ($current == $slogan) {
                $success = true;
            } else {
                $updated = DB::table('topluluklar')->where('id', $topluluk_id)->update(['slogan' => $slogan]);
                DB::table('slogan_onay')->updateOrInsert(
                    ['t_id' => $topluluk_id],
                    ['onay' => 2, 'red' => null]
                );
                $success = $updated ? true : false;
            }
        }
        // === VİZYON ===
        if ($request->filled('vizyon')) {
            $vizyon = $request->input('vizyon');
            $current = $topluluk ? $topluluk->vizyon : null;
            if ($current == $vizyon) {
                $success = true;
            } else {
                $updated = DB::table('topluluklar')->where('id', $topluluk_id)->update(['vizyon' => $vizyon]);
                DB::table('vizyon_onay')->updateOrInsert(
                    ['t_id' => $topluluk_id],
                    ['onay' => 2, 'red' => null]
                );
                $success = $updated ? true : false;
            }
        }
        // === MİSYON ===
        if ($request->filled('misyon')) {
            $misyon = $request->input('misyon');
            $current = $topluluk ? $topluluk->misyon : null;
            if ($current == $misyon) {
                $success = true;
            } else {
                $updated = DB::table('topluluklar')->where('id', $topluluk_id)->update(['misyon' => $misyon]);
                DB::table('misyon_onay')->updateOrInsert(
                    ['t_id' => $topluluk_id],
                    ['onay' => 2, 'red' => null]
                );
                $success = $updated ? true : false;
            }
        }
        // === TÜZÜK ===
        if ($request->hasFile('tuzuk') && $request->file('tuzuk')->isValid()) {
            $tuzuk_file = $request->file('tuzuk');
            $tuzuk_name = time() . '_tuzuk_' . Str::random(5) . '.' . $tuzuk_file->getClientOriginalExtension();
            $tuzuk_file->move(public_path('files/tuzuk'), $tuzuk_name);
            $current = $topluluk ? $topluluk->tuzuk : null;
            if ($current == $tuzuk_name) {
                $success = true;
            } else {
                $updated = DB::table('topluluklar')->where('id', $topluluk_id)->update(['tuzuk' => $tuzuk_name]);
                DB::table('tuzuk_onay')->updateOrInsert(
                    ['t_id' => $topluluk_id],
                    ['onay' => 2, 'red' => null]
                );
                $success = $updated ? true : false;
            }
        }
        // === SOSYAL MEDYA ===
        if ($request->filled('instagram')) {
            $instagram = $request->input('instagram');
            DB::table('sosyal_medya')->updateOrInsert(
                ['t_id' => $topluluk_id],
                ['instagram' => $instagram, 'i_onay' => 2]
            );
            $success = true;
        }
        if ($request->filled('whatsapp')) {
            $whatsapp = $request->input('whatsapp');
            DB::table('sosyal_medya')->updateOrInsert(
                ['t_id' => $topluluk_id],
                ['whatsapp' => $whatsapp, 'w_onay' => 2]
            );
            $success = true;
        }
        // === LINKEDLN ===
        $linkedln = null;
        if ($request->filled('linkedln')) {
            $linkedln = $request->input('linkedln');
        } elseif ($request->filled('linkedin')) {
            $linkedln = $request->input('linkedin');
        }
        if ($linkedln !== null) {
            DB::table('sosyal_medya')->updateOrInsert(
                ['t_id' => $topluluk_id],
                ['linkedln' => $linkedln, 'l_onay' => 2]
            );
            $success = true;
        }
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success]);
        }
        return redirect()->back()->with('success', 'Bilgiler güncellendi.');
    }
    public function cikis(Request $request)
    {
        // Oturumu sonlandır
        Auth::logout();

        // Tüm session verilerini temizle
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Anasayfaya yönlendir
        return redirect('/')->with('success', 'Başarıyla çıkış yapıldı.');
    }
    public function getUyeListesi(Request $request)
    {
        $topluluk_id = session('topluluk_id') ?? session('t_id');
        $uyeler = DB::table('uyeler')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('uyeler.top_id', $topluluk_id)
            ->whereIn('uyeler.rol', [2,3,6,1])
            ->select(
                'uyeler.id as uye_id',
                'ogrenci_bilgi.id as ogr_id',
                'uyeler.tarih as kay_tar',
                'ogrenci_bilgi.numara',
                'ogrenci_bilgi.isim',
                'ogrenci_bilgi.soyisim',
                'ogrenci_bilgi.tel',
                'ogrenci_bilgi.fak_ad',
                'ogrenci_bilgi.bol_ad',
                'ogrenci_bilgi.eposta',
                'uyeler.belge',
                'uyeler.rol'
            )
            ->orderByRaw('FIELD(uyeler.rol, 2, 3, 6, 1)')
            ->orderByDesc('uyeler.tarih')
            ->get();
        return response()->json($uyeler);
    }
    public function getBasvuruListesi(Request $request)
    {
        $topluluk_id = session('topluluk_id') ?? session('t_id');
        $uyeler = DB::table('uyeler')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('uyeler.top_id', $topluluk_id)
            ->where('uyeler.rol', 4)
            ->select(
                'uyeler.id as uye_id',
                'uyeler.tarih as kay_tar',
                'ogrenci_bilgi.numara',
                'ogrenci_bilgi.isim',
                'ogrenci_bilgi.soyisim',
                'ogrenci_bilgi.tel',
                'ogrenci_bilgi.eposta',
                'ogrenci_bilgi.fak_ad',
                'ogrenci_bilgi.bol_ad',
                'uyeler.belge'
            )
            ->get();
        return response()->json(['topluluk_id' => $topluluk_id, 'uyeler' => $uyeler]);
    }
    public function updateUye(Request $request)
    {
        $ogr_id = $request->input('ogr_id');
        $uye_id = $request->input('uye_id');
        $belge = $request->file('belge');
        $tel = $request->input('tel');
        $eposta = $request->input('eposta');

        // ogrenci_bilgi güncelle
        DB::table('ogrenci_bilgi')->where('id', $ogr_id)->update([
            'tel' => $tel,
            'eposta' => $eposta
        ]);

        // uyeler tablosunda belge güncelle
        if ($belge && $belge->isValid()) {
            $fileName = time() . '_' . $belge->getClientOriginalName();
            $belge->move(public_path('docs/kayit_belge'), $fileName);
            DB::table('uyeler')->where('id', $uye_id)->update([
                'belge' => $fileName
            ]);
        }

        return response()->json(['success' => true]);
    }
    public function uyeSil(Request $request)
    {
        $uye_id = $request->input('uye_id');
        $sebep = $request->input('ayrilis_sebebi');
        $updated = DB::table('uyeler')->where('id', $uye_id)->update([
            'rol' => 5,
            'silinme_sebebi' => $sebep
        ]);
        if ($updated) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    public function silinenUyeler(Request $request)
    {
        $topluluk_id = session('topluluk_id') ?? session('t_id');
        $uyeler = DB::table('uyeler')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('uyeler.top_id', $topluluk_id)
            ->whereIn('uyeler.rol', [5,7])
            ->select(
                'uyeler.tarih as kay_tar',
                'ogrenci_bilgi.numara',
                'ogrenci_bilgi.isim',
                'ogrenci_bilgi.soyisim',
                'ogrenci_bilgi.tel',
                'ogrenci_bilgi.eposta',
                'ogrenci_bilgi.fak_ad',
                'ogrenci_bilgi.bol_ad',
                'uyeler.belge',
                'uyeler.silinme_sebebi',
                'uyeler.red_sebebi',
                'uyeler.rol'
            )
            ->orderByRaw('FIELD(uyeler.rol, 5, 7)')
            ->orderByDesc('uyeler.tarih')
            ->get();
        return response()->json($uyeler);
    }
    public function yonetimBasvurulari(Request $request)
    {
        $topluluk_id = session('topluluk_id') ?? session('t_id');
        $uyeler = DB::table('uyeler')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('uyeler.top_id', $topluluk_id)
            ->where('uyeler.rol', 6)
            ->select(
                'uyeler.tarih as kay_tar',
                'ogrenci_bilgi.numara',
                'ogrenci_bilgi.isim',
                'ogrenci_bilgi.soyisim',
                'ogrenci_bilgi.tel',
                'ogrenci_bilgi.eposta',
                'ogrenci_bilgi.fak_ad',
                'ogrenci_bilgi.bol_ad',
                'ogrenci_bilgi.sınıf',
                'uyeler.belge',
                'uyeler.niyet_metni'
            )
            ->get();
        return response()->json($uyeler);
    }
    public function panelGeriBildirimTalepEtkinlik(Request $request)
    {
        $topluluk_id = session('topluluk_id') ?? session('t_id');
        $etkinlikler = DB::table('etkinlik_bilgi')
            ->where('t_id', $topluluk_id)
            ->whereIn('talep_onay', [0, 1, 2])
            ->orderBy('tarih', 'asc')
            ->select('id', 'isim', 'tarih', 'bitis_tarihi', 'gorsel', 'bilgi', 'metin', 'talep_onay', 'talep_red')
            ->get();
        return response()->json($etkinlikler);
    }
    public function panelGeriBildirimTalepEtkinlikGuncelle(Request $request)
    {
        $id = $request->input('id');
        $update = [
            'isim' => $request->input('isim'),
            'bilgi' => $request->input('bilgi'),
            'metin' => $request->input('metin'),
            'tarih' => $request->input('tarih'),
            'talep_onay' => 2,
        ];
        if ($request->hasFile('gorsel') && $request->file('gorsel')->isValid()) {
            $file = $request->file('gorsel');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/etkinlik'), $fileName);
            $update['gorsel'] = $fileName;
        }
        $updated = DB::table('etkinlik_bilgi')->where('id', $id)->update($update);
        return response()->json(['success' => $updated ? true : false]);
    }
    public function panelGeriBildirimGerceklesenEtkinlik(Request $request)
    {
        $topluluk_id = session('topluluk_id') ?? session('t_id');
        $etkinlikler = DB::table('etkinlik_gecmis as eg')
            ->join('etkinlik_bilgi as eb', 'eg.e_id', '=', 'eb.id')
            ->where('eb.t_id', $topluluk_id)
            ->select(
                'eg.id',
                'eg.e_id',
                'eb.isim as baslik',
                'eb.tarih as tarih',
                'eb.bitis_tarihi as bitis_tarihi',
                'eg.resim',
                'eg.bilgi',
                'eg.aciklama',
                'eg.e_onay',
                'eg.red_sebebi'
            )
            ->orderBy('eb.tarih', 'desc')
            ->get();
        return response()->json($etkinlikler);
    }

    public function panelGeriBildirimGerceklesenEtkinlikGuncelle(Request $request)
    {
        try {
            $id = $request->input('id');
            $baslik = $request->input('baslik');
            $tarih = $request->input('tarih');
            $aciklama = $request->input('aciklama');
            
            $update = [
                'aciklama' => $aciklama,
                'e_onay' => 2, // Beklemede durumuna çevir
                'red_sebebi' => null
            ];
            
            // Resim dosyası varsa işle
            if ($request->hasFile('resim') && $request->file('resim')->isValid()) {
                $resim = $request->file('resim');
                $resimAdi = time() . '_' . $resim->getClientOriginalName();
                $resim->move(public_path('images/etkinlik'), $resimAdi);
                $update['resim'] = $resimAdi;
            }
            
            // etkinlik_gecmis tablosunu güncelle
            $updated = DB::table('etkinlik_gecmis')->where('id', $id)->update($update);
            
            // etkinlik_bilgi tablosundaki başlığı da güncelle
            $etkinlikGecmis = DB::table('etkinlik_gecmis')->where('id', $id)->first();
            if ($etkinlikGecmis) {
                DB::table('etkinlik_bilgi')->where('id', $etkinlikGecmis->e_id)->update([
                    'isim' => $baslik,
                    'tarih' => $tarih
                ]);
            }
            
            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Etkinlik başarıyla güncellendi ve onay için gönderildi.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Güncelleme yapılamadı.']);
            }
        } catch (\Exception $e) {
            \Log::error('Gerçekleşen etkinlik güncelleme hatası: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
        }
    }
    public function silinenUyelerGeriBildirim(Request $request)
    {
        $topluluk_id = session('topluluk_id') ?? session('t_id');
        $uyeler = DB::table('uyeler')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('uyeler.top_id', $topluluk_id)
            ->where('uyeler.rol', 5)
            ->select(
                'ogrenci_bilgi.numara as ogr_no',
                'ogrenci_bilgi.isim as ad',
                'ogrenci_bilgi.soyisim as soyad',
                'ogrenci_bilgi.tel as cep_tel',
                'ogrenci_bilgi.fak_ad as fakulte',
                'ogrenci_bilgi.bol_ad as bolum',
                'uyeler.belge as uyelik_formu',
                'uyeler.silinme_sebebi as silinme_sebebi'
            )
            ->get();
        return response()->json($uyeler);
    }
    public function webArayuzBilgileri(Request $request)
    {
        $topluluk_id = session('topluluk_id') ?? session('t_id');
        $topluluk = DB::table('topluluklar')->where('id', $topluluk_id)->first();
        $result = [];
        if ($topluluk) {
            $onaylar = [
                'logo' => DB::table('logo_onay')->where('t_id', $topluluk_id)->first(),
                'bg' => DB::table('bg_onay')->where('t_id', $topluluk_id)->first(),
                'slogan' => DB::table('slogan_onay')->where('t_id', $topluluk_id)->first(),
                'vizyon' => DB::table('vizyon_onay')->where('t_id', $topluluk_id)->first(),
                'misyon' => DB::table('misyon_onay')->where('t_id', $topluluk_id)->first(),
                'tuzuk' => DB::table('tuzuk_onay')->where('t_id', $topluluk_id)->first(),
            ];
            $result = [
                [
                    'ozellik' => 'logo',
                    'deger' => $topluluk->gorsel,
                    'onay' => $onaylar['logo']->onay ?? 4,
                    'red' => $onaylar['logo']->red ?? null
                ],
                [
                    'ozellik' => 'arkaplan',
                    'deger' => $topluluk->bg,
                    'onay' => $onaylar['bg']->onay ?? 4,
                    'red' => $onaylar['bg']->red ?? null
                ],
                [
                    'ozellik' => 'slogan',
                    'deger' => $topluluk->slogan,
                    'onay' => $onaylar['slogan']->onay ?? 4,
                    'red' => $onaylar['slogan']->red ?? null
                ],
                [
                    'ozellik' => 'vizyon',
                    'deger' => $topluluk->vizyon,
                    'onay' => $onaylar['vizyon']->onay ?? 4,
                    'red' => $onaylar['vizyon']->red ?? null
                ],
                [
                    'ozellik' => 'misyon',
                    'deger' => $topluluk->misyon,
                    'onay' => $onaylar['misyon']->onay ?? 4,
                    'red' => $onaylar['misyon']->red ?? null
                ],
                [
                    'ozellik' => 'tuzuk',
                    'deger' => $topluluk->tuzuk,
                    'onay' => $onaylar['tuzuk']->onay ?? 4,
                    'red' => $onaylar['tuzuk']->red ?? null
                ],
            ];
        }
        return response()->json($result);
    }
    public function sosyalMedyaGeriBildirim()
    {
        $topluluk_id = session('topluluk_id') ?? session('t_id');
        $row = \DB::table('sosyal_medya')->where('t_id', $topluluk_id)->first();
        $result = [];
        if ($row) {
            $result[] = [
                'medya' => 'instagram',
                'link' => $row->instagram,
                'onay' => $row->i_onay,
                'red' => $row->i_onay == 0 ? $row->i_red : ''
            ];
            $result[] = [
                'medya' => 'whatsapp',
                'link' => $row->whatsapp,
                'onay' => $row->w_onay,
                'red' => $row->w_onay == 0 ? $row->w_red : ''
            ];
            $result[] = [
                'medya' => 'linkedln',
                'link' => $row->linkedln,
                'onay' => $row->l_onay,
                'red' => $row->l_onay == 0 ? $row->l_red : ''
            ];
        }
        return response()->json($result);
    }
    // AJAX: Etkinlik yoklama üyelerini getir
    public function yoklamaUyeler(Request $request)
    {
        $e_id = $request->input('e_id');
        // 1. O etkinliğe ait tüm yoklama kayıtlarını çek
        $uyeler = DB::table('etkinlik_yoklama')
            ->join('uyeler', 'etkinlik_yoklama.u_id', '=', 'uyeler.id')
            ->join('ogrenci_bilgi', 'uyeler.ogr_id', '=', 'ogrenci_bilgi.id')
            ->where('etkinlik_yoklama.e_id', $e_id)
            ->select(
                'etkinlik_yoklama.id as yoklama_id',
                'ogrenci_bilgi.isim',
                'ogrenci_bilgi.soyisim',
                'ogrenci_bilgi.numara',
                'ogrenci_bilgi.bol_ad as bolum',
                'ogrenci_bilgi.tel',
                'etkinlik_yoklama.yoklama',
                'uyeler.id as uye_id',
                'etkinlik_yoklama.e_id as e_id'
            )
            ->get();
        return response()->json($uyeler);
    }

    // AJAX: Yoklama durumunu güncelle
    public function yoklamaDurumGuncelle(Request $request)
    {
        $yoklama_id = $request->input('yoklama_id');
        $yoklama = $request->input('yoklama');
        $uye_id = $request->input('uye_id');
        $e_id = $request->input('e_id');
        $success = false;
        $message = '';
        if ($yoklama_id) {
            $updated = DB::table('etkinlik_yoklama')->where('id', $yoklama_id)->update(['yoklama' => $yoklama]);
            $success = $updated ? true : false;
        } else if ($uye_id && $e_id) {
            $inserted = DB::table('etkinlik_yoklama')->insert([
                'u_id' => $uye_id,
                'e_id' => $e_id,
                'yoklama' => $yoklama,
                'tarih' => now(),
            ]);
            $success = $inserted ? true : false;
        }
        if ($success) {
            $message = $yoklama == 1 ? 'Yoklama durumu katıldı olarak değiştirildi.' : 'Yoklama durumu katılmadı olarak değiştirildi.';
        } else {
            $message = 'Güncelleme başarısız!';
        }
        return response()->json(['success' => $success, 'message' => $message]);
    }
}

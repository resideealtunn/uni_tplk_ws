<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üye İşlemleri</title>
    <link rel="stylesheet" href="{{ asset('css/style_panels.css') }}">
    <link rel="stylesheet" href="{{ asset('css/panel_uye_islemleri.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="page-wrapper">
    <div class="sidebar">
        <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
        <h2>{{ session('topluluk') }}</h2>
        <h3>{{ session('isim') }}</h3>
        <p>{{ session('rol') }}</p>
        <div class="menu">
            <a href="/yonetici_panel" class="menu-item">Web Arayüz İşlemleri</a>
            <a href="/etkinlik_islemleri" class="menu-item">Etkinlik İşlemleri</a>
            <a href="/uye_islemleri" class="menu-item active">Üye İşlemleri</a>
            <a href="/panel_geribildirim" class="menu-item ">Denetim Geri Bildirimleri</a>
            <a href="javascript:void(0);" class="menu-item" onclick="document.getElementById('cikisForm').submit();">Çıkış</a>
            <form id="cikisForm" action="{{ route('cikis') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    <div class="main-content">
        <div class="content active">
            <div class="action-container">
                <h1>Üye İşlemleri</h1>
                <div class="action-card" onclick="showUyeListeModal()">
                    <h2>Üyeleri Listele</h2>
                    <p>Topluluk üyelerini görüntülemek için tıklayın</p>
                </div>
                <div class="action-card" onclick="showBasvuruListeModal()">
                    <h2>Üyelik Başvurularını Görüntüle</h2>
                    <p>Üyelik başvurularını yönetmek için tıklayın</p>
                </div>
                <div class="action-card" onclick="showYonetimBasvurulariModal()">
                    <h2>Yönetim Başvurularını Görüntüle</h2>
                    <p>Yönetim başvurularını görüntülemek için tıklayın</p>
                </div>
                <div class="action-card" onclick="showUyeGuncelleModal()">
                    <h2>Üye Bilgilerini Güncelle</h2>
                    <p>Üye bilgilerini düzenlemek için tıklayın</p>
                </div>
                <div class="action-card" onclick="showYeniUyeModal()">
                    <h2>Yeni Üye Ekle</h2>
                    <p>Yeni üye eklemek için tıklayın</p>
                </div>
                <div class="action-card" onclick="showUyeSilModal()">
                    <h2>Üye Sil</h2>
                    <p>Üye silmek için tıklayın</p>
                </div>
                <div class="action-card" onclick="showSilinenUyelerModal()">
                    <h2>Silinen Üyeleri ve Başvuruları Görüntüle</h2>
                    <p>Silinen üyeleri görüntülemek için tıklayın</p>
                </div>
                <div class="action-card" onclick="showUyeMesajModal()">
                    <h2>Üye Mesajlarını Görüntüle</h2>
                    <p>Üyelerin gönderdiği mesajları görüntülemek için tıklayın</p>
                </div>
            </div>
        </div>
        <!-- Üyeleri Listele Modal -->
        <div id="uyeListeModal" class="modal">
            <div class="modal-content">
                <h2>Üye Listesi</h2>
                <input type="text" id="uyeSearchInput" class="form-control" placeholder="Öğrenci No ile ara..." onkeyup="searchUyeListesi()" style="margin-bottom: 15px; max-width: 300px;">
                <div class="uye-listesi">
                    <table>
                        <thead>
                            <tr>
                                <th>Başvuru Tarihi</th>
                                <th>Öğrenci No</th>
                                <th>Ad Soyad</th>
                                <th>Telefon Numarası</th>
                                <th>Eposta</th>
                                <th>Fakülte</th>
                                <th>Bölüm</th>
                                <th>Üyelik Formu</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody id="uyeListesi">

                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('uyeListeModal')">Kapat</button>
            </div>
        </div>
        <!-- Diğer modaller buraya eklenecek -->
         <!-- Üyelik Başvuruları Modal -->
        <div id="basvuruListeModal" class="modal">
            <div class="modal-content">
                <h2>Üyelik Başvuruları</h2>
                <input type="text" id="basvuruSearchInput" class="form-control" placeholder="Öğrenci No ile ara..." onkeyup="searchBasvuruListesi()" style="margin-bottom: 15px; max-width: 300px;">
                <div class="uye-listesi">
                    <table id="basvuruListesiTable">
                        <thead>
                            <tr>
                                <th>Kayıt Şekli</th>
                                <th>Başvuru Tarihi</th>
                                <th>Öğrenci No</th>
                                <th>Ad Soyad</th>
                                <th>Cep Tel</th>
                                <th>Fakülte</th>
                                <th>Bölüm</th>
                                <th>Üyelik Formu</th>
                                <th style="width:110px;">İşlem</th>
                            </tr>
                        </thead>
                        <tbody id="basvuruListesi">
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('basvuruListeModal')">Kapat</button>
            </div>
        </div>
        <!-- Üye Güncelle Modal -->
        <div id="uyeGuncelleModal" class="modal">
            <div class="modal-content">
                <h2>Üye Bilgilerini Güncelle</h2>
                <input type="text" id="guncelleSearchInput" class="form-control" placeholder="Öğrenci No ile ara..." onkeyup="searchUyeGuncelle()" style="margin-bottom: 15px; max-width: 300px;">
                <div class="uye-listesi">
                    <table>
                        <thead>
                            <tr>
                                <th>Başvuru Tarihi</th>
                                <th>Öğrenci No</th>
                                <th>Ad Soyad</th>
                                <th>Telefon Numarası</th>
                                <th>Eposta</th>
                                <th>Fakülte</th>
                                <th>Bölüm</th>
                                <th>Üyelik Formu</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody id="uyeGuncelleListesi">
                            <!-- JavaScript ile doldurulacak -->
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('uyeGuncelleModal')">Kapat</button>
            </div>
        </div>

        <!-- Üye Bilgisi Düzenle Modalı -->
        <div id="duzenleUyeModal" class="modal">
            <div class="modal-content" style="max-width:400px;">
                <h2>Üye Bilgilerini Düzenle</h2>
                <form id="duzenleUyeForm">
                    <input type="hidden" id="duzenleUyeOgrNo">
                    <input type="hidden" id="duzenleUyeUyeId">
                    <div class="form-group">
                        <label for="duzenleUyeCepTel">Cep Tel:</label>
                        <input type="text" id="duzenleUyeCepTel" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="duzenleUyeEmail">Email:</label>
                        <input type="email" id="duzenleUyeEmail" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="duzenleUyeFormu">Üyelik Formu:</label>
                        <input type="file" id="duzenleUyeFormu" accept=".pdf" class="form-control">
                        <small>Yeni bir PDF yüklemek için seçiniz</small>
                    </div>
                    <div style="margin-top:15px; text-align:right;">
                        <button type="submit" class="btn">Değişiklikleri Kaydet</button>
                        <button type="button" class="btn btn-cancel" onclick="closeModal('duzenleUyeModal')">İptal</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Yeni Üye Ekle Modal -->
        <div id="yeniUyeModal" class="modal">
          <div class="modal-content" style="max-width:400px;">
            <h2>Yeni Üye Ekle</h2>
            <form id="yeniUyeForm">
              <div class="form-group">
                <label for="yeniKayitSekli">Kayıt Şekli:</label>
                <input type="text" id="yeniKayitSekli" value="yönetici" readonly class="form-control">
              </div>
              <div class="form-group">
                <label for="yeniBasvuruTarihi">Başvuru Tarihi:</label>
                <input type="date" id="yeniBasvuruTarihi" name="basvuru_tarihi" required class="form-control">
              </div>
              <div class="form-group">
                <label for="yeniTcNo">TC Kimlik No:</label>
                <input type="text" id="yeniTcNo" name="tcno" required class="form-control">
              </div>
              <div class="form-group">
                <label for="yeniUyelikFormu">Üyelik Formu:</label>
                <input type="file" id="yeniUyelikFormu" accept=".pdf" required class="form-control">
                <small>PDF formatında üyelik formu yükleyiniz</small>
              </div>
              <div style="margin-top:15px; text-align:right;">
                <input type="hidden" name="topluluk_id" id="topluluk_id" value="{{ session('t_id') }}">
                <button type="submit" class="btn">Ekle</button>
                <button type="button" class="btn btn-cancel" onclick="closeModal('yeniUyeModal')">İptal</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Üye Sil Modal -->
        <div id="uyeSilModal" class="modal">
          <div class="modal-content">
            <h2>Üye Sil</h2>
            <input type="text" id="silSearchInput" class="form-control" placeholder="Öğrenci No ile ara..." onkeyup="searchUyeSil()" style="margin-bottom: 15px; max-width: 300px;">
            <div class="uye-listesi">
              <table>
                <thead>
                  <tr>
                    <th>Başvuru Tarihi</th>
                    <th>Öğrenci No</th>
                    <th>Ad Soyad</th>
                    <th>Telefon Numarası</th>
                    <th>Eposta</th>
                    <th>Fakülte</th>
                    <th>Bölüm</th>
                    <th>Üyelik Formu</th>
                    <th>İşlem</th>
                  </tr>
                </thead>
                <tbody id="uyeSilListesi">
                  <!-- JavaScript ile doldurulacak -->
                </tbody>
              </table>
            </div>
            <button type="button" class="btn btn-cancel" onclick="closeModal('uyeSilModal')">Kapat</button>
          </div>
        </div>

        <!-- Ayrılış Sebebi Modalı (Silme için) -->
        <div id="ayrilisSebepModal" class="modal">
          <div class="modal-content" style="max-width:400px;">
            <h2>Ayrılış Sebebi Giriniz</h2>
            <form id="ayrilisSebepForm">
              <textarea id="ayrilisSebepInput" rows="4" style="width:100%;resize:vertical;" placeholder="Ayrılış sebebini yazınız..." required></textarea>
              <div style="margin-top:15px; text-align:right;">
                <button type="submit" class="btn btn-sil">Gönder</button>
                <button type="button" class="btn btn-cancel" onclick="closeAyrilisSebepModal()">İptal</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Red Sebebi Modal -->
        <div id="redSebepModal" class="modal">
            <div class="modal-content" style="max-width:400px;">
                <h2>Red Sebebi Giriniz</h2>
                <form id="redSebepForm">
                    <textarea id="redSebepInput" rows="4" style="width:100%;resize:vertical;" placeholder="Red sebebini yazınız..." required></textarea>
                    <div style="margin-top:15px; text-align:right;">
                        <button type="submit" class="btn btn-reddet">Gönder</button>
                        <button type="button" class="btn btn-cancel" onclick="closeRedSebepModal()">İptal</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Yönetim Başvuruları Modal -->
        <div id="yonetimBasvurulariModal" class="modal">
            <div class="modal-content">
                <h2>Yönetim Başvuruları</h2>
                <input type="text" id="yonetimBasvuruSearchInput" class="form-control" placeholder="Öğrenci No ile ara..." onkeyup="searchYonetimBasvurulari()" style="margin-bottom: 15px; max-width: 300px;">
                <div class="uye-listesi">
                    <table>
                        <thead>
                            <tr>
                                <th>Başvuru Tarihi</th>
                                <th>Öğrenci No</th>
                                <th>Ad Soyad</th>
                                <th>Cep Tel</th>
                                <th>Email</th>
                                <th>Fakülte</th>
                                <th>Bölüm</th>
                                <th>Sınıf</th>
                                <th>Üyelik Formu</th>
                                <th>Niyet Metni</th>
                            </tr>
                        </thead>
                        <tbody id="yonetimBasvurulariListesi">
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('yonetimBasvurulariModal')">Kapat</button>
            </div>
        </div>
        <!-- Niyet Metni Modalı -->
        <div id="niyetMetniModal" class="modal">
            <div class="modal-content" style="max-width:500px;">
                <h2>Niyet Metni</h2>
                <div id="niyetMetniIcerik" style="white-space:pre-line; margin: 20px 0;"></div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('niyetMetniModal')">Kapat</button>
            </div>
        </div>
        <!-- Silinen Üyeler Modal -->
        <div id="silinenUyelerModal" class="modal">
          <div class="modal-content">
            <h2>Silinen Üyeler ve Reddedilen Başvurular</h2>
            <div class="uye-listesi">
              <table>
                <thead>
                  <tr>
                    <th>Başvuru Tarihi</th>
                    <th>Öğrenci No</th>
                    <th>Ad Soyad</th>
                    <th>Telefon Numarası</th>
                    <th>Eposta</th>
                    <th>Fakülte</th>
                    <th>Bölüm</th>
                    <th>Üyelik Formu</th>
                    <th>Durum</th>
                    <th>Sebep</th>
                  </tr>
                </thead>
                <tbody id="silinenUyelerListesi">
                  <!-- JS ile doldurulacak -->
                </tbody>
              </table>
            </div>
            <button type="button" class="btn btn-cancel" onclick="closeModal('silinenUyelerModal')">Kapat</button>
          </div>
        </div>
        <!-- Üye Mesajları Modal -->
        <div id="uyeMesajModal" class="modal">
            <div class="modal-content">
                <h2>Üye Mesajları</h2>
                <div class="uye-listesi">
                    <table>
                        <thead>
                            <tr>
                                <th>Öğrenci No</th>
                                <th>Ad</th>
                                <th>Soyad</th>
                                <th>Telefon Numarası</th>
                                <th>Eposta</th>
                                <th>Fakülte</th>
                                <th>Bölüm</th>
                                <th>Mesaj</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody id="uyeMesajListesi">
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('uyeMesajModal')">Kapat</button>
            </div>
        </div>
        <!-- Mesaj Görüntüle Modalı -->
        <div id="mesajGoruntuleModal" class="modal">
            <div class="modal-content" style="max-width:400px;">
                <h2>Mesaj İçeriği</h2>
                <div id="mesajIcerik" style="white-space:pre-line; margin: 20px 0;"></div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('mesajGoruntuleModal')">Kapat</button>
            </div>
        </div>
        <!-- Mesaj Sil Modalı -->
        <div id="mesajSilModal" class="modal">
            <div class="modal-content" style="max-width:400px;">
                <h2>Mesajı Sil</h2>
                <p>Mesajı silmek istediğinize emin misiniz?</p>
                <div style="margin-top:15px; text-align:right;">
                    <button type="button" class="btn btn-sil" id="mesajSilOnayBtn">Evet, Sil</button>
                    <button type="button" class="btn btn-cancel" onclick="closeModal('mesajSilModal')">İptal</button>
                </div>
            </div>
        </div>
    </div>
</div>
@php
    $sosyal = null;
    if(session('t_id')) {
        $sosyal = \DB::table('sosyal_medya')->where('t_id', session('t_id'))->first();
    }
@endphp
<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Adres</h3>
            <p>Yaka Mah. Yeni Meram Cad. Kasım Halife Sok. No:11 (B Blok) 42090 Meram/Konya</p>
        </div>
        <div class="footer-section">
            <h3>İletişim</h3>
            <p>Tel : 0 332 221 0 561</p>
            <p>Fax : 0 332 235 98 03</p>
        </div>
        <div class="footer-section">
            <h3>Sosyal Medya & Eposta</h3>
            <div class="social-icons">
                @if($sosyal && $sosyal->i_onay == 1 && $sosyal->instagram)
                    <a href="{{ $sosyal->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                @else
                    <span class="social-icon-disabled"><i class="fab fa-instagram"></i></span>
                @endif
                @if($sosyal && $sosyal->w_onay == 1 && $sosyal->whatsapp)
                    <a href="{{ $sosyal->whatsapp }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                @else
                    <span class="social-icon-disabled"><i class="fab fa-whatsapp"></i></span>
                @endif
                @if($sosyal && $sosyal->l_onay == 1 && $sosyal->linkedln)
                    <a href="{{ $sosyal->linkedln }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                @else
                    <span class="social-icon-disabled"><i class="fab fa-linkedin-in"></i></span>
                @endif
            </div>
            <p>topluluk@erbakan.edu.tr</p>
        </div>
    </div>
    <div class="footer-bottom">
        © 2022 Necmettin Erbakan Üniversitesi
    </div>
</footer>

<script src="{{ asset('js/panel_uye_islemleri.js') }}"></script>
</body>
</html>

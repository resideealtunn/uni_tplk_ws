<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik İşlemleri</title>
    <link rel="stylesheet" href="{{ asset('css/style_panels.css') }}">
    <link rel="stylesheet" href="{{ asset('css/etkinlik_islemleri.css') }}">
</head>
<body>
<div class="sidebar">
    <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
    <h2>{{ session('topluluk') }}</h2>
    <h3>{{ session('isim') }}</h3>
    <p>{{ session('rol') }}</p>

    <div class="menu">
        <a href="/yonetici_panel" class="menu-item">Web Arayüz İşlemleri</a>
        <a href="/etkinlik_islemleri" class="menu-item">Etkinlik İşlemleri</a>
        <a href="/uye_islemleri" class="menu-item active">Üye İşlemleri</a>

        <!-- Çıkış Butonu Formu -->
        <form action="{{ route('cikis') }}" method="POST" id="cikisForm" style="display: none;">
            @csrf
        </form>
        <!-- Çıkış Div'i -->
        <div class="menu-item" onclick="document.getElementById('cikisForm').submit();">
            Çıkış
        </div>
    </div>

</div>

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
        </div>
    </div>

    <!-- Üyeleri Listele Modal -->
    <div id="uyeListeModal" class="modal">
        <div class="modal-content">
            <h2>Üye Listesi</h2>
            <div class="uye-listesi">
                <table>
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
                            <th>Onay Durumu</th>
                            <th>Ayrılış Tarihi/Sebebi</th>
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
        <div class="uye-listesi">
            <table>
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
                        <th>İşlem</th>
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

        <input type="text" id="searchInput" placeholder="Öğrenci No ile ara..." onkeyup="searchUye()">

        <div class="uye-listesi">
            <table>
                <thead>
                    <tr>
                        <th>Üyelik Türü</th>
                        <th>Kayıt Şekli</th>
                        <th>Başvuru Tarihi</th>
                        <th>Öğrenci No</th>
                        <th>Ad Soyad</th>
                        <th>Cep Tel</th>
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

<!-- Düzenleme Formu Modal -->
<div id="duzenleFormModal" class="modal">
    <div class="modal-content">
        <h2>Üye Bilgilerini Güncelle</h2>
        <form id="duzenleForm">
            <input type="hidden" id="duzenleOgrenciNo">

            <label>Kayıt Şekli:</label>
            <input type="text" id="duzenleKayitSekli" required>

            <label>Başvuru Tarihi:</label>
            <input type="date" id="duzenleBasvuruTarihi" required>

            <label>Ad Soyad:</label>
            <input type="text" id="duzenleAdSoyad" required>

            <label>Cep Tel:</label>
            <input type="text" id="duzenleCepTel" required>

            <label>Fakülte:</label>
            <input type="text" id="duzenleFakulte" required>

            <label>Bölüm:</label>
            <input type="text" id="duzenleBolum" required>

            <label>Üyelik Formu:</label>
            <input type="file" id="duzenleUyelikFormu" accept=".pdf">
            <small>Yeni bir PDF yüklemek için seçiniz</small>

            <button type="submit" class="btn">Güncelle</button>
            <button type="button" class="btn btn-cancel" onclick="closeModal('duzenleFormModal')">İptal</button>
        </form>
    </div>
</div>

<!-- Yeni Üye Ekle Modal -->
<div id="yeniUyeModal" class="modal">
  <div class="modal-content">
    <h2>Yeni Üye Ekle</h2>
    <form id="yeniUyeForm">
      <label>Kayıt Şekli:</label>
      <input type="text" id="yeniKayitSekli" value="yönetici" readonly>

      <label>Başvuru Tarihi:</label>
      <input type="date" id="yeniBasvuruTarihi" readonly>

      <label>Öğrenci No:</label>
      <input type="text" id="yeniOgrNo" required>

      <label>Ad Soyad:</label>
      <input type="text" id="yeniAdSoyad" required>

      <label>Cep Tel:</label>
      <input type="text" id="yeniCepTel" required>

      <label>Fakülte:</label>
      <input type="text" id="yeniFakulte" required>

      <label>Bölüm:</label>
      <input type="text" id="yeniBolum" required>

      <label>Üyelik Formu:</label>
      <input type="file" id="yeniUyelikFormu" accept=".pdf" required>
      <small>PDF formatında üyelik formu yükleyiniz</small>

      <button type="submit" class="btn">Ekle</button>
      <button type="button" class="btn btn-cancel" onclick="closeModal('yeniUyeModal')">İptal</button>
    </form>
  </div>
</div>

<!-- Üye Sil Modal -->
<div id="uyeSilModal" class="modal">
  <div class="modal-content">
    <h2>Üye Sil</h2>

    <!-- Öğrenci Numarası ile Arama -->
    <input type="text" id="searchInput" placeholder="Öğrenci No ile ara..." onkeyup="searchUyeSil()">

    <!-- Üye Listesi -->
    <div class="uye-listesi">
      <table>
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
            <th>Onay Durumu</th>
            <th>Ayrılış Sebebi</th>
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

    <script src="{{ asset('js/panel_uye_islemleri.js') }}"></script>
</body>
</html>

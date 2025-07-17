<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Topluluk Denetimi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/denetim_etkinlik.css') }}">

    <link rel="stylesheet" href="{{ asset('css/denetim_uye.css') }}">
    <!-- jQuery (Select2'den önce eklenmeli) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body>

<!-- Hamburger Menü -->
<div class="hamburger" id="hamburger">
    <span></span>
    <span></span>
    <span></span>
</div>

<div class="sidebar" id="sidebar">
    <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
    <h2>{{session('isim')}}</h2>
    <h3>{{session('unvan')}}</h3>
    <p>{{session('birim')}}</p>

    <div class="menu">
        <a href="{{ route('denetim.topluluk') }}" class="menu-item">Topluluk İşlemleri</a>
        <a href="{{ route('denetim.etkinlik') }}" class="menu-item">Etkinlik İşlemleri</a>
        <a href="{{ route('denetim.uye') }}" class="menu-item active" style="background-color: #3498db !important; color: #fff !important;">Üye İşlemleri</a>
        <a href="{{ route('denetim.formlar') }}" class="menu-item">Form İşlemleri</a>
        <a href="{{ route('denetim.panel') }}" class="menu-item">Web Arayüz İşlemleri</a>
        <div class="menu-item" onclick="window.location.href='{{ route('anasayfa') }}'">Çıkış</div>
    </div>

</div>


<div class="content" id="web">
    <div class="form-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Üye Denetim İşlemleri</h2>
            <button class="btn btn-success" onclick="openExcelModal()">
                <i class="fas fa-file-excel"></i> Üye Listesini Excel Formatında İndir
            </button>
        </div>

        <div class="info-section mb-4">
            <h3>Topluluklar </h3>
            <div class="search-container">
                <input type="text" id="searchToplulukName" class="search-input" placeholder="Topluluk adına göre ara...">
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center w-100">
                    <thead class="table-dark">
                    <tr>
                        <th>Topluluk Adı</th>
                        <th>Logo</th>
                        <th>Üye Listesi</th>
                        <th>Üyelik Başvuruları</th>
                        <th>Güncelle</th>
                        <th>Yeni Üye</th>
                        <th>Üye Sil</th>
                    </tr>
                    </thead>
                    <tbody id="topluluklarTableBody">
                    @foreach ($topluluklar as $index => $topluluk)
                        <tr>
                            <td>{{ $topluluk->isim }}</td>
                            <td><img src="{{ asset('images/logo/' . $topluluk->gorsel) }}" alt="Logo" class="img-logo" style="width: 50px;"></td>
                            <td>
                                <button class="btn btn-primary" onclick="openUyeListesiModal({{ $topluluk->id }})">
                                    Üye Listesi
                                </button>
                            </td>
                            <td><button class="btn btn-secondary" onclick="openBasvuruListeModal({{ $topluluk->id }})">Başvurular</button></td>
                            <td><button class="btn btn-warning" onclick="openGuncelleModal({{ $topluluk->id }})">Güncelle</button></td>
                            <td><button class="btn btn-success" onclick="openYeniUyeModal({{ $topluluk->id }})">Yeni Üye</button></td>
                            <td><button class="btn btn-danger" onclick="openSilModal({{ $topluluk->id }})">Sil</button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div id="uyeListeModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Üye Listesi</h2>
                <div class="search-container">
                    <input type="text" id="searchInputUyeListesi" class="search-input" placeholder="Öğrenci No ile Ara..." inputmode="numeric" pattern="[0-9]*">
                </div>
                <div class="uye-listesi">
                    <table class="table table-bordered table-striped align-middle text-center w-100 uye-lacivert-table">
                        <thead class="table-dark lacivert-table-head">
                        <tr>
                            <th>Tarih</th>
                            <th>Öğrenci No</th>
                            <th>Ad Soyad</th>
                            <th>Cep Tel</th>
                            <th>Fakülte</th>
                            <th>Bölüm</th>
                            <th>Üyelik Formu</th>
                        </tr>
                        </thead>
                        <tbody id="uyeListesi">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="basvuruListeModal" class="modal">
            <div class="modal-content">
                <h2>Üyelik Başvuruları</h2>
                <div class="search-container">
                    <input type="text" id="searchBasvuruNo" class="search-input" placeholder="Öğrenci No ile Ara..." inputmode="numeric" pattern="[0-9]*" oninput="filterListBasvuru('basvuruListesi', 'searchBasvuruNo')">
                </div>
                <div class="uye-listesi">
                    <table class="table table-bordered table-striped align-middle text-center w-100 uye-lacivert-table">
                        <thead class="table-dark lacivert-table-head">
                        <tr>
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
            </div>
        </div>

        <div id="redSebebiModal" class="modal">
            <div class="modal-content">
                <h2>Red Sebebi</h2>
                <div class="form-group">
                    <label for="redSebebi">Red Sebebini Giriniz:</label>
                    <textarea id="redSebebi" class="form-control" rows="4" placeholder="Red sebebini buraya yazınız..."></textarea>
                </div>
                <div class="button-group">
                    <button type="button" class="btn btn-success" >Gönder</button>
                </div>
            </div>
        </div>

        <div id="guncelleModal" class="modal">
            <div class="modal-content">
                <h2>Üye Güncelleme Listesi</h2>
                <div class="search-container">
                    <input type="text" id="searchGuncelleNo" class="search-input" placeholder="Öğrenci No ile Ara..." inputmode="numeric" pattern="[0-9]*" oninput="filterListUpdate('guncelleUyeListesi', 'searchGuncelleNo')">
                </div>
                <div class="uye-listesi">
                    <table class="table table-bordered table-striped align-middle text-center w-100 uye-lacivert-table">
                        <thead class="table-dark lacivert-table-head">
                        <tr>
                            <th>Tarih</th>
                            <th>Öğrenci No</th>
                            <th>Ad Soyad</th>
                            <th>Cep Tel</th>
                            <th>Fakülte</th>
                            <th>Bölüm</th>
                            <th>Üyelik Formu</th>
                            <th>Rol</th>
                            <th>Görev</th>
                            <th>İşlem</th>
                        </tr>
                        </thead>
                        <tbody id="guncelleUyeListesi">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="duzenleModal" class="modal">
            <div class="modal-content">
                <h2>Üye Bilgilerini Düzenle</h2>
                <form id="duzenleForm">
                    <div>
                        <label>Öğrenci No</label>
                        <input type="text" id="editOgrenciNo" pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumbers(event)" required>
                    </div>
                    <div>
                        <label>Ad Soyad</label>
                        <input type="text" id="editAdSoyad" required>
                    </div>
                    <div>
                        <label>Cep Tel</label>
                        <input type="text" id="editCepTel" pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumbers(event)" maxlength="11" required>
                    </div>
                    <div>
                        <label>Fakülte</label>
                        <input type="text" id="editFakulte" required>
                    </div>
                    <div>
                        <label>Bölüm</label>
                        <input type="text" id="editBolum" required>
                    </div>
                    <div>
                        <label>Üyelik Formu</label>
                        <div class="file-upload-container">
                            <input type="file" id="editUyeFormu" accept=".pdf" onchange="validatePDF(this)" required>
                            <div id="currentPdf" class="current-pdf"></div>
                        </div>
                    </div>
                    <br>
                    <div class="button-group">
                        <button type="button" class="btn btn-success btn-equal-size" >Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="yeniUyeModal" class="modal">
            <div class="modal-content">
                <h2>Yeni Üye Ekle</h2>
                <form id="yeniUyeForm">
                    <div>
                        <label>Kayıt Şekli:</label>
                        <input type="text" id="kayitSekli" value="denetim" readonly required>
                    </div>
                    <div>
                        <label>Başvuru Tarihi:</label>
                        <input type="date" id="basvuruTarihi" value="{{ date('Y-m-d') }}" readonly required>
                    </div>
                    <div>
                        <label>TC Kimlik No:</label>
                        <input type="text" id="tcKimlikNo" name="tcKimlikNo" required maxlength="11" pattern="[0-9]{11}">
                    </div>
                    <div>
                        <label>Üyelik Formu:</label>
                        <input type="file" id="uyelikFormu" name="uyelikFormu" accept=".pdf" required>
                        <small>PDF formatında üyelik formu yükleyiniz</small>
                    </div>
                    <br>
                    <div class="button-group">
                        <button type="submit" class="btn btn-success btn-equal-size">Ekle</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Silinen Üyeler Geri Bildirimleri Modalı -->
<div id="silinenUyelerGeriModal" class="modal">
    <div class="modal-content">
        <h2>Üye Silme Geri Bildirimleri</h2>
        <div class="uye-listesi">
            <table class="table table-bordered table-striped align-middle text-center w-100 uye-lacivert-table">
                <thead class="table-dark lacivert-table-head">
                    <tr>
                        <th>Öğrenci No</th>
                        <th>Ad Soyad</th>
                        <th>Cep Tel</th>
                        <th>Fakülte</th>
                        <th>Bölüm</th>
                        <th>Üyelik Formu</th>
                        <th>Silinme Sebebi</th>
                    </tr>
                </thead>
                <tbody id="silinenUyelerGeriListesi">
                    <!-- JS ile doldurulacak -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Silinme Sebebi Mini Modalı -->
<div id="silSebepMiniModal" class="modal" style="display:none;">
    <div class="modal-content small-modal">
        <h3>Silinme Sebebi</h3>
        <div id="silSebepIcerik" style="white-space:pre-line; margin: 20px 0;"></div>
    </div>
</div>

<!-- Sil Modal -->
<div id="silModal" class="modal">
    <div class="modal-content">
        <h2>Silinecek Üyeler</h2>
        <div class="search-container">
            <input type="text" id="searchInputSilListesi" class="search-input" placeholder="Öğrenci numarası ile ara..." inputmode="numeric" pattern="[0-9]*">
        </div>
        <div class="uye-listesi">
            <table class="uye-lacivert-table">
                <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Öğrenci No</th>
                        <th>Ad</th>
                        <th>Soyad</th>
                        <th>Cep Tel</th>
                        <th>Fakülte</th>
                        <th>Bölüm</th>
                        <th>Üyelik Formu</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody id="silListesi">
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Silme Sebebi Modal -->
<div id="silSebebiModal" class="modal">
    <div class="modal-content">
        <h2>Silme Sebebi</h2>
        <div class="form-group">
            <label for="silinmeSebebi">Silme Sebebi:</label>
            <textarea id="silinmeSebebi" class="form-control" rows="4" required></textarea>
        </div>
        <div class="button-group">
            <button class="btn btn-success">Sil</button>
        </div>
    </div>
</div>

<!-- Excel İndirme Modal -->
<div id="excelModal" class="modal">
    <div class="modal-content excel-modal">
        <div class="modal-header">
            <h2>Üye Listesini Excel Formatında İndir</h2>
            <span class="close" onclick="closeModal('excelModal')">&times;</span>
        </div>
        <div class="modal-body">
            <div class="search-container">
                <input type="text" id="toplulukSearch" class="search-input" placeholder="Topluluk adına göre ara...">
            </div>
            <div class="topluluk-list" id="toplulukList">
                <!-- Topluluklar burada listelenecek -->
            </div>
        </div>
        <div class="modal-footer">
            <button id="indirButton" class="btn btn-success" onclick="indirExcel()" disabled>
                <i class="fas fa-download"></i> Üyeleri Excel Olarak İndir
            </button>
            <button class="btn btn-secondary" onclick="closeModal('excelModal')">İptal</button>
        </div>
    </div>
</div>

</div> <!-- .content bitişi -->
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
            <h3>Eposta</h3>

            <p>topluluk@erbakan.edu.tr</p>
        </div>
    </div>
    <div class="footer-bottom">
        © 2022 Necmettin Erbakan Üniversitesi
    </div>
</footer>
<style>
.content { padding-bottom: 60px !important; }
.footer { position: static !important; margin-top: 40px; }
</style>
<!-- Bootstrap JS (Popper + Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/denetim_uye.js') }}"></script>
<script src="{{ asset('js/denetim_uye_menu.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchToplulukName');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const value = this.value.toLowerCase();
            const rows = document.querySelectorAll('#topluluklarTableBody tr');
            rows.forEach(row => {
                const name = row.children[0]?.textContent.toLowerCase() || '';
                row.style.display = name.includes(value) ? '' : 'none';
            });
        });
    }
    
    // Sadece rakam girilebilsin
    const numberInputs = [
        document.getElementById('searchInputUyeListesi'),
        document.getElementById('searchBasvuruNo'),
        document.getElementById('searchGuncelleNo'),
        document.getElementById('searchInputSilListesi')
    ];
    numberInputs.forEach(function(input) {
        if (input) {
            input.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    });
    
    // Excel modalı açıldığında arama kutusunu temizle
    const excelModal = document.getElementById('excelModal');
    if (excelModal) {
        excelModal.addEventListener('show', function() {
            const searchInput = document.getElementById('toplulukSearch');
            if (searchInput) {
                searchInput.value = '';
            }
        });
    }
});
</script>
</body>

</html>

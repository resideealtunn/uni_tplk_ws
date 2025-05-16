<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topluluk Denetimi</title>
    <link rel="stylesheet" href="{{ asset('css/denetim_etkinlik.css') }}">

    <link rel="stylesheet" href="{{ asset('css/denetim_uye.css') }}">

</head>

<body>
<div class="sidebar">
    <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
    <h2>Rektörlük</h2>
    <h3>Ali Ataseven</h3>
    <p>Sağlık, Kültür, Sanat</p>

    <div class="menu">
        <a href="{{ route('denetim.panel') }}" class="menu-item">Topluluk & Web Arayüz İşlemleri</a>
        <a href="{{ route('denetim.etkinlik') }}" class="menu-item ">Etkinlik İşlemleri</a>
        <a href="{{ route('denetim.uye') }}" class="menu-item active" style="background-color: #3498db !important; color: #fff !important;">Üye İşlemleri</a>
        <div class="menu-item" onclick="window.location.href='{{ route('kesfet') }}'">Çıkış</div>
    </div>

</div>


<div class="content" id="web">
    <div class="form-container">
        <h2>Üye Denetim İşlemleri</h2>

        <div class="info-section mb-4">
            <h3>Topluluklar </h3>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center w-100">
                    <thead class="table-dark">
                    <tr>
                        <th>Topluluk ID</th>
                        <th>Topluluk Adı</th>
                        <th>Logo</th>
                        <th>Üye Listesi</th>
                        <th>Üyelik Başvuruları</th>
                        <th>Güncelle</th>
                        <th>Yeni Üye</th>
                        <th>Üye Sil</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Bilişim Topluluğu</td>
                        <td><img src="{{ asset('images/logo/bilisimlogo.png') }}" alt="Logo" class="img-logo"></td> <!-- Logo burada yer alacak -->
                        <td><button class="btn btn-primary" onclick="openUyeListesiModal()">Üye Listesi</button></td>
                        <td><button class="btn btn-secondary" onclick="openBasvuruListeModal()">Başvurular</button></td>
                        <td><button class="btn btn-warning" onclick="openGuncelleModal()">Güncelle</button></td>
                        <td><button class="btn btn-success" onclick="openYeniUyeModal()">Yeni Üye</button></td>
                        <td><button class="btn btn-danger" onclick="openSilModal()">Sil</button></td> <!-- Pass the member ID here -->
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Sağlık Topluluğu</td>
                        <td><img src="{{ asset('images/logo/bilisimlogo.png') }}" alt="Logo" class="img-logo"></td> <!-- Logo burada yer alacak -->
                        <td><button class="btn btn-primary" onclick="openUyeListesiModal()">Üye Listesi</button></td>
                        <td><button class="btn btn-secondary" onclick="openBasvuruListeModal()">Başvurular</button></td>
                        <td><button class="btn btn-warning" onclick="openGuncelleModal()">Güncelle</button></td>
                        <td><button class="btn btn-success" onclick="openYeniUyeModal()">Yeni Üye</button></td>
                        <td><button class="btn btn-danger" onclick="openSilModal()">Sil</button></td> <!-- Pass the member ID here -->
                    </tr>

                    </tbody>
                </table>

            </div>
        </div>
        <!-- Üyeleri Listele Modal -->
        <div id="uyeListeModal" class="modal">
            <div class="modal-content">
                <h2>Üye Listesi</h2>
                <!-- Search Input for Öğrenci No -->
                <div class="search-container">
                    <input type="text" id="searchUyeNo" class="search-input" placeholder="Öğrenci No ile Ara..." oninput="filterList('uyeListesi', 'searchUyeNo')">
                </div>
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
                        <!-- Üyeler JavaScript ile doldurulacak -->
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('uyeListeModal')">Kapat</button>
            </div>
        </div>

        <!-- Üyelik Başvuruları Modal -->
        <div id="basvuruListeModal" class="modal">
            <div class="modal-content">
                <h2>Üyelik Başvuruları</h2>
                <!-- Search Input for Öğrenci No -->
                <div class="search-container">
                    <input type="text" id="searchBasvuruNo" class="search-input" placeholder="Öğrenci No ile Ara..." oninput="filterListBasvuru('basvuruListesi', 'searchBasvuruNo')">
                </div>
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
                        <!-- JavaScript ile doldurulacak -->
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('basvuruListeModal')">Kapat</button>
            </div>
        </div>

        <!-- Red Sebebi Modalı -->
        <div id="redSebebiModal" class="modal">
            <div class="modal-content">
                <h2>Red Sebebi</h2>
                <div class="form-group">
                    <label for="redSebebi">Red Sebebini Giriniz:</label>
                    <textarea id="redSebebi" class="form-control" rows="4" placeholder="Red sebebini buraya yazınız..."></textarea>
                </div>
                <div class="button-group">
                    <button type="button" class="btn btn-success" onclick="redSebebiGonder()">Gönder</button>
                    <button type="button" class="btn btn-cancel" onclick="closeModal('redSebebiModal')">İptal</button>
                </div>
            </div>
        </div>

        <!-- Güncelle Modalı -->
        <div id="guncelleModal" class="modal">
            <div class="modal-content">
                <h2>Üye Güncelleme Listesi</h2>
                <!-- Search Input for Öğrenci No -->
                <div class="search-container">
                    <input type="text" id="searchGuncelleNo" class="search-input" placeholder="Öğrenci No ile Ara..." oninput="filterListUpdate('guncelleUyeListesi', 'searchGuncelleNo')">
                </div>
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
                        <tbody id="guncelleUyeListesi">
                        <!-- JavaScript ile doldurulacak -->
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('guncelleModal')">Kapat</button>
            </div>
        </div>
        <!-- Üye Düzenleme Modalı -->
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
                        <button type="button" class="btn btn-success btn-equal-size" onclick="saveChanges()">Kaydet</button>
                        <button type="button" class="btn btn-cancel btn-equal-size" onclick="closeModal('duzenleModal')">İptal</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Yeni Üye Ekle Modalı -->
        <div id="yeniUyeModal" class="modal">
            <div class="modal-content">
                <h2>Yeni Üye Ekle</h2>
                <form id="yeniUyeForm">
                    <div>
                        <label>Kayıt Şekli:</label>
                        <input type="text" id="yeniKayitSekli" value="denetim" readonly>
                    </div>
                    <div>
                        <label>Başvuru Tarihi:</label>
                        <input type="date" id="yeniBasvuruTarihi" readonly>
                    </div>
                    <div>
                        <label>Öğrenci No:</label>
                        <input type="text" id="yeniOgrNo" required>
                    </div>
                    <div>
                        <label>Tek Şifre:</label>
                        <input type="text" id="yeniteksifre" required>
                    </div>
                    <div>
                        <label>Ad Soyad:</label>
                        <input type="text" id="yeniAdSoyad" required>
                    </div>
                    <div>
                        <label>Cep Tel:</label>
                        <input type="text" id="yeniCepTel" required>
                    </div>
                    <div>
                        <label>Fakülte:</label>
                        <input type="text" id="yeniFakulte" required>
                    </div>
                    <div>
                        <label>Bölüm:</label>
                        <input type="text" id="yeniBolum" required>
                    </div>
                    <br>
                    <div class="button-group">
                        <button type="button" class="btn btn-success btn-equal-size" onclick="saveNewUye()">Kaydet</button>
                        <button type="button" class="btn btn-cancel btn-equal-size" onclick="closeModal('yeniUyeModal')">İptal</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Silme Modalı -->
        <div id="silModal" class="modal">
            <div class="modal-content">
                <h2>Üye Silme Listesi</h2>
                <!-- Search Input for Öğrenci No -->
                <div class="search-container">
                    <input type="text" id="searchSilNo" class="search-input" placeholder="Öğrenci No ile Ara..." oninput="filterListDelete('silListesi', 'searchSilNo')">
                </div>
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
                            <th>Ayrılış Sebebi</th> <!-- Change here -->
                            <th>İşlem</th>
                        </tr>
                        </thead>
                        <tbody id="silListesi">
                        <!-- Üyeler JavaScript ile doldurulacak -->
                        </tbody>
                    </table>
                </div>
                <!-- Add combobox for Exit Reason -->

                <button type="button" class="btn btn-cancel" onclick="closeModal('silModal')">Kapat</button>
            </div>
        </div>



        <!-- Footer -->
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
                        <i class="fab fa-facebook-f"></i>
                        <i class="fab fa-twitter"></i>
                        <i class="fab fa-instagram"></i>
                        <i class="fab fa-linkedin-in"></i>
                        <i class="fab fa-youtube"></i>
                    </div>
                    <p>topluluk@erbakan.edu.tr</p>
                </div>
            </div>
            <div class="footer-bottom">
                © 2022 Necmettin Erbakan Üniversitesi
            </div>
        </footer>
        <!-- Bootstrap JS (Popper + Bootstrap) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/denetim_uye.js') }}"></script>

</body>

</html>

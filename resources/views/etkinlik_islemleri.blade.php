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
        <img src="{{ asset('images/neu_logo.png') }}" alt="Logo">
        <h2>BİLİŞİM TOPLULUĞU</h2>
        <h3>REŞİDE ALTUN</h3>
        <p>BİLGİSAYAR MÜHENDİSLİĞİ</p>

        <div class="menu">
            <a href="/yonetici" class="menu-item">Web Arayüz İşlemleri</a>
            <a href="/etkinlik_islemleri" class="menu-item active">Etkinlik İşlemleri</a>
            <div class="menu-item">Üye İşlemleri</div>
            <div class="menu-item">Çıkış</div>
        </div>
    </div>

    <div class="content active">
        <div class="action-container">
            <h1>Etkinlik İşlemleri</h1>
            
            <div class="action-card" onclick="showEtkinlikEkleModal()">
                <h2>Etkinlik Ekle</h2>
                <p>Yeni bir etkinlik oluşturmak için tıklayın</p>
            </div>

            <div class="action-card" onclick="showBasvuruModal()">
                <h2>Başvuru Aç/Kapat</h2>
                <p>Etkinlik başvurularını yönetmek için tıklayın</p>
            </div>

            <div class="action-card" onclick="showYoklamaModal()">
                <h2>Yoklama Aç/Kapat</h2>
                <p>Etkinlik yoklamalarını yönetmek için tıklayın</p>
            </div>

            <div class="action-card" onclick="showPaylasModal()">
                <h2>Etkinlik Paylaş</h2>
                <p>Etkinliği paylaşmak için tıklayın</p>
            </div>

            <div class="action-card" onclick="showBasvuruListeModal()">
                <h2>Etkinlik Başvurularını Listele</h2>
                <p>Etkinlik başvurularını görüntülemek için tıklayın</p>
            </div>
        </div>
    </div>

    <!-- Etkinlik Ekle Modal -->
    <div id="etkinlikEkleModal" class="modal">
        <div class="modal-content">
            <h2>Etkinlik Ekle</h2>
            <form id="etkinlikEkleForm">
                <div class="form-group">
                    <label for="etkinlikBaslik">Etkinlik Başlığı:</label>
                    <input type="text" id="etkinlikBaslik" required>
                </div>
                <div class="form-group">
                    <label for="etkinlikKisaBilgi">Kısa Bilgi:</label>
                    <input type="text" id="etkinlikKisaBilgi" required>
                </div>
                <div class="form-group">
                    <label for="etkinlikAciklama">Açıklama:</label>
                    <textarea id="etkinlikAciklama" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="etkinlikAfiş">Tanıtım Afişi:</label>
                    <input type="file" id="etkinlikAfiş" accept="image/*" required>
                </div>
                <button type="submit" class="btn">Etkinlik Ekle</button>
                <button type="button" class="btn btn-cancel" onclick="closeModal('etkinlikEkleModal')">İptal</button>
            </form>
        </div>
    </div>

    <!-- Başvuru Aç/Kapat Modal -->
    <div id="basvuruModal" class="modal">
        <div class="modal-content">
            <h2>Başvuru Aç/Kapat</h2>
            <form id="basvuruForm">
                <div class="form-group">
                    <label for="etkinlikSec">Etkinlik Seçin:</label>
                    <select id="etkinlikSec" class="form-control" required>
                        <option value="">Etkinlik seçiniz</option>
                        <option value="1">Etkinlik 1</option>
                        <option value="2">Etkinlik 2</option>
                        <option value="3">Etkinlik 3</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Mevcut Durum:</label>
                    <p id="mevcutDurum" class="durum-text">Başvurular Kapalı</p>
                </div>
                <button type="button" class="btn" onclick="toggleBasvuru()">Başvuru Aç/Kapat</button>
                <button type="button" class="btn btn-cancel" onclick="closeModal('basvuruModal')">İptal</button>
            </form>
        </div>
    </div>

    <!-- Yoklama Aç/Kapat Modal -->
    <div id="yoklamaModal" class="modal">
        <div class="modal-content">
            <h2>Yoklama Aç/Kapat</h2>
            <form id="yoklamaForm">
                <div class="form-group">
                    <label for="yoklamaEtkinlikSec">Etkinlik Seçin:</label>
                    <select id="yoklamaEtkinlikSec" class="form-control" required>
                        <option value="">Etkinlik seçiniz</option>
                        <option value="1">Etkinlik 1</option>
                        <option value="2">Etkinlik 2</option>
                        <option value="3">Etkinlik 3</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Mevcut Durum:</label>
                    <p id="yoklamaMevcutDurum" class="durum-text">Yoklama Kapalı</p>
                </div>
                <button type="button" class="btn" onclick="toggleYoklama()">Yoklama Aç/Kapat</button>
                <button type="button" class="btn btn-cancel" onclick="closeModal('yoklamaModal')">İptal</button>
            </form>
        </div>
    </div>

    <!-- Etkinlik Paylaş Modal -->
    <div id="paylasModal" class="modal">
        <div class="modal-content">
            <h2>Etkinlik Paylaş</h2>
            <form id="paylasForm">
                <div class="form-group">
                    <label for="paylasEtkinlikSec">Etkinlik Seçin:</label>
                    <select id="paylasEtkinlikSec" class="form-control" required>
                        <option value="">Etkinlik seçiniz</option>
                        <option value="1">Etkinlik 1</option>
                        <option value="2">Etkinlik 2</option>
                        <option value="3">Etkinlik 3</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="paylasKisaBilgi">Kısa Bilgi:</label>
                    <input type="text" id="paylasKisaBilgi" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="paylasAciklama">Açıklama:</label>
                    <textarea id="paylasAciklama" rows="4" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="paylasResim">Etkinlik Resmi:</label>
                    <input type="file" id="paylasResim" accept="image/*" class="form-control" required>
                    <div id="resimOnizleme" class="resim-onizleme"></div>
                </div>
                <button type="submit" class="btn">Paylaş</button>
                <button type="button" class="btn btn-cancel" onclick="closeModal('paylasModal')">İptal</button>
            </form>
        </div>
    </div>

    <!-- Etkinlik Başvurularını Listele Modal -->
    <div id="basvuruListeModal" class="modal">
        <div class="modal-content">
            <h2>Etkinlik Başvurularını Listele</h2>
            <form id="basvuruListeForm">
                <div class="form-group">
                    <label for="basvuruListeEtkinlikSec">Etkinlik Seçin:</label>
                    <select id="basvuruListeEtkinlikSec" class="form-control" required>
                        <option value="">Etkinlik seçiniz</option>
                        <option value="1">Etkinlik 1</option>
                        <option value="2">Etkinlik 2</option>
                        <option value="3">Etkinlik 3</option>
                    </select>
                </div>
                <div class="basvuru-listesi">
                    <table>
                        <thead>
                            <tr>
                                <th>Ad Soyad</th>
                                <th>Bölüm</th>
                                <th>Cep No</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody id="basvuruListesi">
                            <!-- Başvurular JavaScript ile doldurulacak -->
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('basvuruListeModal')">Kapat</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/js_etkinlik_islemleri.js') }}"></script>
</body>
</html>

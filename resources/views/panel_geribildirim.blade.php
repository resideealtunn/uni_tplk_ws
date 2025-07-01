<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denetim Geri Bildirimleri</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style_panels.css') }}">
    <link rel="stylesheet" href="{{ asset('css/panel_geribildirim.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        <a href="/uye_islemleri" class="menu-item">Üye İşlemleri</a>
        <a href="/panel_geribildirim" class="menu-item active">Denetim Geri Bildirimleri</a>
        <a href="javascript:void(0);" class="menu-item" onclick="document.getElementById('cikisForm').submit();">Çıkış</a>
        <form id="cikisForm" action="{{ route('cikis') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
<div class="content active">
    <div class="form-container">
        <h2>Denetim Geri Bildirimleri</h2>
        <h3 style="font-size:2rem; margin-bottom:18px;">Talep Etkinlik İşlemleri Geri Bildirimleri</h3>
        <table class="geri-bildirim-table">
            <thead>
                <tr>
                    <th>Etkinlik Başlığı</th>
                    <th>Etkinlik Başlangıç Tarihi</th>
                    <th>Etkinlik Bitiş Tarihi</th>
                    <th>Etkinlik Tanıtım Afişi</th>
                    <th>Etkinlik Detayları</th>
                    <th>Durum</th>
                    <th>Red Sebebi</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody id="etkinlikGeriBildirimBody"></tbody>
        </table>
        <h3 style="font-size:2rem; margin:32px 0 18px 0;">Gerçekleşen Etkinlik Geri Bildirimleri</h3>
        <table class="geri-bildirim-table">
            <thead>
                <tr>
                    <th>Etkinlik Başlığı</th>
                    <th>Etkinlik Başlangıç Tarihi</th>
                    <th>Etkinlik Bitiş Tarihi</th>
                    <th>Etkinlik Resmi</th>
                    <th>Etkinlik Detayları</th>
                    <th>Durum</th>
                    <th>Red Sebebi</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody id="gerceklesenEtkinlikBody"></tbody>
        </table>
        <h3 style="font-size:2rem; margin:32px 0 18px 0;">Üye Silme Geri Bildirimleri</h3>
        <table class="geri-bildirim-table">
            <thead>
                <tr>
                    <th>Öğrenci No</th>
                    <th>Ad</th>
                    <th>Soyad</th>
                    <th>Cep Tel</th>
                    <th>Fakülte</th>
                    <th>Bölüm</th>
                    <th>Üyelik Formu</th>
                    <th>Silinme Sebebi</th>
                </tr>
            </thead>
            <tbody id="uyeSilmeGeriBildirimBody"></tbody>
        </table>
        <h3 style="font-size:2rem; margin:32px 0 18px 0;">Web Arayüz İşlemleri Geri Bildirimleri</h3>
        <table class="geri-bildirim-table">
            <thead>
                <tr>
                    <th>Özellik</th>
                    <th>İçerik</th>
                    <th>Durum</th>
                    <th>Red Sebebi</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody id="geriBildirimBody"></tbody>
            <!-- JS hata vermesin diye görünmez boş tbody eklendi -->
            <tbody id="toplulukDurumuGeriBildirimBody" style="display:none;"></tbody>
        </table>
        <h3 style="font-size:2rem; margin:32px 0 18px 0;">Sosyal Medya İşlemleri</h3>
        <table class="geri-bildirim-table">
            <thead>
                <tr>
                    <th>Medya</th>
                    <th>İçerik</th>
                    <th>Durum</th>
                    <th>Red Sebebi</th>
                </tr>
            </thead>
            <tbody id="sosyalMedyaGeriBildirimBody"></tbody>
        </table>
    </div>
    <!-- Düzenleme Modalı: Web Arayüz -->
    <div id="editModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3>Bilgileri Düzenle</h3>
            <form id="editForm" enctype="multipart/form-data" onsubmit="return false;">
                <div class="modal-row">
                    <label>Logo</label>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <img id="modalLogoPreview" src="" alt="Logo" style="max-width:40px;max-height:40px;border-radius:5px;">
                        <input type="file" id="modalLogoInput" accept="image/*">
                    </div>
                </div>
                <div class="modal-row">
                    <label>Arkaplan</label>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <img id="modalArkaPreview" src="" alt="Arkaplan" style="max-width:40px;max-height:40px;border-radius:5px;">
                        <input type="file" id="modalArkaInput" accept="image/*">
                    </div>
                </div>
                <div class="modal-row">
                    <label>Slogan</label>
                    <input type="text" id="modalSlogan">
                </div>
                <div class="modal-row">
                    <label>Vizyon</label>
                    <textarea id="modalVizyon" rows="3" style="resize:vertical;"></textarea>
                </div>
                <div class="modal-row">
                    <label>Misyon</label>
                    <textarea id="modalMisyon" rows="3" style="resize:vertical;"></textarea>
                </div>
                <div class="modal-row">
                    <label>Red Sebebi</label>
                    <div id="modalRedSebebi" class="readonly-field"></div>
                </div>
                <div class="modal-row">
                    <label>Durum</label>
                    <div id="modalDurum" class="readonly-field"></div>
                </div>
                <div style="text-align:right;margin-top:18px;">
                    <button type="button" class="guncelle-btn" onclick="saveEdit()">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Düzenleme Modalı: Talep Etkinlik -->
    <div id="etkinlikEditModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeEtkinlikEditModal()">&times;</span>
            <h3>Etkinlik Bilgilerini Düzenle</h3>
            <form id="etkinlikEditForm" enctype="multipart/form-data" onsubmit="return false;">
                <div class="modal-row">
                    <label>Etkinlik Başlığı</label>
                    <input type="text" id="modalEtkinlikBaslik">
                </div>
                <div class="modal-row">
                    <label>Kısa Bilgi</label>
                    <input type="text" id="modalEtkinlikKisaBilgi">
                </div>
                <div class="modal-row">
                    <label>Açıklama</label>
                    <textarea id="modalEtkinlikAciklama" rows="3" style="resize:vertical;"></textarea>
                </div>
                <div class="modal-row">
                    <label>Etkinlik Tanıtım Afişi</label>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <img id="modalEtkinlikAfişPreview" src="" alt="Afiş" style="max-width:40px;max-height:40px;border-radius:5px;">
                        <input type="file" id="modalEtkinlikAfişInput" accept="image/*">
                    </div>
                </div>
                <div class="modal-row">
                    <label>Etkinlik Tarihi</label>
                    <input type="datetime-local" id="modalEtkinlikTarih">
                </div>
                <div class="modal-row">
                    <label>Red Sebebi</label>
                    <div id="modalEtkinlikRedSebebi" class="readonly-field"></div>
                </div>
                <div class="modal-row">
                    <label>Durum</label>
                    <div id="modalEtkinlikDurum" class="readonly-field"></div>
                </div>
                <div style="text-align:right;margin-top:18px;">
                    <button type="button" class="guncelle-btn" onclick="saveEtkinlikEdit()">Değişiklikleri Kaydet</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Düzenleme Modalı: Gerçekleşen Etkinlik -->
    <div id="gerceklesenEditModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeGerceklesenEditModal()">&times;</span>
            <h3>Gerçekleşen Etkinlik Bilgilerini Düzenle</h3>
            <form id="gerceklesenEditForm" enctype="multipart/form-data" onsubmit="return false;">
                <div class="modal-row">
                    <label>Etkinlik Başlığı</label>
                    <input type="text" id="modalGerceklesenBaslik">
                </div>
                <div class="modal-row">
                    <label>Etkinlik Tarihi</label>
                    <input type="datetime-local" id="modalGerceklesenTarih">
                </div>
                <div class="modal-row">
                    <label>Etkinlik Resmi</label>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <img id="modalGerceklesenResimPreview" src="" alt="Resim" style="max-width:40px;max-height:40px;border-radius:5px;">
                        <input type="file" id="modalGerceklesenResimInput" accept="image/*">
                    </div>
                </div>
                <div class="modal-row">
                    <label>Etkinlik Detayları</label>
                    <textarea id="modalGerceklesenDetay" rows="3" style="resize:vertical;"></textarea>
                </div>
                <div class="modal-row">
                    <label>Red Sebebi</label>
                    <div id="modalGerceklesenRedSebebi" class="readonly-field"></div>
                </div>
                <div class="modal-row">
                    <label>Durum</label>
                    <div id="modalGerceklesenDurum" class="readonly-field"></div>
                </div>
                <div style="text-align:right;margin-top:18px;">
                    <button type="button" class="guncelle-btn" onclick="saveGerceklesenEdit()">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Silinme Sebebi Modalı -->
    <div id="silinmeSebebiModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeSilinmeSebebiModal()">&times;</span>
            <h3>Silinme Sebebi</h3>
            <div id="modalSilinmeSebebiText" style="font-size:1.4rem; color:#003366; margin-top:18px; white-space:pre-line;"></div>
        </div>
    </div>
    <!-- Modal: Metin Detay -->
    <div id="textDetailModal" class="modal" style="display:none;">
        <div class="modal-content" style="max-width:500px;">
            <span class="close" onclick="closeTextDetailModal()">&times;</span>
            <h3 id="textDetailTitle" style="font-size:1.8rem;margin-bottom:10px;"></h3>
            <div id="textDetailContent" style="white-space:pre-line;word-break:break-word;"></div>
        </div>
    </div>
</div>
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
                @php
                    $sosyal = null;
                    if(session('t_id')) {
                        $sosyal = \DB::table('sosyal_medya')->where('t_id', session('t_id'))->first();
                    }
                @endphp
                {{-- Instagram --}}
                @if($sosyal && $sosyal->i_onay == 1 && $sosyal->instagram)
                    <a href="{{ $sosyal->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                @else
                    <span class="social-icon-disabled"><i class="fab fa-instagram"></i></span>
                @endif
                {{-- WhatsApp --}}
                @if($sosyal && $sosyal->w_onay == 1 && $sosyal->whatsapp)
                    <a href="{{ $sosyal->whatsapp }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                @else
                    <span class="social-icon-disabled"><i class="fab fa-whatsapp"></i></span>
                @endif
                {{-- LinkedIn --}}
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
<script src="{{ asset('js/panel_geribildirim.js') }}"></script>
<script>

function showImageModal(imgPath) {
    // Basit büyütme için yeni sekmede aç
    window.open('/' + imgPath, '_blank');
}

function editRow(idx) {
    const row = document.querySelector(`tr[data-index='${idx}']`);
    row.querySelector('.slogan').innerHTML = `<input type='text' value='${geriBildirimData[idx].slogan}' class='edit-slogan'>`;
    row.querySelector('.vizyon').innerHTML = `<input type='text' value='${geriBildirimData[idx].vizyon}' class='edit-vizyon'>`;
    row.querySelector('.misyon').innerHTML = `<input type='text' value='${geriBildirimData[idx].misyon}' class='edit-misyon'>`;
    row.querySelector('.red-sebebi').innerHTML = `<input type='text' value='${geriBildirimData[idx].redSebebi}' class='edit-red-sebebi'>`;
    row.querySelector('.duzenle-btn').style.display = 'none';
    row.querySelector('.guncelle-btn').style.display = 'inline-block';
}

function updateRow(idx) {
    const row = document.querySelector(`tr[data-index='${idx}']`);
    geriBildirimData[idx].slogan = row.querySelector('.edit-slogan').value;
    geriBildirimData[idx].vizyon = row.querySelector('.edit-vizyon').value;
    geriBildirimData[idx].misyon = row.querySelector('.edit-misyon').value;
    geriBildirimData[idx].redSebebi = row.querySelector('.edit-red-sebebi').value;
    renderTable();
}

document.getElementById('etkinlikEditForm').addEventListener('submit', function(e) {
    e.preventDefault();
});

function showTextDetailModal(title, content) {
    document.getElementById('textDetailTitle').textContent = title;
    document.getElementById('textDetailContent').textContent = content;
    document.getElementById('textDetailModal').style.display = 'flex';
}

function closeTextDetailModal() {
    document.getElementById('textDetailModal').style.display = 'none';
}
</script>
</body>
</html>

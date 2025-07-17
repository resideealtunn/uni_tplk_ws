// Modal işlemleri için genel fonksiyonlar
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'block';
    
    // Modal dışına tıklandığında kapatma özelliği
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal(modalId);
        }
    });
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Üye Listesi Modal
function showUyeListeModal() {
    showModal('uyeListeModal');
    getUyeListesi();
}

function getUyeListesi() {
    fetch('/uye-listesi')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('uyeListesi');
            tbody.innerHTML = '';
            data.forEach(uye => {
                let rolText = '-';
                if (uye.rol == 2) rolText = 'Başkan';
                else if (uye.rol == 3) rolText = 'Başkan Yardımcısı';
                else if (uye.rol == 6) rolText = 'Yönetim Başvuru';
                else if (uye.rol == 1) rolText = 'Üye';
                tbody.innerHTML += `
                    <tr>
                        <td>${uye.kay_tar || '-'}</td>
                        <td>${uye.numara || '-'}</td>
                        <td>${uye.isim} ${uye.soyisim}</td>
                        <td>${uye.tel || '-'}</td>
                        <td>${uye.fak_ad || '-'}</td>
                        <td>${uye.bol_ad || '-'}</td>
                        <td>${uye.eposta || '-'}</td>
                        <td>${uye.belge ? `<a href='/docs/kayit_belge/${uye.belge}' target='_blank'>Görüntüle</a>` : '-'}</td>
                        <td>${rolText}</td>
                    </tr>
                `;
            });
            var uyeSearchInput = document.getElementById('uyeSearchInput');
            if(uyeSearchInput) uyeSearchInput.value = '';
        });
}

function getBasvuruListesi() {
    fetch('/basvuru-listesi')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('basvuruListesi');
            tbody.innerHTML = '';
            (data.uyeler || []).forEach((uye, idx) => {
                tbody.innerHTML += `
                    <tr>
                        <td>${'Online'}</td>
                        <td>${uye.kay_tar || '-'}</td>
                        <td>${uye.numara || '-'}</td>
                        <td>${uye.isim} ${uye.soyisim}</td>
                        <td>${uye.tel || '-'}</td>
                        <td>${uye.fak_ad || '-'}</td>
                        <td>${uye.bol_ad || '-'}</td>
                        <td>${uye.belge ? `<a href='/docs/kayit_belge/${uye.belge}' target='_blank'>Görüntüle</a>` : '-'}</td>
                        <td>
                            <div class="basvuru-btn-group">
                                <button class="btn-onay" onclick="onaylaBasvuru('${uye.uye_id}')">Onayla</button>
                                <button class="btn-reddet" onclick="reddetBasvuru('${uye.uye_id}', ${idx})">Reddet</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            var basvuruSearchInput = document.getElementById('basvuruSearchInput');
            if(basvuruSearchInput) basvuruSearchInput.value = '';
        });
}

function showBasvuruListeModal() {
    showModal("basvuruListeModal");
    getBasvuruListesi();
}

let currentRedIndex = null;

function reddet(index) {
    currentRedIndex = index;
    document.getElementById('redSebepInput').value = '';
    showModal('redSebepModal');
}

function closeRedSebepModal() {
    document.getElementById('redSebepModal').style.display = 'none';
    currentRedIndex = null;
}

document.getElementById('redSebepForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const sebep = document.getElementById('redSebepInput').value.trim();
    if (!sebep) {
        alert('Lütfen red sebebini giriniz.');
        return;
    }
    // Burada backend'e red sebebiyle birlikte istek gönderebilirsin
    alert(`Başvuru ${currentRedIndex + 1} reddedildi. Sebep: ${sebep}`);
    closeRedSebepModal();
});

// Yeni üye veri yapısı örneği (email eklendi)
let uyeler = [
    {
        uyelikTuru: 'Aday',
        kayitSekli: 'Online',
        basvuruTarihi: '2025-03-15',
        ogrenciNo: '20214567',
        adSoyad: 'Ali Veli',
        cepTel: '05554443322',
        email: 'ali.veli@example.com',
        fakulte: 'Mühendislik',
        bolum: 'Bilgisayar Mühendisliği',
        uyelikFormu: '/public/uyelik_formu.pdf'
    },
    {
        uyelikTuru: 'Üye',
        kayitSekli: 'Yüz yüze',
        basvuruTarihi: '2024-11-02',
        ogrenciNo: '20219876',
        adSoyad: 'Ayşe Demir',
        cepTel: '05559998877',
        email: 'ayse.demir@example.com',
        fakulte: 'Fen Edebiyat',
        bolum: 'Matematik',
        uyelikFormu: '/public/uyelik_formu.pdf'
    }
];

function populateUyeGuncelleTable() {
    fetch('/uye-listesi')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('uyeGuncelleListesi');
            tbody.innerHTML = '';
            data.forEach(uye => {
                tbody.innerHTML += `
                    <tr>
                        <td>${uye.kay_tar || '-'}</td>
                        <td>${uye.numara || '-'}</td>
                        <td>${uye.isim} ${uye.soyisim}</td>
                        <td>${uye.tel || '-'}</td>
                        <td>${uye.eposta || '-'}</td>
                        <td>${uye.fak_ad || '-'}</td>
                        <td>${uye.bol_ad || '-'}</td>
                        <td>${uye.belge ? `<a href='/docs/kayit_belge/${uye.belge}' target='_blank'>Görüntüle</a>` : '-'}</td>
                        <td>
                            <button class="btn-duzenle" onclick="openDuzenleUyeModal('${uye.uye_id}', '${uye.ogr_id}', '${uye.tel || ''}', '${uye.eposta || ''}', '${uye.belge || ''}')\">Düzenle</button>
                        </td>
                    </tr>
                `;
            });
        });
}

function showUyeGuncelleModal() {
    populateUyeGuncelleTable();
    showModal('uyeGuncelleModal');
}

function searchUyeGuncelle() {
    const input = document.getElementById('guncelleSearchInput').value.toLowerCase();
    // Sadece sayısal değer kontrolü
    if (input && !/^\d+$/.test(input)) {
        return;
    }
    const rows = document.querySelectorAll('#uyeGuncelleListesi tr');
    rows.forEach(row => {
        const ogrenciNo = row.children[1].textContent.toLowerCase();
        row.style.display = ogrenciNo.includes(input) ? '' : 'none';
    });
}

function openDuzenleUyeModal(uye_id, ogr_id, tel, eposta, belge) {
    document.getElementById('duzenleUyeOgrNo').value = ogr_id;
    document.getElementById('duzenleUyeUyeId').value = uye_id;
    document.getElementById('duzenleUyeCepTel').value = tel;
    document.getElementById('duzenleUyeEmail').value = eposta;
    document.getElementById('duzenleUyeFormu').value = '';
    showModal('duzenleUyeModal');
}

document.getElementById('duzenleUyeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const ogr_id = document.getElementById('duzenleUyeOgrNo').value;
    const uye_id = document.getElementById('duzenleUyeUyeId').value;
    const tel = document.getElementById('duzenleUyeCepTel').value;
    const eposta = document.getElementById('duzenleUyeEmail').value;
    const belge = document.getElementById('duzenleUyeFormu').files[0];
    
    // Telefon numarası validasyonu
    if (tel && tel.trim() !== '') {
        // Sadece sayıları al
        const telDigits = tel.replace(/[^0-9]/g, '');
        
        if (telDigits.length !== 11) {
            alert('Telefon numarası 11 haneli olmalıdır.');
            return;
        }
        
        if (!telDigits.startsWith('0')) {
            alert('Telefon numarası 0 ile başlamalıdır.');
            return;
        }
    }
    
    // Email validasyonu
    if (eposta && eposta.trim() !== '') {
        if (!eposta.includes('@')) {
            alert('Email adresi @ işareti içermelidir.');
            return;
        }
        
        // Basit email format kontrolü
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(eposta)) {
            alert('Lütfen geçerli bir email adresi giriniz.');
            return;
        }
    }
    
    const formData = new FormData();
    formData.append('ogr_id', ogr_id);
    formData.append('uye_id', uye_id);
    formData.append('tel', tel);
    formData.append('eposta', eposta);
    if (belge) {
        formData.append('belge', belge);
    }
    fetch('/uye-guncelle', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Üye bilgileri güncellendi.');
            closeModal('duzenleUyeModal');
            populateUyeGuncelleTable();
        } else {
            alert('Güncelleme başarısız.');
        }
    })
    .catch(() => alert('Bir hata oluştu.'));
});

// Modalı gösterme fonksiyonu
function showYeniUyeModal() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('yeniUyeForm').reset();
    document.getElementById('yeniKayitSekli').value = 'yönetici';
    document.getElementById('yeniBasvuruTarihi').value = today;
    showModal('yeniUyeModal');
}

// Tabloya üye satırı ekleme
function tabloyaUyeEkle(uye) {
    const tbody = document.getElementById('uyeListesi');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${uye.kayitSekli}</td>
        <td>${uye.basvuruTarihi}</td>
        <td>${uye.ogrNo}</td>
        <td>${uye.adSoyad}</td>
        <td>${uye.cepTel}</td>
        <td>${uye.fakulte}</td>
        <td>${uye.bolum}</td>
        <td><a href="/public/uyelik_formu.pdf" target="_blank">Görüntüle</a></td>
        <td>${uye.onayDurumu || "Bekliyor"}</td>
        <td>${uye.ayrilis || "-"}</td>
    `;
    tbody.appendChild(tr);
}

// Sayfa yüklendiğinde örnek üyeleri tabloya yerleştir
window.addEventListener('DOMContentLoaded', () => {
    uyeler.forEach(tabloyaUyeEkle);
});

// TC Kimlik numarası validasyon fonksiyonu
function validateTCKimlik(input) {
    const value = input.value;
    const errorElement = document.getElementById('tcKimlikError');
    
    // Sadece sayı girişine izin ver
    input.value = value.replace(/[^0-9]/g, '');
    
    // 11 haneden fazla girilmesini engelle
    if (input.value.length > 11) {
        input.value = input.value.substring(0, 11);
    }
    
    // Validasyon kuralları
    if (input.value.length > 0) {
        if (input.value.length !== 11) {
            errorElement.textContent = 'TC kimlik numarası 11 haneli olmalıdır.';
            errorElement.style.display = 'block';
            return false;
        }
        
        if (input.value.startsWith('0')) {
            errorElement.textContent = 'TC kimlik numarası 0 ile başlayamaz.';
            errorElement.style.display = 'block';
            return false;
        }
        
        // TC kimlik numarası algoritma kontrolü
        if (!validateTCKimlikAlgorithm(input.value)) {
            errorElement.textContent = 'Geçersiz TC kimlik numarası.';
            errorElement.style.display = 'block';
            return false;
        }
        
        errorElement.style.display = 'none';
        return true;
    } else {
        errorElement.style.display = 'none';
        return true;
    }
}

// TC kimlik numarası algoritma kontrolü
function validateTCKimlikAlgorithm(tcKimlik) {
    if (tcKimlik.length !== 11) return false;
    
    // İlk hane 0 olamaz
    if (tcKimlik[0] === '0') return false;
    
    // Tüm haneler aynı olamaz
    if (tcKimlik.split('').every(char => char === tcKimlik[0])) return false;
    
    // 1, 3, 5, 7, 9. hanelerin toplamının 7 katından, 2, 4, 6, 8. hanelerin toplamı çıkartıldığında, elde edilen sonucun 10'a bölümünden kalan, 10. haneyi vermelidir
    let tekHaneToplam = 0;
    let ciftHaneToplam = 0;
    
    for (let i = 0; i < 9; i++) {
        if (i % 2 === 0) {
            tekHaneToplam += parseInt(tcKimlik[i]);
        } else {
            ciftHaneToplam += parseInt(tcKimlik[i]);
        }
    }
    
    const onuncuHane = (tekHaneToplam * 7 - ciftHaneToplam) % 10;
    if (onuncuHane !== parseInt(tcKimlik[9])) return false;
    
    // İlk 10 hanenin toplamının 10'a bölümünden kalan, 11. haneyi vermelidir
    let ilkOnHaneToplam = 0;
    for (let i = 0; i < 10; i++) {
        ilkOnHaneToplam += parseInt(tcKimlik[i]);
    }
    
    const onBirinciHane = ilkOnHaneToplam % 10;
    if (onBirinciHane !== parseInt(tcKimlik[10])) return false;
    
    return true;
}

// Telefon numarası validasyon fonksiyonu
function validateTelefon(input) {
    const value = input.value;
    const errorElement = document.getElementById('telefonError');
    
    // Sadece sayı girişine izin ver
    input.value = value.replace(/[^0-9]/g, '');
    
    // 11 haneden fazla girilmesini engelle
    if (input.value.length > 11) {
        input.value = input.value.substring(0, 11);
    }
    
    // Validasyon kuralları
    if (input.value.length > 0) {
        if (input.value.length !== 11) {
            errorElement.textContent = 'Telefon numarası 11 haneli olmalıdır.';
            errorElement.style.display = 'block';
            return false;
        }
        
        if (!input.value.startsWith('0')) {
            errorElement.textContent = 'Telefon numarası 0 ile başlamalıdır.';
            errorElement.style.display = 'block';
            return false;
        }
        
        errorElement.style.display = 'none';
        return true;
    } else {
        errorElement.style.display = 'none';
        return true;
    }
}

// Email validasyon fonksiyonu
function validateEmail(input) {
    const value = input.value;
    const errorElement = document.getElementById('emailError');
    
    // Validasyon kuralları
    if (value.length > 0) {
        if (!value.includes('@')) {
            errorElement.textContent = 'Email adresi @ işareti içermelidir.';
            errorElement.style.display = 'block';
            return false;
        }
        
        // Basit email format kontrolü
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            errorElement.textContent = 'Lütfen geçerli bir email adresi giriniz.';
            errorElement.style.display = 'block';
            return false;
        }
        
        errorElement.style.display = 'none';
        return true;
    } else {
        errorElement.style.display = 'none';
        return true;
    }
}

// Yeni üye formu gönderildiğinde çalışır
document.getElementById('yeniUyeForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const tcno = document.getElementById('yeniTcNo').value;
    const toplulukId = document.getElementById('topluluk_id').value;
    const basvuruTarihi = document.getElementById('yeniBasvuruTarihi').value;
    const pdfFile = document.getElementById('yeniUyelikFormu').files[0];
    
    // TC kimlik numarası validasyonu
    if (!validateTCKimlik(document.getElementById('yeniTcNo'))) {
        alert('Lütfen geçerli bir TC kimlik numarası giriniz.');
        return;
    }
    
    if (!pdfFile) {
        alert('Lütfen üyelik formu (PDF) yükleyiniz.');
        return;
    }
    
    const formData = new FormData();
    formData.append('tcno', tcno);
    formData.append('basvuru_tarihi', basvuruTarihi);
    formData.append('belge', pdfFile);
    formData.append('topluluk_id', toplulukId);
    fetch('/denetim/uye/ekle', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Yeni üye başarıyla eklendi.');
            closeModal('yeniUyeModal');
            document.getElementById('yeniUyeForm').reset();
            populateUyeGuncelleTable && populateUyeGuncelleTable();
        } else {
            alert('Hata: ' + (data.message || 'Üye eklenemedi.'));
        }
    })
    .catch(() => alert('Bir hata oluştu.'));
});

function populateUyeSilTable() {
    fetch('/uye-listesi')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('uyeSilListesi');
            tbody.innerHTML = '';
            data.forEach(uye => {
                tbody.innerHTML += `
                    <tr>
                        <td>${uye.kay_tar || '-'}</td>
                        <td>${uye.numara || '-'}</td>
                        <td>${uye.isim} ${uye.soyisim}</td>
                        <td>${uye.tel || '-'}</td>
                        <td>${uye.eposta || '-'}</td>
                        <td>${uye.fak_ad || '-'}</td>
                        <td>${uye.bol_ad || '-'}</td>
                        <td>${uye.belge ? `<a href='/docs/kayit_belge/${uye.belge}' target='_blank'>Görüntüle</a>` : '-'}</td>
                        <td><button class="btn btn-sil" onclick="openAyrilisSebepModal('${uye.uye_id}')">Sil</button></td>
                    </tr>
                `;
            });
        });
}

let currentSilUyeId = null;
function openAyrilisSebepModal(uye_id) {
    currentSilUyeId = uye_id;
    document.getElementById('ayrilisSebepInput').value = '';
    showModal('ayrilisSebepModal');
}
function closeAyrilisSebepModal() {
    document.getElementById('ayrilisSebepModal').style.display = 'none';
    currentSilUyeId = null;
}
document.getElementById('ayrilisSebepForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const sebep = document.getElementById('ayrilisSebepInput').value.trim();
    if (!sebep) {
        alert('Lütfen ayrılış sebebini giriniz.');
        return;
    }
    if (currentSilUyeId) {
        const formData = new FormData();
        formData.append('uye_id', currentSilUyeId);
        formData.append('ayrilis_sebebi', sebep);
        fetch('/uye-sil', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Üye başarıyla silindi.');
                closeAyrilisSebepModal();
                populateUyeSilTable();
            } else {
                alert('Silme işlemi başarısız.');
            }
        })
        .catch(() => alert('Bir hata oluştu.'));
    }
});

// Arama fonksiyonu
function searchUye() {
    var input = document.getElementById('uyeSearchInput').value.toLowerCase();
    var table = document.getElementById('uyeListesi');
    var rows = table.getElementsByTagName('tr');

    // Tablo satırlarını döngüyle kontrol et
    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName('td');
        if (cells.length > 0) {
            var ogrenciNo = cells[2].textContent.toLowerCase(); // Öğrenci No sütunu
            if (ogrenciNo.indexOf(input) > -1) {
                rows[i].style.display = ''; // Arama sonucu eşleşiyorsa satırı göster
            } else {
                rows[i].style.display = 'none'; // Eşleşmiyorsa satırı gizle
            }
        }
    }
}

function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Üye listesini al
    const table = document.getElementById('uyeListesi');
    const rows = table.getElementsByTagName('tr');

    // Tablo başlıkları
    let header = ['Kayıt Şekli', 'Başvuru Tarihi', 'Öğrenci No', 'Ad Soyad', 'Cep Tel', 'Fakülte', 'Bölüm', 'Durum', 'Ayrılış'];

    // PDF'e başlıkları ekle
    doc.text("Üye Listesi", 14, 10);
    doc.autoTable({ head: [header], html: table });

    // PDF'i kaydet
    doc.save('uye_listesi.pdf');
}

// Üye Listesi modalındaki tabloyu öğrenci no ile filtrele
function searchUyeListesi() {
    var input = document.getElementById('uyeSearchInput').value.toLowerCase();
    // Sadece sayısal değer kontrolü
    if (input && !/^\d+$/.test(input)) {
        return;
    }
    var table = document.getElementById('uyeListesi');
    var rows = table.getElementsByTagName('tr');
    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName('td');
        if (cells.length > 0) {
            // Öğrenci No sütunu: 2. index (0: başvuru tarihi, 1: öğrenci no)
            var ogrenciNo = cells[1].textContent.toLowerCase();
            if (ogrenciNo.indexOf(input) > -1) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
}

// Başvuru Listesi modalındaki tabloyu öğrenci no ile filtrele
function searchBasvuruListesi() {
    var input = document.getElementById('basvuruSearchInput').value.toLowerCase();
    // Sadece sayısal değer kontrolü
    if (input && !/^\d+$/.test(input)) {
        return;
    }
    var table = document.getElementById('basvuruListesi');
    var rows = table.getElementsByTagName('tr');
    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName('td');
        if (cells.length > 0) {
            var ogrenciNo = cells[2].textContent.toLowerCase(); // 3. sütun öğrenci no
            if (ogrenciNo.indexOf(input) > -1) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
}

// Yönetim Başvuruları Modalı
function showYonetimBasvurulariModal() {
    showModal('yonetimBasvurulariModal');
    populateYonetimBasvurulariTable();
}

const ornekYonetimBasvurulari = [
    {
        basvuruTarihi: '2024-05-01',
        ogrenciNo: '2023001',
        adSoyad: 'Zeynep Kılıç',
        cepTel: '0532 111 2233',
        email: 'zeynep.kilic@example.com',
        fakulte: 'İktisadi ve İdari Bilimler',
        bolum: 'İşletme',
        sinif: '3',
        niyetMetni: 'Yönetim kurulunda yer almak istiyorum çünkü liderlik yeteneklerimi geliştirmek ve topluluğa katkı sağlamak istiyorum.'
    },
    {
        basvuruTarihi: '2024-05-02',
        ogrenciNo: '2023002',
        adSoyad: 'Mehmet Can',
        cepTel: '0533 222 3344',
        email: 'mehmet.can@example.com',
        fakulte: 'Mühendislik',
        bolum: 'Elektrik Mühendisliği',
        sinif: '2',
        niyetMetni: 'Topluluğun projelerinde aktif rol almak ve yeni fikirler üretmek için başvuruyorum.'
    }
];

function populateYonetimBasvurulariTable() {
    fetch('/yonetim-basvurulari')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('yonetimBasvurulariListesi');
            tbody.innerHTML = '';
            data.forEach((uye, idx) => {
                tbody.innerHTML += `
                    <tr>
                        <td>${uye.kay_tar || '-'}</td>
                        <td>${uye.numara || '-'}</td>
                        <td>${uye.isim} ${uye.soyisim}</td>
                        <td>${uye.tel || '-'}</td>
                        <td>${uye.eposta || '-'}</td>
                        <td>${uye.fak_ad || '-'}</td>
                        <td>${uye.bol_ad || '-'}</td>
                        <td>${uye.sınıf || '-'}</td>
                        <td>${uye.belge ? `<a href='/docs/kayit_belge/${uye.belge}' target='_blank'>Görüntüle</a>` : '-'}</td>
                        <td><button class="btn" style="padding:2px 8px;font-size:13px;" onclick="showNiyetMetniModalDinamik('${uye.niyet_metni ? encodeURIComponent(uye.niyet_metni) : ''}')">Görüntüle</button></td>
                    </tr>
                `;
            });
        });
}

function showNiyetMetniModalDinamik(niyetMetni) {
    document.getElementById('niyetMetniIcerik').textContent = decodeURIComponent(niyetMetni);
    showModal('niyetMetniModal');
}

function searchYonetimBasvurulari() {
    const input = document.getElementById('yonetimBasvuruSearchInput').value.toLowerCase();
    // Sadece sayısal değer kontrolü
    if (input && !/^\d+$/.test(input)) {
        return;
    }
    const rows = document.querySelectorAll('#yonetimBasvurulariListesi tr');
    rows.forEach(row => {
        const ogrenciNo = row.children[1].textContent.toLowerCase();
        row.style.display = ogrenciNo.includes(input) ? '' : 'none';
    });
}

function showUyeSilModal() {
    populateUyeSilTable();
    showModal('uyeSilModal');
}

function searchUyeSil() {
    const input = document.getElementById('silSearchInput').value.toLowerCase();
    // Sadece sayısal değer kontrolü
    if (input && !/^\d+$/.test(input)) {
        return;
    }
    const rows = document.querySelectorAll('#uyeSilListesi tr');
    rows.forEach(row => {
        const ogrenciNo = row.children[1].textContent.toLowerCase();
        row.style.display = ogrenciNo.includes(input) ? '' : 'none';
    });
}

function showSilinenUyelerModal() {
    fetch('/silinen-uyeler')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('silinenUyelerListesi');
            tbody.innerHTML = '';
            data.forEach(uye => {
                let durumText = '';
                let sebepText = '';
                if (uye.rol == 5) {
                    durumText = 'Silinen Üye';
                    sebepText = uye.silinme_sebebi || '';
                } else if (uye.rol == 7) {
                    durumText = 'Reddedilen Başvuru';
                    sebepText = uye.red_sebebi || '';
                }
                let sebepCell = sebepText
                    ? `<button class='btn btn-duzenle' style='padding:4px 12px;font-size:13px;' onclick="showSebepPopup(this, '${encodeURIComponent(sebepText)}')">Görüntüle</button>`
                    : `<button class='btn btn-duzenle' style='padding:4px 12px;font-size:13px;' disabled>Görüntüle</button>`;
                tbody.innerHTML += `
                    <tr>
                        <td>${uye.kay_tar || '-'}</td>
                        <td>${uye.numara || '-'}</td>
                        <td>${uye.isim} ${uye.soyisim}</td>
                        <td>${uye.tel || '-'}</td>
                        <td>${uye.eposta || '-'}</td>
                        <td>${uye.fak_ad || '-'}</td>
                        <td>${uye.bol_ad || '-'}</td>
                        <td>${uye.belge ? `<a href='/docs/kayit_belge/${uye.belge}' target='_blank'>Görüntüle</a>` : '-'}</td>
                        <td>${durumText}</td>
                        <td>${sebepCell}</td>
                    </tr>
                `;
            });
        });
    showModal('silinenUyelerModal');
}

// Sebep popup fonksiyonu
function showSebepPopup(el, sebep) {
    sebep = decodeURIComponent(sebep);
    // Önce varsa eski popup'ı kaldır
    const eski = document.getElementById('sebepPopup');
    if (eski) eski.remove();
    // Popup div'i oluştur
    const popup = document.createElement('div');
    popup.id = 'sebepPopup';
    popup.style.position = 'fixed';
    popup.style.zIndex = 9999;
    popup.style.background = '#fff';
    popup.style.border = '1px solid #3498db';
    popup.style.borderRadius = '8px';
    popup.style.padding = '18px 22px';
    popup.style.boxShadow = '0 4px 16px rgba(0,0,0,0.18)';
    popup.style.maxWidth = '600px';
    popup.style.wordBreak = 'break-word';
    popup.style.top = (window.event.clientY + 20) + 'px';
    popup.style.left = (window.event.clientX - 100) + 'px';
    popup.innerHTML = `<div style='font-size:15px;white-space:pre-line;'>${sebep}</div><div style='text-align:right;margin-top:10px;'><button onclick="document.getElementById('sebepPopup').remove()" class='btn btn-cancel' style='padding:4px 14px;font-size:13px;'>Kapat</button></div>`;
    document.body.appendChild(popup);
    // Dışarı tıklanınca kapansın
    setTimeout(() => {
        document.addEventListener('mousedown', sebepPopupCloseListener);
    }, 100);
}
function sebepPopupCloseListener(e) {
    const popup = document.getElementById('sebepPopup');
    if (popup && !popup.contains(e.target)) {
        popup.remove();
        document.removeEventListener('mousedown', sebepPopupCloseListener);
    }
}

function showUyeMesajModal() {
    populateUyeMesajTable();
    showModal('uyeMesajModal');
}

function populateUyeMesajTable() {
    fetch('/uye-mesajlari')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('uyeMesajListesi');
            tbody.innerHTML = '';
            data.forEach((item, idx) => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.numara || '-'}</td>
                        <td>${item.isim || '-'}</td>
                        <td>${item.soyisim || '-'}</td>
                        <td>${item.tel || '-'}</td>
                        <td>${item.eposta || '-'}</td>
                        <td>${item.fak_ad || '-'}</td>
                        <td>${item.bol_ad || '-'}</td>
                        <td><button class="btn" onclick="showMesajGoruntuleModal('${encodeURIComponent(item.mesaj)}')">Görüntüle</button></td>
                        <td><button class="btn" style="background:#dc3545;color:#fff;" onclick="openMesajSilModal(${item.mesaj_id})"><i class='fa fa-trash'></i> Sil</button></td>
                    </tr>
                `;
            });
        });
}

function showMesajGoruntuleModal(mesaj) {
    document.getElementById('mesajIcerik').textContent = decodeURIComponent(mesaj);
    showModal('mesajGoruntuleModal');
}

let currentMesajId = null;
function openMesajSilModal(mesaj_id) {
    currentMesajId = mesaj_id;
    showModal('mesajSilModal');
}

document.getElementById('mesajSilOnayBtn').addEventListener('click', function() {
    if (currentMesajId) {
        fetch('/mesaj-sil', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ mesaj_id: currentMesajId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Mesaj başarıyla silindi.');
                closeModal('mesajSilModal');
                populateUyeMesajTable();
            } else {
                alert('Silme işlemi başarısız.');
            }
        })
        .catch(() => alert('Bir hata oluştu.'));
    }
});

function onaylaBasvuru(uye_id) {
    if (!confirm('Başvuruyu onaylamak istediğinize emin misiniz?')) return;
    const formData = new FormData();
    formData.append('uye_id', uye_id);
    fetch('/denetim/uye/basvuru/onayla', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Başvuru onaylandı.');
            getBasvuruListesi();
        } else {
            alert('Onaylama başarısız.');
        }
    })
    .catch(() => alert('Bir hata oluştu.'));
}

let currentRedBasvuruId = null;
function reddetBasvuru(uye_id, idx) {
    currentRedBasvuruId = uye_id;
    document.getElementById('redSebepInput').value = '';
    showModal('redSebepModal');
}

// Red sebep formunu submit edince başvuruyu reddet
const redSebepForm = document.getElementById('redSebepForm');
if (redSebepForm) {
    redSebepForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const sebep = document.getElementById('redSebepInput').value.trim();
        if (!sebep) {
            alert('Lütfen red sebebini giriniz.');
            return;
        }
        if (currentRedBasvuruId) {
            const formData = new FormData();
            formData.append('uye_id', currentRedBasvuruId);
            formData.append('red_sebebi', sebep);
            fetch('/denetim/uye/basvuru/reddet', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Başvuru reddedildi.');
                    closeRedSebepModal();
                    getBasvuruListesi();
                } else {
                    alert('Reddetme başarısız.');
                }
            })
            .catch(() => alert('Bir hata oluştu.'));
        }
    });
}

// Silinen üyeler arama fonksiyonu
function searchSilinenUyeler() {
    const input = document.getElementById('silinenUyelerSearchInput').value.toLowerCase();
    // Sadece sayısal değer kontrolü
    if (input && !/^\d+$/.test(input)) {
        return;
    }
    const rows = document.querySelectorAll('#silinenUyelerListesi tr');
    rows.forEach(row => {
        const ogrenciNo = row.children[1].textContent.toLowerCase();
        row.style.display = ogrenciNo.includes(input) ? '' : 'none';
    });
}

// Üye mesajları arama fonksiyonu
function searchUyeMesajlar() {
    const input = document.getElementById('uyeMesajSearchInput').value.toLowerCase();
    // Sadece sayısal değer kontrolü
    if (input && !/^\d+$/.test(input)) {
        return;
    }
    const rows = document.querySelectorAll('#uyeMesajListesi tr');
    rows.forEach(row => {
        const ogrenciNo = row.children[0].textContent.toLowerCase();
        row.style.display = ogrenciNo.includes(input) ? '' : 'none';
    });
}

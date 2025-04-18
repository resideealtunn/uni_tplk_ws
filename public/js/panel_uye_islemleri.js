// Modal işlemleri için genel fonksiyonlar
function showModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
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
    // Burada API'den üye listesini çekebilirsiniz
    // Şimdilik örnek veriler kullanıyoruz
    const ornekUyeler = [
        {
            kayitSekli: 'Online',
            basvuruTarihi: '2024-03-01',
            ogrenciNo: '2024001',
            adSoyad: 'Ahmet Yılmaz',
            cepTel: '0532 123 4567',
            fakulte: 'Mühendislik Fakültesi',
            bolum: 'Bilgisayar Mühendisliği',
            onayDurumu: 'Onaylandı',
            ayrilis: '-'
        },
        {
            kayitSekli: 'Form',
            basvuruTarihi: '2024-03-02',
            ogrenciNo: '2024002',
            adSoyad: 'Ayşe Demir',
            cepTel: '0533 987 6543',
            fakulte: 'Mühendislik Fakültesi',
            bolum: 'Elektrik-Elektronik Mühendisliği',
            onayDurumu: 'Beklemede',
            ayrilis: '-'
        },
        {
            kayitSekli: 'Online',
            basvuruTarihi: '2024-02-15',
            ogrenciNo: '2024003',
            adSoyad: 'Mehmet Kaya',
            cepTel: '0542 456 7890',
            fakulte: 'Mühendislik Fakültesi',
            bolum: 'Makine Mühendisliği',
            onayDurumu: 'Ayrıldı',
            ayrilis: '2024-03-10 (Mezuniyet)'
        }
    ];

    const uyeListesi = document.getElementById('uyeListesi');
    uyeListesi.innerHTML = '';

    ornekUyeler.forEach(uye => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${uye.kayitSekli}</td>
            <td>${uye.basvuruTarihi}</td>
            <td>${uye.ogrenciNo}</td>
            <td>${uye.adSoyad}</td>
            <td>${uye.cepTel}</td>
            <td>${uye.fakulte}</td>
            <td>${uye.bolum}</td>
            <td><a href="/public/uyelik_formu.pdf" target="_blank">Görüntüle</a></td>
            <td class="durum-${uye.onayDurumu.toLowerCase()}">${uye.onayDurumu}</td>
            <td>${uye.ayrilis}</td>
        `;
        uyeListesi.appendChild(tr);
    });
}

function showBasvuruListeModal() {
    document.getElementById("basvuruListeModal").style.display = "block";

    // Geçici örnek veri
    const basvurular = [
        {
            kayitSekli: "Online",
            basvuruTarihi: "2025-04-01",
            ogrenciNo: "2021123456",
            adSoyad: "Ahmet Yılmaz",
            cepTel: "0532 123 4567",
            fakulte: "Mühendislik",
            bolum: "Bilgisayar Mühendisliği"
        },
        // İsteğe göre daha fazla eklenebilir
    ];

    const tbody = document.getElementById("basvuruListesi");
    tbody.innerHTML = "";

    basvurular.forEach((uye, index) => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${uye.kayitSekli}</td>
            <td>${uye.basvuruTarihi}</td>
            <td>${uye.ogrenciNo}</td>
            <td>${uye.adSoyad}</td>
            <td>${uye.cepTel}</td>
            <td>${uye.fakulte}</td>
            <td>${uye.bolum}</td>
            <td><a href="/public/uyelik_formu.pdf" target="_blank">Görüntüle</a></td>
            <td>
                <button class="btn btn-onay" onclick="onayla(${index})">Onayla</button>
                <button class="btn btn-reddet" onclick="reddet(${index})">Reddet</button>
            </td>
        `;

        tbody.appendChild(row);
    });
}

function onayla(index) {
    alert(`Başvuru ${index + 1} onaylandı.`);
    // Burada backend'e istek gönderebilirsin
}

function reddet(index) {
    alert(`Başvuru ${index + 1} reddedildi.`);
    // Burada backend'e istek gönderebilirsin
}

const uyeler = [
    {
        uyelikTuru: 'Aday',
        kayitSekli: 'Online',
        basvuruTarihi: '2025-03-15',
        ogrenciNo: '20214567',
        adSoyad: 'Ali Veli',
        cepTel: '05554443322',
        fakulte: 'Mühendislik',
        bolum: 'Bilgisayar Mühendisliği'
    },
    {
        uyelikTuru: 'Üye',
        kayitSekli: 'Yüz yüze',
        basvuruTarihi: '2024-11-02',
        ogrenciNo: '20219876',
        adSoyad: 'Ayşe Demir',
        cepTel: '05559998877',
        fakulte: 'Fen Edebiyat',
        bolum: 'Matematik'
    }
];

function showUyeGuncelleModal() {
    populateUyeTable();
    const modal = document.getElementById('uyeGuncelleModal');
    modal.style.display = 'block';
    
    // Tablo genişliğini ayarla
    const table = modal.querySelector('table');
    if (table) {
        table.style.width = '100%';
        table.style.tableLayout = 'fixed';
    }
}

function populateUyeTable() {
    const tbody = document.getElementById('uyeGuncelleListesi');
    tbody.innerHTML = '';
    uyeler.forEach(uye => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${uye.uyelikTuru}</td>
            <td>${uye.kayitSekli}</td>
            <td>${uye.basvuruTarihi}</td>
            <td>${uye.ogrenciNo}</td>
            <td>${uye.adSoyad}</td>
            <td>${uye.cepTel}</td>
            <td>${uye.fakulte}</td>
            <td>${uye.bolum}</td>
            <td><a href="/public/uyelik_formu.pdf" target="_blank">Görüntüle</a></td>
            <td><button class="btn-duzenle" onclick="editUye('${uye.ogrenciNo}')">Düzenle</button></td>
        `;
        tbody.appendChild(tr);
    });
}

function searchUye() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#uyeGuncelleListesi tr');
    rows.forEach(row => {
        const ogrenciNo = row.children[3].textContent.toLowerCase();
        row.style.display = ogrenciNo.includes(input) ? '' : 'none';
    });
}

function editUye(ogrenciNo) {
    const uye = uyeler.find(u => u.ogrenciNo === ogrenciNo);
    if (uye) {
        document.getElementById('duzenleOgrenciNo').value = uye.ogrenciNo;
        document.getElementById('duzenleKayitSekli').value = uye.kayitSekli;
        document.getElementById('duzenleBasvuruTarihi').value = uye.basvuruTarihi;
        document.getElementById('duzenleAdSoyad').value = uye.adSoyad;
        document.getElementById('duzenleCepTel').value = uye.cepTel;
        document.getElementById('duzenleFakulte').value = uye.fakulte;
        document.getElementById('duzenleBolum').value = uye.bolum;
        // PDF dosyası için özel işlem yapmıyoruz, kullanıcı yeni dosya seçebilir

        document.getElementById('duzenleFormModal').style.display = 'block';
    }
}

document.getElementById('duzenleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const no = document.getElementById('duzenleOgrenciNo').value;
    const index = uyeler.findIndex(u => u.ogrenciNo === no);
    
    if (index !== -1) {
        const formData = new FormData();
        const pdfFile = document.getElementById('duzenleUyelikFormu').files[0];
        
        if (pdfFile) {
            formData.append('uyelik_formu', pdfFile);
            // Burada PDF dosyasını sunucuya yükleme işlemi yapılabilir
            // Örnek: fetch('/api/upload-pdf', { method: 'POST', body: formData })
        }

        uyeler[index] = {
            kayitSekli: document.getElementById('duzenleKayitSekli').value,
            basvuruTarihi: document.getElementById('duzenleBasvuruTarihi').value,
            ogrenciNo: no,
            adSoyad: document.getElementById('duzenleAdSoyad').value,
            cepTel: document.getElementById('duzenleCepTel').value,
            fakulte: document.getElementById('duzenleFakulte').value,
            bolum: document.getElementById('duzenleBolum').value,
        };

        populateUyeTable();
        closeModal('duzenleFormModal');
        alert('Üye bilgileri güncellendi.');
    }
});

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

// Modalı gösterme fonksiyonu
function showYeniUyeModal() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('yeniUyeForm').reset();
    document.getElementById('yeniKayitSekli').value = 'yönetici';
    document.getElementById('yeniBasvuruTarihi').value = today;
    document.getElementById('yeniUyeModal').style.display = 'block';
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

// Yeni üye formu gönderildiğinde çalışır
document.getElementById('yeniUyeForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData();
    const pdfFile = document.getElementById('yeniUyelikFormu').files[0];
    
    if (pdfFile) {
        formData.append('uyelik_formu', pdfFile);
        // Burada PDF dosyasını sunucuya yükleme işlemi yapılabilir
        // Örnek: fetch('/api/upload-pdf', { method: 'POST', body: formData })
    }

    const yeniUye = {
        kayitSekli: document.getElementById('yeniKayitSekli').value,
        basvuruTarihi: document.getElementById('yeniBasvuruTarihi').value,
        ogrenciNo: document.getElementById('yeniOgrNo').value,
        adSoyad: document.getElementById('yeniAdSoyad').value,
        cepTel: document.getElementById('yeniCepTel').value,
        fakulte: document.getElementById('yeniFakulte').value,
        bolum: document.getElementById('yeniBolum').value,
        onayDurumu: "Bekliyor",
        ayrilis: "-"
    };

    uyeler.push(yeniUye);
    tabloyaUyeEkle(yeniUye);

    closeModal('yeniUyeModal');
    alert("Yeni üye başarıyla eklendi.");
});

// Üye silme sayfasını açarken üyeleri listeleme
function showUyeSilModal() {
    const uyeSilListesi = document.getElementById('uyeSilListesi');
    uyeSilListesi.innerHTML = ''; // Önceki listeyi temizle

    // Üyeleri tabloya ekleyelim
    uyeler.forEach(uye => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${uye.kayitSekli}</td>
            <td>${uye.basvuruTarihi}</td>
            <td>${uye.ogrenciNo}</td>
            <td>${uye.adSoyad}</td>
            <td>${uye.cepTel}</td>
            <td>${uye.fakulte}</td>
            <td>${uye.bolum}</td>
            <td><a href="/public/uyelik_formu.pdf" target="_blank">Görüntüle</a></td>
            <td>${uye.onayDurumu}</td>
            <td>
                <select class="ayrilisSebebi" data-ogrNo="${uye.ogrenciNo}">
                    <option value="">Seçiniz</option>
                    <option value="Disiplin Suçu">Disiplin Suçu</option>
                    <option value="Mezuniyet">Mezuniyet</option>
                    <option value="Diğer">Diğer</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-sil" onclick="silUye('${uye.ogrenciNo}')">Sil</button></td>
        `;
        uyeSilListesi.appendChild(row);
    });

    // Modali aç
    document.getElementById('uyeSilModal').style.display = 'block';
}

function silUye(ogrNo) {
    const ayrilisSebebi = document.querySelector(`select[data-ogrNo="${ogrNo}"]`).value;
    
    if (!ayrilisSebebi) {
        alert('Lütfen ayrılış sebebini seçiniz.');
        return;
    }

    // Silme işlemi için örnek: Öğrenci numarasına göre üyeyi bul ve listeden çıkar
    uyeler = uyeler.filter(uye => uye.ogrenciNo !== ogrNo);
    alert(`${ogrNo} numaralı üye "${ayrilisSebebi}" sebebiyle silindi.`);
    showUyeSilModal(); // Listeyi tekrar güncelle
}

function searchUyeSil() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const filteredUyeler = uyeler.filter(uye => uye.ogrenciNo.includes(searchInput));

    const uyeSilListesi = document.getElementById('uyeSilListesi');
    uyeSilListesi.innerHTML = '';

    filteredUyeler.forEach(uye => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${uye.kayitSekli}</td>
            <td>${uye.basvuruTarihi}</td>
            <td>${uye.ogrenciNo}</td>
            <td>${uye.adSoyad}</td>
            <td>${uye.cepTel}</td>
            <td>${uye.fakulte}</td>
            <td>${uye.bolum}</td>
            <td><a href="/public/uyelik_formu.pdf" target="_blank">Görüntüle</a></td>
            <td>${uye.onayDurumu}</td>
            <td>
                <select class="ayrilisSebebi" data-ogrNo="${uye.ogrenciNo}">
                    <option value="">Seçiniz</option>
                    <option value="Disiplin Suçu">Disiplin Suçu</option>
                    <option value="Mezuniyet">Mezuniyet</option>
                    <option value="Diğer">Diğer</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-sil" onclick="silUye('${uye.ogrenciNo}')">Sil</button></td>
        `;
        uyeSilListesi.appendChild(row);
    });
}

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

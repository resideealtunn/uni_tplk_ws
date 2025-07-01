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
    document.getElementById("basvuruListeModal").style.display = "block";
    getBasvuruListesi();
}

let currentRedIndex = null;

function reddet(index) {
    currentRedIndex = index;
    document.getElementById('redSebepInput').value = '';
    document.getElementById('redSebepModal').style.display = 'block';
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
    document.getElementById('uyeGuncelleModal').style.display = 'block';
}

function searchUyeGuncelle() {
    const input = document.getElementById('guncelleSearchInput').value.toLowerCase();
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
    document.getElementById('duzenleUyeModal').style.display = 'block';
}

document.getElementById('duzenleUyeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const ogr_id = document.getElementById('duzenleUyeOgrNo').value;
    const uye_id = document.getElementById('duzenleUyeUyeId').value;
    const tel = document.getElementById('duzenleUyeCepTel').value;
    const eposta = document.getElementById('duzenleUyeEmail').value;
    const belge = document.getElementById('duzenleUyeFormu').files[0];
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
    const tcno = document.getElementById('yeniTcNo').value;
    const toplulukId = document.getElementById('topluluk_id').value;
    const basvuruTarihi = document.getElementById('yeniBasvuruTarihi').value;
    const pdfFile = document.getElementById('yeniUyelikFormu').files[0];
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
            alert('Yeni üye başarıyla eklendi.');
            closeModal('yeniUyeModal');
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
    document.getElementById('ayrilisSebepModal').style.display = 'block';
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
    const rows = document.querySelectorAll('#yonetimBasvurulariListesi tr');
    rows.forEach(row => {
        const ogrenciNo = row.children[1].textContent.toLowerCase();
        row.style.display = ogrenciNo.includes(input) ? '' : 'none';
    });
}

function showUyeSilModal() {
    populateUyeSilTable();
    document.getElementById('uyeSilModal').style.display = 'block';
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
    document.getElementById('silinenUyelerModal').style.display = 'block';
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
    document.getElementById('redSebepModal').style.display = 'block';
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

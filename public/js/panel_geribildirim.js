// Denetim Geri Bildirimleri sayfası için JS

// --- Etkinlik Geri Bildirim Tablosu ---
let etkinlikEditData = [];
let etkinlikEditIndex = null;
let tempEtkinlikAfiş = null;
let etkinlikShowAll = false;

function renderEtkinlikGeriBildirimTable() {
    fetch('/panel/geribildirim/talep-etkinlik')
        .then(response => response.json())
        .then(data => {
            etkinlikEditData = data;
            const tbody = document.getElementById('etkinlikGeriBildirimBody');
            tbody.innerHTML = '';
            let showData = etkinlikShowAll ? data : data.slice(0, 5);
            showData.forEach((item, idx) => {
                let durum = '';
                if (item.talep_onay == 0) durum = 'Reddedildi';
                else if (item.talep_onay == 1) durum = 'Onaylandı';
                else if (item.talep_onay == 2) durum = 'Beklemede';
                else if (item.talep_onay == 3) durum = 'Gerçekleşti';
                else durum = 'Bilinmiyor';
                
                let duzenleBtn = item.talep_onay == 0 ? `<button class='duzenle-btn' onclick='openEtkinlikEditModal(${idx})'>Düzenle</button>` : '';
                let redSebep = item.talep_red || '';
                let redHtml = '';
                if (redSebep.length > 30) {
                    redHtml = `<span style='cursor:pointer;color:#c00;text-decoration:underline;' onclick=\"showTextDetailModal('Red Sebebi', '${redSebep.replace(/'/g, "&#39;").replace(/"/g, '&quot;')}')\">${redSebep.substring(0,30)}...</span>`;
                } else if (redSebep) {
                    redHtml = `<span style='cursor:pointer;color:#c00;text-decoration:underline;' onclick=\"showTextDetailModal('Red Sebebi', '${redSebep.replace(/'/g, "&#39;").replace(/"/g, '&quot;')}')\">${redSebep}</span>`;
                } else {
                    redHtml = '';
                }
                let detayBtn = `<button class='duzenle-btn' style='background:#217dbb' onclick=\"showEtkinlikDetay('${item.bilgi.replace(/'/g, "&#39;").replace(/"/g, '&quot;')}', '${(item.metin||'').replace(/'/g, "&#39;").replace(/"/g, '&quot;')}')\">Görüntüle</button>`;
                let imgHtml = `<img src="/images/etkinlik/${item.gorsel}" alt="Afiş" class="table-img" style="cursor:pointer;" onclick="showImagePopup('/images/etkinlik/${item.gorsel}')">`;
                tbody.innerHTML += `
                <tr>
                    <td>${item.isim}</td>
                    <td>${item.tarih.replace('T', ' ').substring(0,16)}</td>
                    <td>${item.bitis_tarihi ? item.bitis_tarihi.replace('T', ' ').substring(0,16) : '-'}</td>
                    <td>${imgHtml}</td>
                    <td>${detayBtn}</td>
                    <td>${durum}</td>
                    <td>${redHtml}</td>
                    <td>${duzenleBtn}</td>
                </tr>
                `;
            });
            // Daha fazla/az göster butonları
            const btnId = 'etkinlikDahaFazlaBtn';
            let btn = document.getElementById(btnId);
            if (btn) btn.remove();
            if (!etkinlikShowAll && data.length > 5) {
                const showMoreBtn = document.createElement('button');
                showMoreBtn.id = btnId;
                showMoreBtn.className = 'duzenle-btn';
                showMoreBtn.textContent = 'Daha Fazla Göster';
                showMoreBtn.style.margin = '10px auto 0 auto';
                showMoreBtn.onclick = function() {
                    etkinlikShowAll = true;
                    renderEtkinlikGeriBildirimTable();
                };
                tbody.parentElement.appendChild(showMoreBtn);
            } else if (etkinlikShowAll && data.length > 5) {
                const showLessBtn = document.createElement('button');
                showLessBtn.id = btnId;
                showLessBtn.className = 'duzenle-btn';
                showLessBtn.textContent = 'Daha Az Göster';
                showLessBtn.style.margin = '10px auto 0 auto';
                showLessBtn.onclick = function() {
                    etkinlikShowAll = false;
                    renderEtkinlikGeriBildirimTable();
                };
                tbody.parentElement.appendChild(showLessBtn);
            }
        });
}

function openEtkinlikEditModal(idx) {
    etkinlikEditIndex = idx;
    tempEtkinlikAfiş = null;
    const data = etkinlikEditData[idx];
    document.getElementById('modalEtkinlikBaslik').value = data.isim;
    document.getElementById('modalEtkinlikKisaBilgi').value = data.bilgi;
    document.getElementById('modalEtkinlikAciklama').value = data.metin || '';
    document.getElementById('modalEtkinlikTarih').value = data.tarih.substring(0,16);
    document.getElementById('modalEtkinlikAfişPreview').src = '/images/etkinlik/' + data.gorsel;
    document.getElementById('modalEtkinlikAfişInput').value = '';
    document.getElementById('modalEtkinlikRedSebebi').textContent = data.talep_red || '';
    document.getElementById('modalEtkinlikDurum').textContent = data.talep_onay == 1 ? 'Onaylandı' : 'Reddedildi';
    document.getElementById('etkinlikEditModal').style.display = 'block';
    kontrolEtkinlikEditForm();
}

function kontrolEtkinlikEditForm() {
    const baslik = document.getElementById('modalEtkinlikBaslik').value.trim();
    const bilgi = document.getElementById('modalEtkinlikKisaBilgi').value.trim();
    const aciklama = document.getElementById('modalEtkinlikAciklama').value.trim();
    const tarih = document.getElementById('modalEtkinlikTarih').value.trim();
    const btn = document.querySelector('#etkinlikEditForm .guncelle-btn');
    if (baslik && bilgi && aciklama && tarih) {
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
}

['modalEtkinlikBaslik','modalEtkinlikKisaBilgi','modalEtkinlikAciklama','modalEtkinlikTarih'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('input', kontrolEtkinlikEditForm);
    }
});

function closeEtkinlikEditModal() {
    document.getElementById('etkinlikEditModal').style.display = 'none';
}
const etkinlikAfişInput = document.getElementById('modalEtkinlikAfişInput');
if (etkinlikAfişInput) {
    etkinlikAfişInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('modalEtkinlikAfişPreview').src = ev.target.result;
                tempEtkinlikAfiş = file;
            };
            reader.readAsDataURL(file);
        }
    });
}
function saveEtkinlikEdit() {
    if (etkinlikEditIndex !== null) {
        const aciklama = document.getElementById('modalEtkinlikAciklama').value.trim();
        if (!aciklama) {
            alert('Açıklama alanı boş bırakılamaz!');
            return;
        }
        const data = etkinlikEditData[etkinlikEditIndex];
        const formData = new FormData();
        formData.append('id', data.id);
        formData.append('isim', document.getElementById('modalEtkinlikBaslik').value);
        formData.append('bilgi', document.getElementById('modalEtkinlikKisaBilgi').value);
        formData.append('metin', aciklama);
        formData.append('tarih', document.getElementById('modalEtkinlikTarih').value);
        if (tempEtkinlikAfiş) formData.append('gorsel', tempEtkinlikAfiş);
        fetch('/panel/geribildirim/talep-etkinlik-guncelle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                alert('Etkinlik başarıyla güncellendi.');
                closeEtkinlikEditModal();
                renderEtkinlikGeriBildirimTable();
            } else {
                alert('Güncelleme başarısız!');
            }
        })
        .catch(() => alert('Bir hata oluştu.'));
    }
}

// --- Gerçekleşen Etkinlik Geri Bildirim Tablosu ---
let gerceklesenEtkinlikData = [];
let gerceklesenEditIndex = null;
let tempGerceklesenResim = null;
let gerceklesenShowAll = false;

function renderGerceklesenTable() {
    fetch('/panel/geribildirim/gerceklesen-etkinlik')
        .then(response => response.json())
        .then(data => {
            gerceklesenEtkinlikData = data;
            const tbody = document.getElementById('gerceklesenEtkinlikBody');
            tbody.innerHTML = '';
            let showData = gerceklesenShowAll ? data : data.slice(0, 5);
            showData.forEach((item, idx) => {
                let durum = item.e_onay == 1 ? 'Onaylandı' : (item.e_onay == 2 ? 'Beklemede' : 'Reddedildi');
                let duzenleBtn = item.e_onay == 0 ? `<button class='duzenle-btn' onclick='openGerceklesenEditModal(${idx})'>Düzenle</button>` : '';
                // Görüntüle butonu tüm durumlarda çalışmalı - data attribute kullanarak
                let detayBtn = `<button class='duzenle-btn gerceklesen-detay-btn' style='background:#217dbb' data-bilgi="${encodeURIComponent(item.bilgi || '')}" data-aciklama="${encodeURIComponent(item.aciklama || '')}">Görüntüle</button>`;
                let imgHtml = `<img src="/images/etkinlik/${item.resim}" alt="Resim" class="table-img" style="cursor:pointer;" onclick="showImagePopup('/images/etkinlik/${item.resim}')">`;
                
                // Red sebebi için popup sistemi
                let redSebep = item.red_sebebi || '';
                let redHtml = '';
                if (redSebep.length > 30) {
                    redHtml = `<span style='cursor:pointer;color:#c00;text-decoration:underline;' onclick=\"showTextDetailModal('Red Sebebi', '${redSebep.replace(/'/g, "&#39;").replace(/"/g, '&quot;')}')\">${redSebep.substring(0,30)}...</span>`;
                } else if (redSebep) {
                    redHtml = `<span style='cursor:pointer;color:#c00;text-decoration:underline;' onclick=\"showTextDetailModal('Red Sebebi', '${redSebep.replace(/'/g, "&#39;").replace(/"/g, '&quot;')}')\">${redSebep}</span>`;
                } else {
                    redHtml = '';
                }
                
                tbody.innerHTML += `
                <tr>
                    <td>${item.baslik}</td>
                    <td>${item.tarih.replace('T', ' ').substring(0,16)}</td>
                    <td>${item.bitis_tarihi ? item.bitis_tarihi.replace('T', ' ').substring(0,16) : '-'}</td>
                    <td>${imgHtml}</td>
                    <td>${detayBtn}</td>
                    <td>${durum}</td>
                    <td>${redHtml}</td>
                    <td>${duzenleBtn}</td>
                </tr>
                `;
            });
            // Daha fazla/az göster butonları
            const btnId = 'gerceklesenDahaFazlaBtn';
            let btn = document.getElementById(btnId);
            if (btn) btn.remove();
            if (!gerceklesenShowAll && data.length > 5) {
                const showMoreBtn = document.createElement('button');
                showMoreBtn.id = btnId;
                showMoreBtn.className = 'duzenle-btn';
                showMoreBtn.textContent = 'Daha Fazla Göster';
                showMoreBtn.style.margin = '10px auto 0 auto';
                showMoreBtn.onclick = function() {
                    gerceklesenShowAll = true;
                    renderGerceklesenTable();
                };
                tbody.parentElement.appendChild(showMoreBtn);
            } else if (gerceklesenShowAll && data.length > 5) {
                const showLessBtn = document.createElement('button');
                showLessBtn.id = btnId;
                showLessBtn.className = 'duzenle-btn';
                showLessBtn.textContent = 'Daha Az Göster';
                showLessBtn.style.margin = '10px auto 0 auto';
                showLessBtn.onclick = function() {
                    gerceklesenShowAll = false;
                    renderGerceklesenTable();
                };
                tbody.parentElement.appendChild(showLessBtn);
            }
        });
}

function openGerceklesenEditModal(idx) {
    gerceklesenEditIndex = idx;
    tempGerceklesenResim = null;
    const data = gerceklesenEtkinlikData[idx];
    document.getElementById('modalGerceklesenBaslik').value = data.baslik;
    document.getElementById('modalGerceklesenTarih').value = data.tarih.substring(0,16);
    document.getElementById('modalGerceklesenResimPreview').src = '/images/etkinlik/' + data.resim;
    document.getElementById('modalGerceklesenResimInput').value = '';
    document.getElementById('modalGerceklesenDetay').value = (data.aciklama || '');
    document.getElementById('modalGerceklesenRedSebebi').textContent = data.red_sebebi || '';
    document.getElementById('modalGerceklesenDurum').textContent = data.e_onay == 1 ? 'Onaylandı' : 'Reddedildi';
    document.getElementById('gerceklesenEditModal').style.display = 'block';
}

function closeGerceklesenEditModal() {
    document.getElementById('gerceklesenEditModal').style.display = 'none';
}
const gerceklesenResimInput = document.getElementById('modalGerceklesenResimInput');
if (gerceklesenResimInput) {
    gerceklesenResimInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('modalGerceklesenResimPreview').src = ev.target.result;
                tempGerceklesenResim = ev.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
}
function saveGerceklesenEdit() {
    if (gerceklesenEditIndex !== null) {
        const data = gerceklesenEtkinlikData[gerceklesenEditIndex];
        const formData = new FormData();
        formData.append('id', data.id);
        formData.append('baslik', document.getElementById('modalGerceklesenBaslik').value);
        formData.append('tarih', document.getElementById('modalGerceklesenTarih').value);
        formData.append('aciklama', document.getElementById('modalGerceklesenDetay').value);
        if (tempGerceklesenResim) {
            formData.append('resim', tempGerceklesenResim);
        }
        
        fetch('/panel/geribildirim/gerceklesen-etkinlik-guncelle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(resp => {
            if (resp.success) {
                alert('Etkinlik başarıyla güncellendi ve onay için gönderildi.');
                closeGerceklesenEditModal();
                renderGerceklesenTable();
            } else {
                alert('Güncelleme başarısız!');
            }
        })
        .catch(() => alert('Bir hata oluştu.'));
    }
}

// --- Üye Silme Geri Bildirim Tablosu ---
let uyeSilmeData = [];
let uyeSilmeShowAll = false;
function renderUyeSilmeTable() {
    fetch('/panel/geribildirim/silinen-uyeler')
        .then(response => response.json())
        .then(data => {
            uyeSilmeData = data;
            const tbody = document.getElementById('uyeSilmeGeriBildirimBody');
            tbody.innerHTML = '';
            let showData = uyeSilmeShowAll ? data : data.slice(0, 5);
            showData.forEach((item, idx) => {
                tbody.innerHTML += `
                <tr>
                    <td>${item.ogr_no}</td>
                    <td>${item.ad}</td>
                    <td>${item.soyad}</td>
                    <td>${item.cep_tel}</td>
                    <td>${item.fakulte}</td>
                    <td>${item.bolum}</td>
                    <td>${item.uyelik_formu ? `<a href="/docs/kayit_belge/${item.uyelik_formu}" target="_blank">Form</a>` : '-'}</td>
                    <td class="silinme-sebebi-cell" style="cursor:pointer; color:#217dbb;" onclick="showSilinmeSebebiModalDinamik('${item.silinme_sebebi ? item.silinme_sebebi.replace(/'/g, "&#39;").replace(/\n/g, '<br>') : ''}')">${item.silinme_sebebi && item.silinme_sebebi.length > 30 ? item.silinme_sebebi.substring(0,30)+'...' : (item.silinme_sebebi || '')}</td>
                </tr>
                `;
            });
            // Daha fazla/az göster butonları
            const btnId = 'uyeSilmeDahaFazlaBtn';
            let btn = document.getElementById(btnId);
            if (btn) btn.remove();
            if (!uyeSilmeShowAll && data.length > 5) {
                const showMoreBtn = document.createElement('button');
                showMoreBtn.id = btnId;
                showMoreBtn.className = 'duzenle-btn';
                showMoreBtn.textContent = 'Daha Fazla Göster';
                showMoreBtn.style.margin = '10px auto 0 auto';
                showMoreBtn.onclick = function() {
                    uyeSilmeShowAll = true;
                    renderUyeSilmeTable();
                };
                tbody.parentElement.appendChild(showMoreBtn);
            } else if (uyeSilmeShowAll && data.length > 5) {
                const showLessBtn = document.createElement('button');
                showLessBtn.id = btnId;
                showLessBtn.className = 'duzenle-btn';
                showLessBtn.textContent = 'Daha Az Göster';
                showLessBtn.style.margin = '10px auto 0 auto';
                showLessBtn.onclick = function() {
                    uyeSilmeShowAll = false;
                    renderUyeSilmeTable();
                };
                tbody.parentElement.appendChild(showLessBtn);
            }
        });
}

function showSilinmeSebebiModalDinamik(sebep) {
    document.getElementById('modalSilinmeSebebiText').innerHTML = sebep || '-';
    document.getElementById('silinmeSebebiModal').style.display = 'block';
}

function closeSilinmeSebebiModal() {
    document.getElementById('silinmeSebebiModal').style.display = 'none';
}

// --- Topluluk Durumu Geri Bildirim Tablosu ---
const toplulukDurumuGeriBildirimData = [
    {
        toplulukAdi: 'Bilişim Topluluğu',
        silinmeSebebi: 'Yeterli üye bulunamadı.'
    },
    {
        toplulukAdi: 'Müzik Topluluğu',
        silinmeSebebi: 'Faaliyet göstermediği için silindi.'
    }
];
function renderToplulukDurumuTable() {
    const tbody = document.getElementById('toplulukDurumuGeriBildirimBody');
    tbody.innerHTML = '';
    toplulukDurumuGeriBildirimData.forEach((item) => {
        tbody.innerHTML += `
        <tr>
            <td>${item.toplulukAdi}</td>
            <td>${item.silinmeSebebi}</td>
        </tr>
        `;
    });
}

// --- Web Arayüz İşlemleri Tablosu ---
function capitalizeFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Modal oluşturma fonksiyonu
function showContentModal(type, content) {
    let modal = document.getElementById('webArayuzContentModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'webArayuzContentModal';
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content" style="max-width:600px;text-align:center;position:relative;">
                <span class="close" style="position:absolute;right:16px;top:10px;font-size:28px;cursor:pointer;" onclick="closeWebArayuzContentModal()">&times;</span>
                <div id="webArayuzContentModalBody"></div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    let body = document.getElementById('webArayuzContentModalBody');
    if (type === 'image') {
        body.innerHTML = `<img src="${content}" style="max-width:90%;max-height:70vh;border-radius:10px;box-shadow:0 2px 16px rgba(0,0,0,0.18);">`;
    } else if (type === 'text') {
        body.innerHTML = `<div style="font-size:2rem;color:#003366;font-weight:500;white-space:pre-line;">${content}</div>`;
    }
    modal.style.display = 'flex';
}
function closeWebArayuzContentModal() {
    let modal = document.getElementById('webArayuzContentModal');
    if (modal) modal.style.display = 'none';
}

function renderWebArayuzTable() {
    fetch('/panel/geribildirim/web-arayuz-bilgileri')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('geriBildirimBody');
            tbody.innerHTML = '';
            data.forEach((item, idx) => {
                let durum = '';
                if (item.onay == 1) durum = 'Onaylandı';
                else if (item.onay == 0) durum = 'Reddedildi';
                else if (item.onay == 2) durum = 'Beklemede';
                else if (item.onay == 4) durum = 'Varsayılan';
                let red = item.onay == 0 ? (item.red ? `<span style='cursor:pointer;color:#c00;text-decoration:underline;' onclick="showTextDetailModal('Red Sebebi', \`${item.red.replace(/`/g, '\`').replace(/'/g, "&#39;")}\`)">${item.red.length > 30 ? item.red.substring(0,30)+'...' : item.red}</span>` : '-') : '';
                let guncelleBtn = item.onay == 0 ? `<button class='duzenle-btn' onclick='guncelleWebArayuzBilgi("${item.ozellik}")'>Güncelle</button>` : '';
                let deger = item.deger;
                let degerHtml = '-';
                if (item.ozellik === 'logo' && deger) {
                    degerHtml = `<img src='/images/logo/${deger}' alt='logo' style='max-width:40px;max-height:40px;cursor:pointer;' onclick="showContentModal('image','/images/logo/${deger}')">`;
                } else if (item.ozellik === 'arkaplan' && deger) {
                    degerHtml = `<img src='/images/background/${deger}' alt='bg' style='max-width:40px;max-height:40px;cursor:pointer;' onclick="showContentModal('image','/images/background/${deger}')">`;
                } else if (item.ozellik === 'tuzuk' && deger) {
                    degerHtml = `<a href='/files/tuzuk/${deger}' target='_blank'>Belge</a>`;
                } else if ((item.ozellik === 'vizyon' || item.ozellik === 'misyon') && deger) {
                    let shortText = deger.length > 30 ? deger.substring(0,30)+'...' : deger;
                    degerHtml = `<span style='cursor:pointer;color:#217dbb;text-decoration:underline;' onclick="showTextDetailModal('${capitalizeFirst(item.ozellik)}', \`${deger.replace(/`/g, '\`').replace(/'/g, "&#39;")}\`)">${shortText}</span>`;
                } else {
                    degerHtml = capitalizeFirst(deger);
                }
                tbody.innerHTML += `
                    <tr>
                        <td>${capitalizeFirst(item.ozellik)}</td>
                        <td>${degerHtml || '-'}</td>
                        <td>${durum}</td>
                        <td>${red}</td>
                        <td>${guncelleBtn}</td>
                    </tr>
                `;
            });
        });
}

function guncelleWebArayuzBilgi(ozellik) {
    window.location.href = '/yonetici_panel';
}

// --- Sosyal Medya İşlemleri Tablosu ---
function renderSosyalMedyaTable() {
    fetch('/panel/geribildirim/sosyal-medya-bilgileri')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('sosyalMedyaGeriBildirimBody');
            tbody.innerHTML = '';
            data.forEach(item => {
                // Durum metni
                let durum = '';
                if (item.onay == 1) durum = 'Onaylandı';
                else if (item.onay == 0) durum = 'Reddedildi';
                else if (item.onay == 2) durum = 'Beklemede';
                // İçerik butonu
                let icerik = '-';
                if (item.medya === 'instagram' && item.link) {
                    icerik = `<button class='duzenle-btn' style='background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%); border: none; color: white;' onclick="window.open('${item.link}','_blank')">Sayfaya Git</button>`;
                } else if (item.medya === 'whatsapp' && item.link) {
                    icerik = `<button class='duzenle-btn' style='background:#28a745; border: none; color: white;' onclick="window.open('${item.link}','_blank')">Grubu Görüntüle</button>`;
                } else if (item.medya === 'linkedln' && item.link) {
                    icerik = `<button class='duzenle-btn' style='background:#31d2f2; border: none; color: white;' onclick="window.open('${item.link}','_blank')">Sayfaya Git</button>`;
                }
                // Red sebebi
                let redSebebi = '';
                if (item.onay == 0 && item.red) {
                    redSebebi = `<span style='cursor:pointer; color:#c00; text-decoration:underline;' onclick="showTextDetailModal('Red Sebebi', '${item.red.replace(/'/g, "&#39;").replace(/"/g, '&quot;')}')">${item.red.length > 30 ? item.red.substring(0,30)+'...' : item.red}</span>`;
                }
                // Medya adı baş harf büyük
                let medyaAd = '';
                if (item.medya === 'instagram') medyaAd = 'Instagram';
                else if (item.medya === 'whatsapp') medyaAd = 'Whatsapp';
                else if (item.medya === 'linkedln') medyaAd = 'LinkedIn';
                else medyaAd = item.medya.charAt(0).toUpperCase() + item.medya.slice(1);
                tbody.innerHTML += `
                    <tr>
                        <td style="font-weight:bold">${medyaAd}</td>
                        <td>${icerik}</td>
                        <td>${durum}</td>
                        <td>${redSebebi}</td>
                    </tr>
                `;
            });
        });
}

// Red sebebi validasyonu
function validateRedReason(textarea) {
    const sendButton = textarea.parentElement.querySelector('button[type="submit"]');
    const reason = textarea.value.trim();
    
    if (reason.length === 0) {
        sendButton.disabled = true;
        sendButton.style.opacity = '0.5';
        sendButton.style.cursor = 'not-allowed';
    } else {
        sendButton.disabled = false;
        sendButton.style.opacity = '1';
        sendButton.style.cursor = 'pointer';
    }
}

// --- Tüm tabloları yükle ---
const oldOnload = window.onload;
window.onload = function() {
    if (oldOnload) oldOnload();
    renderEtkinlikGeriBildirimTable();
    renderGerceklesenTable();
    renderUyeSilmeTable();
    renderToplulukDurumuTable();
    renderWebArayuzTable();
    renderSosyalMedyaTable();
    
    // Gerçekleşen etkinlikler için event listener ekle
    setTimeout(() => {
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('gerceklesen-detay-btn')) {
                const bilgi = decodeURIComponent(e.target.getAttribute('data-bilgi') || '');
                const aciklama = decodeURIComponent(e.target.getAttribute('data-aciklama') || '');
                showEtkinlikDetay(bilgi, aciklama);
            }
        });
        
        // Red sebebi textarea'larını kontrol et - tüm textarea'ları bul
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            // İlk yüklemede kontrol et
            validateRedReason(textarea);
            
            // Her değişiklikte kontrol et
            textarea.addEventListener('input', function() {
                validateRedReason(this);
            });
        });
        
        // Dinamik olarak eklenen textarea'lar için MutationObserver
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        const newTextareas = node.querySelectorAll ? node.querySelectorAll('textarea') : [];
                        newTextareas.forEach(textarea => {
                            validateRedReason(textarea);
                            textarea.addEventListener('input', function() {
                                validateRedReason(this);
                            });
                        });
                    }
                });
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }, 100);
};

function showTextDetailModal(title, content) {
    // Eğer content içinde <b>Kısa Bilgi:</b><br>...<b>Açıklama:</b><br>... varsa, başlıkları bold, metinleri alt alta göster
    if (title === 'Etkinlik Detayları' && content.includes('<b>Kısa Bilgi:</b>')) {
        // Kısa Bilgi ve Açıklama metinlerini ayır
        let kisa = '', aciklama = '';
        const kisaMatch = content.match(/<b>Kısa Bilgi:<\/b><br>(.*?)(<br><b>Açıklama:<\/b>|$)/s);
        if (kisaMatch) kisa = kisaMatch[1].replace(/<br>/g, '\n');
        const aciklamaMatch = content.match(/<b>Açıklama:<\/b><br>(.*)$/s);
        if (aciklamaMatch) aciklama = aciklamaMatch[1].replace(/<br>/g, '\n');
        content = `<div style='font-size:1.8rem;line-height:1.6;'>
            <span style='font-weight:bold;'>Kısa Bilgi:</span><br><span>${kisa.replace(/\n/g, '<br>')}</span><br><br>
            <span style='font-weight:bold;'>Açıklama:</span><br><span>${aciklama.replace(/\n/g, '<br>')}</span>
        </div>`;
    }
    document.getElementById('textDetailTitle').textContent = title;
    document.getElementById('textDetailContent').innerHTML = content;
    document.getElementById('textDetailModal').style.display = 'flex';
}

function showEtkinlikDetay(kisa, aciklama) {
    // Parametreleri güvenli hale getir
    kisa = kisa || '';
    aciklama = aciklama || '';
    
    document.getElementById('textDetailTitle').textContent = 'Etkinlik Detayları';
    document.getElementById('textDetailContent').innerHTML =
        `<div style='font-size:1.5rem;line-height:1.6;'>
            <span style='font-weight:bold;'>Kısa Bilgi:</span><br>
            <span>${escapeHtml(kisa).replace(/\n/g, '<br>')}</span><br>
            <span style='font-weight:bold; margin-top:10px;'>Açıklama:</span><br>
            <span>${escapeHtml(aciklama).replace(/\n/g, '<br>')}</span>
        </div>`;
    document.getElementById('textDetailModal').style.display = 'flex';
}

function escapeHtml(text) {
    return text.replace(/[&<>'"]/g, function (c) {
        return {'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c];
    });
}

function showImagePopup(imgSrc) {
    let modal = document.getElementById('imagePopupModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'imagePopupModal';
        modal.style.position = 'fixed';
        modal.style.left = '0';
        modal.style.top = '0';
        modal.style.width = '100vw';
        modal.style.height = '100vh';
        modal.style.background = 'rgba(0,0,0,0.45)';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modal.style.zIndex = '9999';
        modal.innerHTML = `<div style='background:#fff; padding:12px 12px 2px 12px; border-radius:10px; box-shadow:0 4px 24px rgba(0,0,0,0.18); max-width:90vw; max-height:90vh; display:flex; flex-direction:column; align-items:center; position:relative;'>
            <span onclick="document.getElementById('imagePopupModal').remove()" style='position:absolute;top:7px;right:12px;font-size:22px;font-weight:bold;color:#888;cursor:pointer;z-index:2;' onmouseover="this.style.color='#003366'" onmouseout="this.style.color='#888'">&times;</span>
            <img id='popupImage' src='' style='max-width:70vw; max-height:70vh; border-radius:8px; box-shadow:0 2px 12px rgba(0,0,0,0.13);'>
        </div>`;
        document.body.appendChild(modal);
    }
    document.getElementById('popupImage').src = imgSrc;
    modal.style.display = 'flex';
}

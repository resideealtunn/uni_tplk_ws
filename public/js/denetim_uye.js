let id = null;
function openUyeListesiModal(toplulukId) {
    id = toplulukId
    const baseURL = "/docs/kayit_belge/";
    fetch(`/denetim/uye/${toplulukId}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById("uyeListesi");
            tbody.innerHTML = ""; // Önce tabloyu temizle

            data.forEach(uye => {
                const belgeURL = baseURL + uye.belge;
                const row = `<tr>
                    <td>${uye.tarih ?? 'Bilinmiyor'}</td>
                    <td>${uye.numara}</td>
                    <td>${uye.isim} ${uye.soyisim}</td>
                    <td>${uye.tel}</td>
                    <td>${uye.fak_ad}</td>
                    <td>${uye.bol_ad}</td>
                    <td><a href="${belgeURL}" target="_blank">İndir</a></td>
                </tr>`;
                tbody.innerHTML += row;
            });

            // Modalı aç
            document.getElementById("uyeListeModal").style.display = "block";

            // Arama kutusu eventini her açılışta ekle
            const searchInput = document.getElementById('searchInputUyeListesi');
            searchInput.value = ""; // Önceki aramayı temizle
            searchInput.onkeyup = function () {
                let query = this.value.toLowerCase();
                let rows = document.querySelectorAll("#uyeListesi tr");
                rows.forEach(row => {
                    // 2. hücre (index 1) öğrenci no
                    let ogrNo = row.cells[1]?.textContent.toLowerCase() || '';
                    if (ogrNo.includes(query)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            };
        })
        .catch(error => console.error("Veri çekme hatası:", error));
}
function openBasvuruListeModal(toplulukId) {
    id = toplulukId
    const baseURL = "/docs/kayit_belge/"; // Public klasör içindeki dosya yolu
    fetch(`/denetim/uye/basvuru/${toplulukId}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById("basvuruListesi");
            tbody.innerHTML = ""; // Önce tabloyu temizle

            data.forEach(uye => {
                const belgeURL = baseURL + uye.belge;
                const row = `<tr>
                    <td>${uye.tarih ?? 'Bilinmiyor'}</td>
                    <td>${uye.numara}</td>
                    <td>${uye.isim} ${uye.soyisim}</td>
                    <td>${uye.tel}</td>
                    <td>${uye.fak_ad}</td>
                    <td>${uye.bol_ad}</td>
                    <td><a href="${belgeURL}" target="_blank">İndir</a></td>
                    <td>
                        <button onclick="onaylaBasvuru(${uye.id})" class="btn btn-success">Onayla</button>
                        <button onclick="acRedSebebiModal(${uye.id})" class="btn btn-danger">Reddet</button>
                    </td>
                </tr>`;
                tbody.innerHTML += row;
            });

            document.getElementById("basvuruListeModal").style.display = "block"; // Modalı aç

            // Arama kutusu eventini her açılışta ekle
            const searchInput = document.getElementById('searchBasvuruNo');
            searchInput.value = "";
            searchInput.onkeyup = function () {
                let query = this.value.toLowerCase();
                let rows = document.querySelectorAll("#basvuruListesi tr");
                rows.forEach(row => {
                    let ogrNo = row.cells[1]?.textContent.toLowerCase() || '';
                    if (ogrNo.includes(query)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            };
        })
        .catch(error => console.error("Veri çekme hatası:", error));
}
function approveApplication(ogr_id, durum)
{
    t_id=id
    fetch(`/denetim/uye/onayla`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ id: ogr_id, durum: durum,t_id: t_id })
    })
        .then(response => response.json())
        .then(data => {
            console.log("Sunucu Yanıtı:", data);

            if (data.success) {
                alert("Başvuru durumu başarıyla güncellendi!");
                location.reload();
            } else {
                alert("Güncelleme başarısız: " + data);
            }
        })
        .catch(error => console.error("Güncelleme hatası:", error));
}
const baseURL = "/docs/kayit_belge/";

function openGuncelleModal(toplulukId) {
    id = toplulukId
    fetch(`/denetim/uye/${toplulukId}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById("guncelleUyeListesi");
            tbody.innerHTML = ""; // Önce tabloyu temizle
            const roleText = {
                1: "Üye",
                2: "Başkan",
                3: "Başkan Yardımcısı"
            };
            data.forEach(uye => {
                const belgeURL = baseURL + uye.belge;
                const row = `<tr>
                    <td>${uye.tarih ?? 'Bilinmiyor'}</td>
                    <td>${uye.numara}</td>
                    <td>${uye.isim} ${uye.soyisim}</td>
                    <td>${uye.tel}</td>
                    <td>${uye.fak_ad}</td>
                    <td>${uye.bol_ad}</td>
                    <td><a href="${belgeURL}" target="_blank">İndir</a></td>
                    <td>${roleText[uye.rol] ?? "Bilinmiyor"}</td>
                    <td>
                        <select id="roleSelect-${uye.id}">
                            <option value="1" ${uye.rol == 1 ? 'selected' : ''}>Üye</option>
                            <option value="2" ${uye.rol == 2 ? 'selected' : ''}>Başkan</option>
                            <option value="3" ${uye.rol == 3 ? 'selected' : ''}>Başkan Yardımcısı</option>
                        </select>
                    </td>
                    <td>
                        <button onclick="updateRole(${uye.id})" class="btn btn-primary">Güncelle</button>
                    </td>
                </tr>`;
                tbody.innerHTML += row;
            });

            document.getElementById("guncelleModal").style.display = "block"; // Modalı aç
        })
        .catch(error => console.error("Veri çekme hatası:", error));
}

function updateRole(id) {
    const newRole = document.getElementById(`roleSelect-${id}`).value;

    fetch(`/denetim/uye/rol`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ id: id, rol: newRole })
    })
        .then(response => response.json())
        .then(data => {
            console.log(`Sunucu Yanıtı - ID: ${id}, Yeni Rol: ${newRole}`, data);

            if (data.success) {
                alert("Üyelik rolü başarıyla güncellendi!");
                location.reload();
            } else {
                alert("Güncelleme başarısız: " + data.message);
            }
        })
        .catch(error => console.error("Rol güncelleme hatası:", error));
}

function openYeniUyeModal(toplulukId) {
    id = toplulukId;
    document.getElementById("yeniUyeForm").reset();
    document.getElementById("kayitSekli").value = "denetim";
    document.getElementById("basvuruTarihi").value = new Date().toISOString().split('T')[0];
    document.getElementById("yeniUyeModal").style.display = "block";
}

// Form submit
if(document.getElementById('yeniUyeForm')) {
    document.getElementById('yeniUyeForm').onsubmit = function(e) {
        e.preventDefault();
        const tcno = document.getElementById('tcKimlikNo').value;
        const tarih = document.getElementById('basvuruTarihi').value;
        const belge = document.getElementById('uyelikFormu').files[0];
        if (!tcno || !belge) {
            alert('TC Kimlik No ve üyelik formu zorunludur.');
            return;
        }
        const formData = new FormData();
        formData.append('tcno', tcno);
        formData.append('basvuru_tarihi', tarih);
        formData.append('belge', belge);
        formData.append('topluluk_id', id);

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
                alert('Üye başarıyla eklendi!');
                closeModal('yeniUyeModal');
            } else {
                alert('Hata: ' + (data.message || 'Üye eklenemedi.'));
            }
        })
        .catch(() => alert('Bir hata oluştu.'));
    };
}

function openSilModal(toplulukId) {
    id = toplulukId;
    const baseURL = "/docs/kayit_belge/";
    fetch(`/denetim/uye/sil/${toplulukId}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById("silListesi");
            tbody.innerHTML = ""; // Önce tabloyu temizle

            data.forEach(uye => {
                const belgeURL = uye.belge ? (baseURL + uye.belge) : null;
                const row = `<tr>
                    <td>${uye.tarih ?? 'Bilinmiyor'}</td>
                    <td>${uye.numara}</td>
                    <td>${uye.isim}</td>
                    <td>${uye.soyisim}</td>
                    <td>${uye.tel}</td>
                    <td>${uye.fakulte}</td>
                    <td>${uye.bolum}</td>
                    <td>${belgeURL ? `<a href='${belgeURL}' target='_blank'>İndir</a>` : '-'}</td>
                    <td><button onclick="acSilSebebiModal(${uye.uye_id})" class="btn btn-danger">Sil</button></td>
                </tr>`;
                tbody.innerHTML += row;
            });

            document.getElementById("silModal").style.display = "block";

            // Arama kutusu eventini her açılışta ekle
            const searchInput = document.getElementById('searchInputSilListesi');
            searchInput.value = "";
            searchInput.onkeyup = function () {
                let query = this.value.toLowerCase();
                let rows = document.querySelectorAll("#silListesi tr");
                rows.forEach(row => {
                    let ogrNo = row.cells[1]?.textContent.toLowerCase() || '';
                    if (ogrNo.includes(query)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            };
        })
        .catch(error => console.error("Veri çekme hatası:", error));
}

let silUyeId = null;
function acSilSebebiModal(uye_id) {
    silUyeId = uye_id;
    document.getElementById('silinmeSebebi').value = '';
    document.getElementById('silSebebiModal').style.display = 'block';
}

document.querySelector('#silSebebiModal .btn-success').onclick = function() {
    const sebep = document.getElementById('silinmeSebebi').value.trim();
    if (!sebep) {
        alert('Lütfen silinme sebebini giriniz.');
        return;
    }
    console.log('Silme isteği gönderiliyor:', { uye_id: silUyeId, sebep: sebep });
    fetch('/denetim/uye/sil/denetim', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ 
            uye_id: silUyeId, 
            silinme_sebebi: sebep 
        })
    })
    .then(response => {
        console.log('Sunucu yanıtı:', response);
        return response.json();
    })
    .then(data => {
        console.log('İşlem sonucu:', data);
        if (data.success) {
            alert('Üye başarıyla silindi!');
            document.getElementById('silSebebiModal').style.display = 'none';
            openSilModal(id);
        } else {
            alert('Silme işlemi başarısız: ' + (data.message || 'Bilinmeyen hata'));
        }
    })
    .catch(error => {
        console.error('Hata:', error);
        alert('Bir hata oluştu: ' + error.message);
    });
};

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

function openSilinenUyelerGeriModal(toplulukId) {
    fetch(`/denetim/uye/silinenler/${toplulukId}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById("silinenUyelerGeriListesi");
            tbody.innerHTML = "";
            data.forEach(uye => {
                const belgeURL = `/docs/kayit_belge/${uye.belge}`;
                const row = `<tr>
                    <td>${uye.numara}</td>
                    <td>${uye.isim} ${uye.soyisim}</td>
                    <td>${uye.tel}</td>
                    <td>${uye.fak_ad}</td>
                    <td>${uye.bol_ad}</td>
                    <td>${uye.belge ? `<a href='${belgeURL}' target='_blank'>Görüntüle</a>` : '-'}</td>
                    <td>
                        <button class="btn btn-primary" onclick="showSilSebepMiniModal(\`${uye.silinme_sebebi ? uye.silinme_sebebi.replace(/`/g, '\\`') : ''}\`)">Görüntüle</button>
                    </td>
                </tr>`;
                tbody.innerHTML += row;
            });
            document.getElementById("silinenUyelerGeriModal").style.display = "block";
        });
}

function showSilSebepMiniModal(sebep) {
    document.getElementById('silSebepIcerik').textContent = sebep || 'Sebep girilmemiş.';
    document.getElementById('silSebepMiniModal').style.display = 'block';
}

function onaylaBasvuru(uye_id) {
    console.log('Onaylama isteği gönderiliyor:', uye_id);
    fetch('/denetim/uye/basvuru/onayla', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ uye_id: uye_id })
    })
    .then(response => {
        console.log('Sunucu yanıtı:', response);
        return response.json();
    })
    .then(data => {
        console.log('İşlem sonucu:', data);
        if (data.success) {
            alert('Başvuru onaylandı!');
            openBasvuruListeModal(id);
        } else {
            alert('Onaylama başarısız: ' + (data.message || 'Bilinmeyen hata'));
        }
    })
    .catch(error => {
        console.error('Hata:', error);
        alert('Bir hata oluştu: ' + error.message);
    });
}

let redUyeId = null;
function acRedSebebiModal(uye_id) {
    redUyeId = uye_id;
    document.getElementById('redSebebi').value = '';
    document.getElementById('redSebebiModal').style.display = 'block';
}

document.querySelector('#redSebebiModal .btn-success').onclick = function() {
    const sebep = document.getElementById('redSebebi').value.trim();
    if (!sebep) {
        alert('Lütfen red sebebini giriniz.');
        return;
    }
    console.log('Reddetme isteği gönderiliyor:', { uye_id: redUyeId, sebep: sebep });
    fetch('/denetim/uye/basvuru/reddet', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ 
            uye_id: redUyeId, 
            red_sebebi: sebep 
        })
    })
    .then(response => {
        console.log('Sunucu yanıtı:', response);
        return response.json();
    })
    .then(data => {
        console.log('İşlem sonucu:', data);
        if (data.success) {
            alert('Başvuru reddedildi!');
            document.getElementById('redSebebiModal').style.display = 'none';
            openBasvuruListeModal(id);
        } else {
            alert('Reddetme başarısız: ' + (data.message || 'Bilinmeyen hata'));
        }
    })
    .catch(error => {
        console.error('Hata:', error);
        alert('Bir hata oluştu: ' + error.message);
    });
};

// Excel Modal Fonksiyonları
function openExcelModal() {
    document.getElementById('excelModal').style.display = 'block';
    loadTopluluklar();
}

// --- Autocomplete Combobox ---
let allTopluluklar = [];
let selectedToplulukId = null;
let dropdownIndex = -1;

function loadTopluluklar() {
    fetch('/denetim/topluluklar')
        .then(response => response.json())
        .then(data => {
            // Alfabetik sırala
            data.sort((a, b) => a.isim.localeCompare(b.isim, 'tr'));
            const select = document.getElementById('toplulukSelect');
            select.innerHTML = '<option value="">Topluluk seçiniz...</option>';
            data.forEach(topluluk => {
                const option = document.createElement('option');
                option.value = topluluk.id;
                option.textContent = topluluk.isim;
                select.appendChild(option);
            });
            if (window.jQuery) {
                $('#toplulukSelect').select2({
                    dropdownParent: $('#excelModal'),
                    width: '100%',
                    placeholder: 'Topluluk seçiniz...',
                    allowClear: true,
                    minimumResultsForSearch: 0
                });
                $('#toplulukSelect').val('').trigger('change');
                $('#toplulukSelect').on('change', function() {
                    const val = $(this).val();
                    document.getElementById('indirButton').disabled = !val;
                });
            }
        })
        .catch(error => {
            console.error('Topluluklar yüklenirken hata:', error);
            alert('Topluluklar yüklenirken bir hata oluştu.');
        });
}

function indirExcel() {
    const toplulukId = document.getElementById('toplulukSelect').value;
    if (!toplulukId) {
        alert('Lütfen bir topluluk seçiniz.');
        return;
    }
    window.location.href = `/denetim/uye/excel-indir/${toplulukId}`;
    closeModal('excelModal');
}
function openUyeListesiModal(toplulukId) {
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

            document.getElementById("uyeListeModal").style.display = "block"; // Modalı aç
        })
        .catch(error => console.error("Veri çekme hatası:", error));
}
function openBasvuruListeModal(toplulukId) {
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
                        <button onclick="approveApplication(${uye.id} , 1)" class="btn btn-success">Onayla</button>
                        <button onclick="approveApplication(${uye.id}, 2)" class="btn btn-danger">Reddet</button>
                    </td>
                </tr>`;
                tbody.innerHTML += row;
            });

            document.getElementById("basvuruListeModal").style.display = "block"; // Modalı aç
        })
        .catch(error => console.error("Veri çekme hatası:", error));
}
function approveApplication(id, durum) {
    fetch(`/denetim/uye/onayla`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ id: id, durum: durum })
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
    document.getElementById("yeniUyeModal").style.display = "block";

    document.getElementById("yeniUyeForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Sayfanın yeniden yüklenmesini engelle

        const ogrno = document.getElementById("yeniOgrNo").value;
        const belgeFile = document.getElementById("belge").files[0]; // Dosyayı a
        console.log(`Öğrenci No: ${ogrno}`);
        console.log(`Topluluk ID: ${toplulukId}`);
        console.log(`Seçilen Dosya:`, belgeFile);

        const formData = new FormData();
        formData.append("ogrno", ogrno);
        formData.append("belge", belgeFile);
        formData.append("topluluk_id", toplulukId);

        fetch("/denetim/uye/ekle", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                console.log("Sunucu Yanıtı:", data);

                if (data.success) {
                    alert("Üye başarıyla eklendi!");
                    location.reload();
                } else {
                    alert("Hata: " + data.message);
                }
            })
            .catch(error => console.error("Üye ekleme hatası:", error));
    });
}

function openSilModal(toplulukId) {
    document.getElementById("silModal").style.display = "block";

    fetch(`/denetim/uye/sil/${toplulukId}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById("silListesi");
            tbody.innerHTML = "";

            data.forEach(uye => {
                const belgeURL = `docs/kayit_belge/${uye.belge}`;
                const row = `<tr>
                    <td>${uye.tarih ?? 'Bilinmiyor'}</td>
                    <td>${uye.numara}</td>
                    <td>${uye.isim} ${uye.soyisim}</td>
                    <td>${uye.tel}</td>
                    <td>${uye.fakulte}</td>
                    <td>${uye.bolum}</td>
                    <td><a href="${belgeURL}" target="_blank">İndir</a></td>
                    <td>
                        <button onclick="deleteUye(${uye.id})" class="btn btn-danger">Sil</button>
                    </td>
                </tr>`;
                tbody.innerHTML += row;
            });
        })
        .catch(error => console.error("Üye listesi çekme hatası:", error));
}
function deleteUye(uyeId) {
    fetch(`/denetim/uye/sil`, {
        method: "POST", // 🟢 GET yerine POST
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ id: uyeId, durum: 2 })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Üye başarıyla Silindi!");
                location.reload();
            } else {
                alert("Hata: " + data.message);
            }
        })
        .catch(error => console.error("Silme hatası:", error));
}
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

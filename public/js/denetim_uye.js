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
function openGuncelleModal() {
    document.getElementById("guncelleModal").style.display = "block";
}

function openYeniUyeModal() {
    document.getElementById("yeniUyeModal").style.display = "block";
}

function openSilModal() {
    document.getElementById("silModal").style.display = "block";
}

// Modal kapatma fonksiyonu
function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}


function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}


    function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

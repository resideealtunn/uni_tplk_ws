function openUyeListesiModal(toplulukId) {
    fetch(`/denetim/uye/${toplulukId}`) // ✅ Yeni route: /denetim/uye/{id}
        .then(response => response.json())
        .then(data => {
            console.log("JSON Formatında Veri:", data); // Konsolda veriyi kontrol et

            const tbody = document.getElementById("uyeListesi");
            if (!tbody) {
                console.error("UYARI: 'uyeListesi' öğesi bulunamadı! Lütfen HTML içinde `id='uyeListesi'` olan bir tablo eklediğinden emin ol.");
                return;
            }

            tbody.innerHTML = ""; // Önce tabloyu temizle

            data.forEach(uye => { // Gelen JSON verisini satır satır ekleyelim
                const row = `<tr>
                    <td>${uye.numara}</td>
                    <td>${uye.isim} ${uye.soyisim}</td>
                    <td>${uye.tel}</td>
                    <td>${uye.fak_ad}</td>
                    <td>${uye.bol_ad}</td>
                    <td><a href="${uye.belge}" target="_blank">İndir</a></td>
                </tr>`;
                tbody.innerHTML += row;
            });

            document.getElementById("uyeListeModal").style.display = "block"; // Modalı aç
        })
        .catch(error => console.error("Veri çekme hatası:", error));
}
function openBasvuruListeModal() {
    document.getElementById("basvuruListeModal").style.display = "block";
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

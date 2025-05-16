// Modal işlemleri
function showModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Etkinlik Ekle Modal
function showEtkinlikEkleModal() {
    showModal('etkinlikEkleModal');
}

// Başvuru Aç/Kapat Modal
function showBasvuruModal() {
    showModal('basvuruModal');
    // Etkinlik seçildiğinde durumu güncelle
    document.getElementById('etkinlikSec').addEventListener('change', function() {
        updateBasvuruDurumu(this.value);
    });
}

// Başvuru durumunu güncelle
function updateBasvuruDurumu(etkinlikId) {
    // Burada API'den etkinliğin mevcut durumunu alabilirsiniz
    // Şimdilik örnek olarak rastgele bir durum gösteriyoruz
    const durum = Math.random() > 0.5 ? 'Açık' : 'Kapalı';
    const durumElement = document.getElementById('mevcutDurum');
    durumElement.textContent = `Başvurular ${durum}`;
    durumElement.className = `durum-text durum-${durum.toLowerCase()}`;
}

// Başvuru durumunu değiştir
function toggleBasvuru() {
    const etkinlikId = document.getElementById('etkinlikSec').value;
    if (!etkinlikId) {
        alert('Lütfen bir etkinlik seçin');
        return;
    }

    const durumElement = document.getElementById('mevcutDurum');
    const yeniDurum = durumElement.textContent.includes('Açık') ? 'Kapalı' : 'Açık';

    // Burada API'ye istek atarak durumu değiştirebilirsiniz
    // Şimdilik sadece görsel değişiklik yapıyoruz
    durumElement.textContent = `Başvurular ${yeniDurum}`;
    durumElement.className = `durum-text durum-${yeniDurum.toLowerCase()}`;

    alert(`Başvurular ${yeniDurum} olarak güncellendi`);
}

// Yoklama Aç/Kapat Modal
function showYoklamaModal() {
    showModal('yoklamaModal');
    // Etkinlik seçildiğinde durumu güncelle
    document.getElementById('yoklamaEtkinlikSec').addEventListener('change', function() {
        updateYoklamaDurumu(this.value);
    });
}

// Yoklama durumunu güncelle
function updateYoklamaDurumu(etkinlikId) {
    // Burada API'den etkinliğin mevcut durumunu alabilirsiniz
    // Şimdilik örnek olarak rastgele bir durum gösteriyoruz
    const durum = Math.random() > 0.5 ? 'Açık' : 'Kapalı';
    const durumElement = document.getElementById('yoklamaMevcutDurum');
    durumElement.textContent = `Yoklama ${durum}`;
    durumElement.className = `durum-text durum-${durum.toLowerCase()}`;
}

// Yoklama durumunu değiştir
function toggleYoklama() {
    const etkinlikId = document.getElementById('yoklamaEtkinlikSec').value;
    if (!etkinlikId) {
        alert('Lütfen bir etkinlik seçin');
        return;
    }

    const durumElement = document.getElementById('yoklamaMevcutDurum');
    const yeniDurum = durumElement.textContent.includes('Açık') ? 'Kapalı' : 'Açık';

    // Burada API'ye istek atarak durumu değiştirebilirsiniz
    // Şimdilik sadece görsel değişiklik yapıyoruz
    durumElement.textContent = `Yoklama ${yeniDurum}`;
    durumElement.className = `durum-text durum-${yeniDurum.toLowerCase()}`;

    alert(`Yoklama ${yeniDurum} olarak güncellendi`);
}

// Etkinlik Paylaş Modal
function showPaylasModal() {
    showModal('paylasModal');

    // Resim önizleme işlemi
    document.getElementById('paylasResim').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const resimOnizleme = document.getElementById('resimOnizleme');
                resimOnizleme.innerHTML = `<img src="${e.target.result}" alt="Etkinlik Resmi">`;
                resimOnizleme.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
}

function showBasvuruListeModal() {
    showModal('basvuruListeModal');


}


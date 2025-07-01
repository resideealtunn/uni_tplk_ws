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
    
    // Tarih validasyonu ekle
    const baslangicTarih = document.getElementById('etkinlikBaslangicTarih');
    const bitisTarih = document.getElementById('etkinlikBitisTarih');
    
    baslangicTarih.addEventListener('change', function() {
        // Başlangıç tarihi seçildiğinde bitiş tarihinin minimum değerini ayarla
        bitisTarih.min = this.value;
        
        // Eğer bitiş tarihi başlangıç tarihinden önceyse temizle
        if (bitisTarih.value && bitisTarih.value <= this.value) {
            bitisTarih.value = '';
        }
    });
    
    bitisTarih.addEventListener('change', function() {
        // Bitiş tarihi başlangıç tarihinden önceyse uyarı ver
        if (baslangicTarih.value && this.value <= baslangicTarih.value) {
            alert('Bitiş tarihi başlangıç tarihinden sonra olmalıdır!');
            this.value = '';
        }
    });
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

// Keşfette Göster/Gizle Modal
function showKesfetModal() {
    showModal('kesfetModal');
    
    // Event listener'ı bir kez ekle
    const selectElement = document.getElementById('kesfetEtkinlikSec');
    if (selectElement) {
        // Önceki event listener'ları temizle
        selectElement.removeEventListener('change', updateKesfetDurum);
        // Yeni event listener ekle
        selectElement.addEventListener('change', function() {
            updateKesfetDurumu(this.value);
        });
    }
}

// Keşfet durumunu güncelle
function updateKesfetDurumu(etkinlikId) {
    const selectElement = document.getElementById('kesfetEtkinlikSec');
    const secilen = selectElement.options[selectElement.selectedIndex];
    const durum = secilen.getAttribute("data-durum");
    
    const durumElement = document.getElementById('kesfetMevcutDurum');
    if (durum === "1") {
        durumElement.textContent = "Keşfette Görünür";
        durumElement.className = "durum-text durum-acik";
    } else if (durum === "0") {
        durumElement.textContent = "Keşfette Gizli";
        durumElement.className = "durum-text durum-kapali";
    } else {
        durumElement.textContent = "Durum bilinmiyor";
        durumElement.className = "durum-text";
    }
}

// Etkinlik form validasyonu
function validateEtkinlikForm() {
    const baslangicTarih = document.getElementById('etkinlikBaslangicTarih').value;
    const bitisTarih = document.getElementById('etkinlikBitisTarih').value;
    
    if (!baslangicTarih) {
        alert('Lütfen etkinlik başlangıç tarihini seçin!');
        return false;
    }
    
    if (!bitisTarih) {
        alert('Lütfen etkinlik bitiş tarihini seçin!');
        return false;
    }
    
    if (bitisTarih <= baslangicTarih) {
        alert('Bitiş tarihi başlangıç tarihinden sonra olmalıdır!');
        return false;
    }
    
    return true;
}


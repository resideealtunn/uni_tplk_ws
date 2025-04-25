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

// Form submit işlemi
document.getElementById('etkinlikEkleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Form verilerini al
    const formData = {
        baslik: document.getElementById('etkinlikBaslik').value,
        kisaBilgi: document.getElementById('etkinlikKisaBilgi').value,
        aciklama: document.getElementById('etkinlikAciklama').value,
        afis: document.getElementById('etkinlikAfiş').files[0]
    };
    
    // Burada form verilerini işleyebilirsiniz
    console.log('Form verileri:', formData);
    
    // Modal'ı kapat
    closeModal('etkinlikEkleModal');
});

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

    // Form submit işlemi
    document.getElementById('paylasForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            etkinlikId: document.getElementById('paylasEtkinlikSec').value,
            kisaBilgi: document.getElementById('paylasKisaBilgi').value,
            aciklama: document.getElementById('paylasAciklama').value,
            resim: document.getElementById('paylasResim').files[0]
        };

        if (!formData.etkinlikId) {
            alert('Lütfen bir etkinlik seçin');
            return;
        }

        // Burada form verilerini API'ye gönderebilirsiniz
        console.log('Paylaşılacak veriler:', formData);
        
        alert('Etkinlik başarıyla paylaşıldı');
        closeModal('paylasModal');
    });
}

function showBasvuruListeModal() {
    showModal('basvuruListeModal');
    
    // Etkinlik seçildiğinde başvuruları getir
    document.getElementById('basvuruListeEtkinlikSec').addEventListener('change', function() {
        const etkinlikId = this.value;
        if (etkinlikId) {
            getBasvurular(etkinlikId);
        } else {
            document.getElementById('basvuruListesi').innerHTML = '';
        }
    });
}

function getBasvurular(etkinlikId) {
    // Burada API'den başvuruları çekebilirsiniz
    // Şimdilik örnek veriler kullanıyoruz
    const ornekBasvurular = [
        {
            adSoyad: 'Ahmet Yılmaz',
            bolum: 'Bilgisayar Mühendisliği',
            cepNo: '0532 123 4567',
            durum: 'Onaylandı'
        },
        {
            adSoyad: 'Ayşe Demir',
            bolum: 'Elektrik-Elektronik Mühendisliği',
            cepNo: '0533 987 6543',
            durum: 'Beklemede'
        },
        {
            adSoyad: 'Mehmet Kaya',
            bolum: 'Makine Mühendisliği',
            cepNo: '0542 456 7890',
            durum: 'Reddedildi'
        }
    ];

    const basvuruListesi = document.getElementById('basvuruListesi');
    basvuruListesi.innerHTML = '';

    ornekBasvurular.forEach(basvuru => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${basvuru.adSoyad}</td>
            <td>${basvuru.bolum}</td>
            <td>${basvuru.cepNo}</td>
            <td class="durum-${basvuru.durum.toLowerCase()}">${basvuru.durum}</td>
        `;
        basvuruListesi.appendChild(tr);
    });
}

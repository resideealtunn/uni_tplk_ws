function openEventModal(item) {
    document.getElementById("modalImage").src = `/images/etkinlik/${item.eb_gorsel}`;
    document.getElementById("modalTitle").innerText = item.eb_isim;
    document.getElementById("modalCommunity").innerText = item.t_isim;
    document.getElementById("modalShortDesc").innerText = item.eb_bilgi;
    document.getElementById("modalLongDesc").innerText = item.eb_metin;

    document.getElementById("eventModal").style.display = "block";
}

document.getElementById("closeModal").addEventListener("click", function () {
    document.getElementById("eventModal").style.display = "none";
});

window.addEventListener("click", function (event) {
    if (event.target === document.getElementById("eventModal")) {
        document.getElementById("eventModal").style.display = "none";
    }
});

// Modal kapatma işlemi
document.getElementById('closeModal').addEventListener('click', function () {
    document.getElementById('eventModal').style.display = 'none';
});
const modal = document.querySelector('.event-modal');
const modalContent = document.querySelector('.modal-content');
const closeBtn = document.querySelector('.close-btn');
function closeModal() {
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}
closeBtn.addEventListener('click', closeModal);

modal.addEventListener('click', function(e) {
    if (!modalContent.contains(e.target)) {
        closeModal();
    }
});

// Mini başvuru modalı açma fonksiyonu
function openMiniModal() {
    const miniModal = document.getElementById('miniModal');
    if (miniModal) {
        miniModal.style.display = 'flex';
    }
}

// Etkinlik kartlarına tıklayınca modal açılması ve başvur butonu için mini modal açılması
window.addEventListener('DOMContentLoaded', function() {
    // Etkinlik kartlarını bul
    const eventCards = document.querySelectorAll('.event-card');
    let currentEId = null;
    let currentTId = null;
    // Her kart için tıklama event'i ekle
    eventCards.forEach(function(card, idx) {
        card.addEventListener('click', function() {
            // Kart içindeki bilgileri al
            const img = card.querySelector('img');
            const title = card.querySelector('h3');
            const community = card.querySelector('p');
            const shortDesc = card.querySelectorAll('p')[1];
            // Modalı doldur
            document.getElementById('modalImage').src = img ? img.src : '';
            document.getElementById('modalTitle').innerText = title ? title.innerText : '';
            document.getElementById('modalCommunity').innerText = community ? community.innerText : '';
            document.getElementById('modalShortDesc').innerText = shortDesc ? shortDesc.innerText : '';
            
            // Tarih bilgilerini al ve formatla
            const baslangicTarih = card.getAttribute('data-tarih');
            const bitisTarih = card.getAttribute('data-bitis_tarihi');
            let tarihText = '';
            if (baslangicTarih && bitisTarih) {
                const baslangic = new Date(baslangicTarih).toLocaleString('tr-TR', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                const bitis = new Date(bitisTarih).toLocaleString('tr-TR', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                tarihText = `${baslangic} - ${bitis}`;
            } else if (baslangicTarih) {
                const baslangic = new Date(baslangicTarih).toLocaleString('tr-TR', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                tarihText = `${baslangic}`;
            }
            document.getElementById('modalTarih').innerText = tarihText;
            
            // Metin alanını doldur
            document.getElementById('modalLongDesc').innerText = card.getAttribute('data-metin') || '';
            
            // Konum bilgisini doldur
            const konum = card.getAttribute('data-konum');
            if (konum && konum.trim() !== '') {
                document.getElementById('modalKonum').innerText = '📍 ' + konum;
                document.getElementById('modalKonum').style.display = 'block';
            } else {
                document.getElementById('modalKonum').style.display = 'none';
            }
            
            document.getElementById('eventModal').style.display = 'block';
            // e_id ve t_id'yi sakla
            currentEId = card.getAttribute('data-e_id');
            currentTId = card.getAttribute('data-t_id');
            document.getElementById('miniEId').value = currentEId;
            document.getElementById('miniTId').value = currentTId;
        });
    });
    // Modal kapatma
    const closeBtn = document.getElementById('closeModal');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            document.getElementById('eventModal').style.display = 'none';
        });
    }
    // Modal dışında tıklayınca kapansın
    const modal = document.getElementById('eventModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
    // Başvur butonu mini modalı açsın
    const applyBtn = document.getElementById('applyBtn');
    const miniModal = document.getElementById('miniModal');
    if (applyBtn && miniModal) {
        applyBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            miniModal.style.display = 'flex';
        });
    }
    // Mini modal kapatma
    const miniClose = document.getElementById('miniClose');
    if (miniClose && miniModal) {
        miniClose.addEventListener('click', function() {
            miniModal.style.display = 'none';
        });
    }
    window.addEventListener('click', function(e) {
        if (e.target === miniModal) {
            miniModal.style.display = 'none';
        }
    });
    // Mini başvuru formu
    const miniApplyForm = document.getElementById('miniApplyForm');
    if (miniApplyForm) {
        miniApplyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const tckn = document.getElementById('minitckNo').value;
            const sifre = document.getElementById('minitckPass').value;
            const e_id = document.getElementById('miniEId').value;
            const t_id = document.getElementById('miniTId').value;
            fetch('/etkinlik-basvuru', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tckn, sifre, e_id, t_id })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    document.getElementById('miniModal').style.display = 'none';
                    miniApplyForm.reset();
                } else if (data.message && data.message.includes('önce topluluğa üye olmalısınız')) {
                    // Yönlendirme: topluluk üye işlemleri sayfasına
                    if (t_id) {
                        window.location.href = '/uyeislemleri/' + encodeURIComponent(document.getElementById('modalCommunity').innerText) + '/' + t_id;
                    }
                }
            })
            .catch(() => {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            });
        });
    }
});

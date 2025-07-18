// Hamburger menü fonksiyonalitesi
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    
    if (hamburger && sidebar) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            sidebar.classList.toggle('active');
        });
        
        // Sidebar dışına tıklandığında menüyü kapat
        document.addEventListener('click', function(e) {
            if (!hamburger.contains(e.target) && !sidebar.contains(e.target)) {
                hamburger.classList.remove('active');
                sidebar.classList.remove('active');
            }
        });
    }
});

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
            
            document.getElementById('eventModal').style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Scroll'u engelle
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
            document.body.style.overflow = 'auto'; // Scroll'u geri aç
        });
    }
    // Modal dışında tıklayınca kapansın
    const modal = document.getElementById('eventModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Scroll'u geri aç
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
    // TC Kimlik No validasyonu
    const tcInput = document.getElementById('minitckNo');
    const tcError = document.getElementById('tcError');
    
    if (tcInput) {
        tcInput.addEventListener('input', function(e) {
            let value = e.target.value;
            
            // Sadece sayı girişine izin ver
            value = value.replace(/[^0-9]/g, '');
            e.target.value = value;
            
            // Hata mesajını temizle
            tcError.style.display = 'none';
            tcError.textContent = '';
            
            // Validasyon kontrolleri
            if (value.length > 0) {
                if (value.length < 11) {
                    tcError.textContent = 'TC Kimlik No 11 haneli olmalıdır.';
                    tcError.style.display = 'block';
                } else if (value.length === 11) {
                    if (value.startsWith('0')) {
                        tcError.textContent = 'TC Kimlik No 0 ile başlayamaz.';
                        tcError.style.display = 'block';
                    } else {
                        // TC Kimlik No algoritma kontrolü (basit)
                        const digits = value.split('').map(Number);
                        const oddSum = digits[0] + digits[2] + digits[4] + digits[6] + digits[8];
                        const evenSum = digits[1] + digits[3] + digits[5] + digits[7];
                        const digit10 = ((oddSum * 7) - evenSum) % 10;
                        const digit11 = (oddSum + evenSum + digits[9]) % 10;
                        
                        if (digit10 !== digits[9] || digit11 !== digits[10]) {
                            tcError.textContent = 'Geçersiz TC Kimlik No.';
                            tcError.style.display = 'block';
                        }
                    }
                }
            }
        });
        
        // Paste event için de kontrol
        tcInput.addEventListener('paste', function(e) {
            setTimeout(() => {
                let value = e.target.value;
                value = value.replace(/[^0-9]/g, '');
                e.target.value = value;
            }, 0);
        });
    }
    
    // Mini başvuru formu
    const miniApplyForm = document.getElementById('miniApplyForm');
    if (miniApplyForm) {
        miniApplyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // TC validasyonu kontrol et
            const tckn = document.getElementById('minitckNo').value;
            const tcError = document.getElementById('tcError');
            
            if (tcError.style.display === 'block') {
                alert('Lütfen geçerli bir TC Kimlik No giriniz.');
                return;
            }
            
            if (tckn.length !== 11) {
                alert('TC Kimlik No 11 haneli olmalıdır.');
                return;
            }
            
            if (tckn.startsWith('0')) {
                alert('TC Kimlik No 0 ile başlayamaz.');
                return;
            }
            
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

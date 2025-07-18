console.log("tplk_etkinlikler.js yüklendi");
// Modal ve başvuru ile ilgili tüm kodlar kaldırıldı.
// Eğer başka işlevler varsa burada bırakılabilir.

document.addEventListener('DOMContentLoaded', function() {
    const eventModal = document.getElementById('eventModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalCommunity = document.getElementById('modalCommunity');
    const modalShortDesc = document.getElementById('modalShortDesc');
    const modalDates = document.getElementById('modalDates');
    const modalLongDesc = document.getElementById('modalLongDesc');
    const applyBtn = document.getElementById('applyBtn');
    const closeModalBtn = document.getElementById('closeModal');

    // Mini başvuru modalı
    const miniModal = document.getElementById('miniModal');
    const miniClose = document.getElementById('miniClose');
    const miniApplyForm = document.getElementById('miniApplyForm');
    let currentEId = null;
    let currentTId = null;

    // Tarih formatla fonksiyonu
    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('tr-TR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Aktif etkinlikler
    document.querySelectorAll('.active-event').forEach(function(card) {
        card.addEventListener('click', function() {
            modalImage.src = card.getAttribute('data-gorsel');
            modalTitle.innerText = card.getAttribute('data-baslik');
            // Topluluk adı
            modalCommunity.innerText = card.closest('.container').querySelector('h2').innerText || '';
            modalShortDesc.innerText = card.getAttribute('data-bilgi');
            
            // Tarihleri formatla ve göster
            const baslangicTarihi = card.getAttribute('data-tarih');
            const bitisTarihi = card.getAttribute('data-bitis-tarihi');
            let datesText = '';
            if (baslangicTarihi) {
                datesText += `<strong>Başlangıç:</strong> ${formatDate(baslangicTarihi)}`;
            }
            if (bitisTarihi && bitisTarihi !== baslangicTarihi) {
                datesText += `<br><strong>Bitiş:</strong> ${formatDate(bitisTarihi)}`;
            }
            modalDates.innerHTML = datesText;
            
            modalLongDesc.innerText = card.getAttribute('data-metin');
            applyBtn.style.display = 'inline-block';
            applyBtn.setAttribute('data-e_id', card.getAttribute('data-e_id'));
            applyBtn.setAttribute('data-t_id', card.getAttribute('data-t_id'));
            eventModal.style.display = 'flex';
            // Body scroll'u devre dışı bırak
            document.body.style.overflow = 'hidden';
        });
    });
    // Geçmiş etkinlikler
    document.querySelectorAll('.past-event').forEach(function(card) {
        card.addEventListener('click', function() {
            modalImage.src = card.getAttribute('data-gorsel');
            modalTitle.innerText = card.getAttribute('data-baslik');
            modalCommunity.innerText = '';
            modalShortDesc.innerText = card.getAttribute('data-bilgi');
            
            // Tarihleri formatla ve göster
            const baslangicTarihi = card.getAttribute('data-tarih');
            const bitisTarihi = card.getAttribute('data-bitis-tarihi');
            let datesText = '';
            if (baslangicTarihi) {
                datesText += `<strong>Başlangıç:</strong> ${formatDate(baslangicTarihi)}`;
            }
            if (bitisTarihi && bitisTarihi !== baslangicTarihi) {
                datesText += `<br><strong>Bitiş:</strong> ${formatDate(bitisTarihi)}`;
            }
            modalDates.innerHTML = datesText;
            
            modalLongDesc.innerText = card.getAttribute('data-metin');
            applyBtn.style.display = 'none';
            eventModal.style.display = 'flex';
            // Body scroll'u devre dışı bırak
            document.body.style.overflow = 'hidden';
        });
    });
    // Modal kapatma
    closeModalBtn.addEventListener('click', function() {
        eventModal.style.display = 'none';
        // Body scroll'unu geri aç
        document.body.style.overflow = 'auto';
    });
    window.addEventListener('click', function(e) {
        if (e.target === eventModal) {
            eventModal.style.display = 'none';
            // Body scroll'unu geri aç
            document.body.style.overflow = 'auto';
        }
    });

    applyBtn.onclick = function() {
        // e_id ve t_id'yi modalda sakla
        document.getElementById('minieId').value = applyBtn.getAttribute('data-e_id');
        document.getElementById('minitId').value = applyBtn.getAttribute('data-t_id');
        miniModal.style.display = 'flex';
    };

    // Mini modal kapatma
    miniClose.addEventListener('click', function() {
        miniModal.style.display = 'none';
    });
    window.addEventListener('click', function(e) {
        if (e.target === miniModal) {
            miniModal.style.display = 'none';
        }
    });

    // Mini başvuru form submit
    miniApplyForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const tckn = document.getElementById('minitckNo').value;
        const sifre = document.getElementById('minitckPass').value;
        const e_id = document.getElementById('minieId').value;
        const t_id = document.getElementById('minitId').value;
        fetch('/etkinlik-basvuru', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ tckn, sifre, e_id, t_id })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                miniModal.style.display = 'none';
                miniApplyForm.reset();
            } else if (data.redirect) {
                // Uye değilse yönlendir
                window.location.href = data.redirect;
            }
        })
        .catch(() => {
            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
        });
    });
});

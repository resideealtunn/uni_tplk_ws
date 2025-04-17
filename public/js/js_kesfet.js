document.querySelectorAll('.event-card').forEach(card => {
    card.addEventListener('click', function () {
        const imgSrc = this.querySelector('img').getAttribute('src');
        const title = this.querySelector('h3').innerText;
        const community = this.querySelectorAll('p')[0].innerText;
        const shortDesc = this.querySelectorAll('p')[1].innerText;

        // Modal bilgilerini doldur
        document.getElementById('modalImage').src = imgSrc;
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalCommunity').innerText = community;
        document.getElementById('modalShortDesc').innerText = shortDesc;
        document.getElementById('modalLongDesc').innerText = "Bu etkinlik hakkında daha detaylı bilgi burada yer alacak. (Buraya uzun açıklama yazılabilir.)";

        // Modal aç
        document.getElementById('eventModal').style.display = 'block';
    });
});

// Modal kapatma işlemi
document.getElementById('closeModal').addEventListener('click', function () {
    document.getElementById('eventModal').style.display = 'none';
});
// Modal ve içerik seçimi
const modal = document.querySelector('.event-modal');
const modalContent = document.querySelector('.modal-content');
const closeBtn = document.querySelector('.close-btn');

// Modal kapatıcı fonksiyon
function closeModal() {
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}

// Çarpıya tıklayınca kapat
closeBtn.addEventListener('click', closeModal);

// Modal dışında bir yere tıklayınca kapat
modal.addEventListener('click', function(e) {
    if (!modalContent.contains(e.target)) {
        closeModal();
    }
});

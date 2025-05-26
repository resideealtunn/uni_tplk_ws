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

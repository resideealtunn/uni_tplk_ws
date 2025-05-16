function openUyeListesiModal() {
    document.getElementById("uyeListeModal").style.display = "block";
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

// Tıklanan alan dışında modal kapatma
window.onclick = function(event) {
    const modals = document.querySelectorAll(".modal");
    modals.forEach((modal) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
}



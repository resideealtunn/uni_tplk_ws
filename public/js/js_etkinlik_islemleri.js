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
function showBasvuruModal() {
    showModal('basvuruModal');
}

// Yoklama Aç/Kapat Modal
function showYoklamaModal() {
    showModal('yoklamaModal');
}

// Etkinlik Paylaş Modal
function showPaylasModal() {
    showModal('paylasModal');

}

function showBasvuruListeModal() {
    showModal('basvuruListeModal');

}



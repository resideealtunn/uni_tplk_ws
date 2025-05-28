function onayla(type) {
    // TODO: Implement API call to approve the update
    alert(`${type} güncellemesi onaylandı.`);
    // After approval, you might want to remove the update item
    // document.querySelector(`[data-type="${type}"]`).remove();
}

function reddet(type) {
    const redSebepDiv = document.getElementById(`${type}-red-sebep`);
    redSebepDiv.style.display = 'block';
}

function redSebepGonder(type) {
    const redSebepTextarea = document.querySelector(`#${type}-red-sebep textarea`);
    const redSebep = redSebepTextarea.value.trim();

    if (!redSebep) {
        alert('Lütfen red sebebini giriniz.');
        return;
    }

    // TODO: Implement API call to reject the update with reason
    alert(`${type} güncellemesi reddedildi. Sebep: ${redSebep}`);

}

// Show content function for sidebar navigation
function showContent(contentId) {
    // Hide all content sections
    document.querySelectorAll('.content').forEach(content => {
        content.style.display = 'none';
    });

    // Show selected content
    document.getElementById(contentId).style.display = 'block';

    // Update active menu item
    document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`.menu-item[onclick*="${contentId}"]`).classList.add('active');
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    // Show the default content (web)
    showContent('web');
});
function showRejectReason(button) {
    const redReasonDiv = button.nextElementSibling;
    redReasonDiv.style.display = (redReasonDiv.style.display === "block") ? "none" : "block";
}

function approve() {
    alert("Topluluk Onaylandı.");
}

function addTopluluk() {
    alert('Topluluk ekleme işlemi başlatıldı!');
    // Buraya form açma ya da başka işlem kodu yazabilirsin
}

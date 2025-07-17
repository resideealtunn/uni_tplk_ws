function showRejectReason(button) {
    const redReasonDiv = button.nextElementSibling;
    redReasonDiv.style.display = (redReasonDiv.style.display === "block") ? "none" : "block";
}

function openModal(text) {
    document.getElementById('modalTextContent').innerText = text;
    const textModal = new bootstrap.Modal(document.getElementById('textModal'));
    textModal.show();
}

function openImage(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}

// Red sebebi validasyonu
function validateRedReason(textarea) {
    const sendButton = textarea.parentElement.querySelector('button[type="submit"]');
    const reason = textarea.value.trim();
    
    if (reason.length === 0) {
        sendButton.disabled = true;
        sendButton.style.opacity = '0.5';
        sendButton.style.cursor = 'not-allowed';
    } else {
        sendButton.disabled = false;
        sendButton.style.opacity = '1';
        sendButton.style.cursor = 'pointer';
    }
}

// Sayfa yüklendiğinde tüm red sebebi textarea'larını kontrol et
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('textarea[name="mesaj"]');
    textareas.forEach(textarea => {
        // İlk yüklemede kontrol et
        validateRedReason(textarea);
        
        // Her değişiklikte kontrol et
        textarea.addEventListener('input', function() {
            validateRedReason(this);
        });
    });
});

  
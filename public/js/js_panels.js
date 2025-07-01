function showContent(id) {
    document.querySelectorAll('.content').forEach(content => {
        content.classList.remove('active');
    });
    document.getElementById(id).classList.add('active');
}

// Sayfa yüklendiğinde "Web Arayüz İşlemleri" açık olsun
showContent('web');

// AJAX form submit for each field (safe)
function showAlert(id, message, success) {
    const el = document.getElementById(id);
    el.innerHTML = `<div class="alert alert-${success ? 'success' : 'danger'}">${message}</div>`;
    setTimeout(() => { el.innerHTML = ''; }, 4000);
}

function safeFormListener(formId, alertId, successMsg, errorMsg) {
    const form = document.getElementById(formId);
    if (!form) return;
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const clickedButton = document.activeElement;
        if (clickedButton && clickedButton.name) {
            formData.append(clickedButton.name, clickedButton.value);
        }
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        
        .then(r => {
            // Her zaman JSON dönmeyebilir, kontrol et
            return r.json().catch(() => null);
        })
        .then(data => {
            if (data && data.success) {
                showAlert(alertId, successMsg, true);
            } else if (data && data.success === false) {
                // Eğer success false ise ama başka bir mesaj varsa, yine de başarıyla kaydedildiyse success göster
                if (data.message && data.message.toLowerCase().includes('başarıyla')) {
                    showAlert(alertId, successMsg, true);
                } else {
                    showAlert(alertId, errorMsg, false);
                }
            } else {
                showAlert(alertId, errorMsg, false);
            }
        })
        .catch(() => showAlert(alertId, errorMsg, false));
    });
}

safeFormListener('logoForm', 'logo-alert', 'Logo güncelleme talebi başarıyla gönderildi.', 'Logo güncelleme talebi gönderilemedi.');
safeFormListener('bgForm', 'bg-alert', 'Arka plan güncelleme talebi başarıyla gönderildi.', 'Arka plan güncelleme talebi gönderilemedi.');
safeFormListener('sloganForm', 'slogan-alert', 'Slogan güncelleme talebi başarıyla gönderildi.', 'Slogan güncelleme talebi gönderilemedi.');
safeFormListener('vizyonForm', 'vizyon-alert', 'Vizyon güncelleme talebi başarıyla gönderildi.', 'Vizyon güncelleme talebi gönderilemedi.');
safeFormListener('misyonForm', 'misyon-alert', 'Misyon güncelleme talebi başarıyla gönderildi.', 'Misyon güncelleme talebi gönderilemedi.');
safeFormListener('tuzukForm', 'tuzuk-alert', 'Tüzük güncelleme talebi başarıyla gönderildi.', 'Tüzük güncelleme talebi gönderilemedi.');
safeFormListener('instagramForm', 'instagram-alert', 'Instagram linki güncelleme talebi başarıyla gönderildi.', 'Instagram linki güncelleme talebi gönderilemedi.');
safeFormListener('whatsappForm', 'whatsapp-alert', 'WhatsApp linki güncelleme talebi başarıyla gönderildi.', 'WhatsApp linki güncelleme talebi gönderilemedi.');
safeFormListener('linkedlnForm', 'linkedln-alert', 'LinkedIn linki güncelleme talebi başarıyla gönderildi.', 'LinkedIn linki güncelleme talebi gönderilemedi.');

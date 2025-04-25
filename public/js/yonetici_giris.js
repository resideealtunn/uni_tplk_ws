document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const tcKimlikInput = document.getElementById('tcKimlik');
    const sifreInput = document.getElementById('sifre');

    // URL'den panel tipini al
    const urlParams = new URLSearchParams(window.location.search);
    const panelType = urlParams.get('panel');
    console.log('Panel Type:', panelType); // Debug için

    // TC Kimlik No validasyonu
    tcKimlikInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) {
            value = value.slice(0, 11);
        }
        e.target.value = value;
    });

    // Form gönderimi
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const tcKimlik = tcKimlikInput.value.trim();
        const sifre = sifreInput.value.trim();

        // Validasyon kontrolleri
        if (tcKimlik.length !== 11) {
            alert('TC Kimlik No 11 haneli olmalıdır.');
            return;
        }

        if (sifre.length < 6) {
            alert('Şifre en az 6 karakter olmalıdır.');
            return;
        }

        // Örnek TC ve şifre kontrolü
        if (tcKimlik === '12345678901' && sifre === 'admin123') {
            // Başarılı giriş - Panel tipine göre yönlendirme
            if (panelType === 'denetim') {
                console.log('Denetim paneline yönlendiriliyor...');
                window.location.href = '/denetim_panel';
            } else {
                console.log('Yönetim paneline yönlendiriliyor...');
                window.location.href = '/yonetici_panel';
            }
        } else {
            // Başarısız giriş
            alert('TC Kimlik No veya şifre hatalı!');
        }
    });
});

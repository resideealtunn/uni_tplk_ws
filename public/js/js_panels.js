function showContent(id) {
    document.querySelectorAll('.content').forEach(content => {
        content.classList.remove('active');
    });
    document.getElementById(id).classList.add('active');
}

// Sayfa yüklendiğinde "Web Arayüz İşlemleri" açık olsun
showContent('web');

// Logo önizleme
document.getElementById('logo').addEventListener('change', function (e) {
    const preview = document.getElementById('logo-preview');
    preview.innerHTML = '';
    const file = e.target.files[0];

    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (event) {
            const img = document.createElement('img');
            img.src = event.target.result;
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = 'Lütfen bir resim dosyası seçin.';
    }
});
function showContent(contentId) {
    // Tüm içerik bölümlerini gizle
    const contents = document.querySelectorAll('.content');
    contents.forEach(content => {
        content.classList.remove('active');
    });

    // Seçilen içerik bölümünü göster
    const selectedContent = document.getElementById(contentId);
    selectedContent.classList.add('active');
}

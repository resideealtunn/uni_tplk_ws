function showContent(id) {
    document.querySelectorAll('.content').forEach(content => {
        content.classList.remove('active');
    });
    document.getElementById(id).classList.add('active');
}

// Sayfa yüklendiğinde "Web Arayüz İşlemleri" açık olsun
showContent('web');

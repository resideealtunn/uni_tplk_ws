// Hamburger menü fonksiyonu

document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    if (hamburger && sidebar) {
        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            hamburger.classList.toggle('active');
            sidebar.classList.toggle('active');
        });
        // Sidebar dışına tıklanınca menüyü kapat
        document.addEventListener('click', function(e) {
            if (!hamburger.contains(e.target) && !sidebar.contains(e.target)) {
                hamburger.classList.remove('active');
                sidebar.classList.remove('active');
            }
        });
    }
}); 
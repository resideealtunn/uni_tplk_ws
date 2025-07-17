// Hamburger menü aç/kapat
const hamburger = document.getElementById('hamburger');
const sidebar = document.getElementById('sidebar');

function openSidebar() {
    sidebar.classList.add('open');
    document.body.classList.add('sidebar-open');
}
function closeSidebar() {
    sidebar.classList.remove('open');
    document.body.classList.remove('sidebar-open');
}

if (hamburger && sidebar) {
    hamburger.addEventListener('click', function(e) {
        e.stopPropagation();
        if (sidebar.classList.contains('open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });
    // Sidebar dışına tıklanınca kapat
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
            if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
                closeSidebar();
            }
        }
    });
    // Menüden bir linke tıklanınca sidebar kapansın (mobilde)
    sidebar.querySelectorAll('a.menu-item').forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        });
    });
}
// Ekran boyutu değişirse sidebar kapalıya dön
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        closeSidebar();
    }
}); 
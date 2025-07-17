// Hamburger menü aç/kapat - Denetim Etkinlik
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
        if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && e.target !== hamburger) {
            closeSidebar();
        }
    });
    // Sidebar içindeki linke tıklanınca kapat (mobilde)
    sidebar.addEventListener('click', function(e) {
        if (e.target.classList.contains('menu-item')) {
            closeSidebar();
        }
    });
}
// Ekran boyutu değişirse sidebar'ı kapat
window.addEventListener('resize', function() {
    if (window.innerWidth > 900) {
        closeSidebar();
    }
}); 
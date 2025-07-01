// Anasayfa JavaScript dosyası
document.addEventListener('DOMContentLoaded', function() {
    console.log('Anasayfa yüklendi');
    
    // Aktif menü öğesini işaretle
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('.menu li a');
    
    menuItems.forEach(item => {
        if (item.getAttribute('href') === currentPath) {
            item.classList.add('active');
        }
    });
    
    // Ana sayfa için özel işlemler
    if (currentPath === '/' || currentPath === '') {
        // Ana sayfa aktif
        const homeLink = document.querySelector('.menu li a[href="/"]');
        if (homeLink) {
            homeLink.classList.add('active');
        }
    }
    
    // Özellik kartları için hover efektleri
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // İstatistik kartları için animasyon
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Smooth scroll için
    const featureButtons = document.querySelectorAll('.feature-btn');
    featureButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Eğer aynı sayfada bir link ise smooth scroll yap
            const href = this.getAttribute('href');
            if (href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // Sayfa yüklendiğinde animasyon
    setTimeout(() => {
        const welcomeSection = document.querySelector('.welcome-section');
        if (welcomeSection) {
            welcomeSection.style.opacity = '0';
            welcomeSection.style.transform = 'translateY(20px)';
            welcomeSection.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            
            setTimeout(() => {
                welcomeSection.style.opacity = '1';
                welcomeSection.style.transform = 'translateY(0)';
            }, 100);
        }
        
        // Özellik kartları için staggered animasyon
        const featureCards = document.querySelectorAll('.feature-card');
        featureCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 200 + (index * 100));
        });
        
        // İstatistik kartları için animasyon
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.8)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            }, 600 + (index * 150));
        });
    }, 100);
    
    // Slider/carousel için butonlar
    const slider = document.getElementById('eventSlider');
    const btnLeft = document.getElementById('sliderBtnLeft');
    const btnRight = document.getElementById('sliderBtnRight');
    if (slider && btnLeft && btnRight) {
        const items = slider.querySelectorAll('.slider-item');
        let current = 0;
        const showItems = () => {
            items.forEach((item, i) => {
                item.classList.remove('active');
                if (i >= current && i < current + 4) {
                    item.classList.add('active');
                }
            });
        };
        showItems();
        function goLeft() {
            if (current > 0) {
                current -= 4;
                if (current < 0) current = 0;
                showItems();
                updatePageDots();
            } else {
                // Başa gelince en sona git
                current = Math.max(0, items.length - 4);
                showItems();
                updatePageDots();
            }
        }
        function goRight() {
            if (current + 4 < items.length) {
                current += 4;
                showItems();
                updatePageDots();
            } else {
                // Sona gelince başa dön
                current = 0;
                showItems();
                updatePageDots();
            }
        }
        btnLeft.addEventListener('click', goLeft);
        btnRight.addEventListener('click', goRight);
        // Sayfa numaralarına tıklama
        const pageDots = document.querySelectorAll('.page-dot');
        pageDots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                current = index * 4;
                showItems();
                updatePageDots();
            });
        });
        
        // Aktif sayfa numarasını güncelle
        function updatePageDots() {
            const activePage = Math.floor(current / 4);
            pageDots.forEach((dot, index) => {
                dot.classList.remove('active');
                if (index === activePage) {
                    dot.classList.add('active');
                }
            });
        }
        
        // Otomatik kaydırma
        setInterval(goRight, 4000);
    }
});

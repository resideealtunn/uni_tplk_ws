// Anasayfa JavaScript dosyası
document.addEventListener('DOMContentLoaded', function() {
    console.log('Anasayfa yüklendi');
    
    // Hamburger menü fonksiyonalitesi
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    
    if (hamburger && sidebar) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            sidebar.classList.toggle('active');
        });
        
        // Sidebar dışına tıklandığında menüyü kapat
        document.addEventListener('click', function(e) {
            if (!hamburger.contains(e.target) && !sidebar.contains(e.target)) {
                hamburger.classList.remove('active');
                sidebar.classList.remove('active');
            }
        });
    }
    
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
        let autoSlideInterval;
        
        // Mobil cihazlarda tek etkinlik, desktop'ta 4 etkinlik göster
        const isMobile = window.innerWidth <= 768;
        const itemsPerPage = isMobile ? 1 : 4;
        const totalPages = Math.ceil(items.length / itemsPerPage);
        
        const showItems = () => {
            items.forEach((item, i) => {
                item.classList.remove('active');
                const startIndex = current * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                if (i >= startIndex && i < endIndex) {
                    item.classList.add('active');
                }
            });
        };
        
        const updatePageDots = () => {
            const pageDots = document.querySelectorAll('.page-dot');
            pageDots.forEach((dot, index) => {
                dot.classList.remove('active');
                if (index === current) {
                    dot.classList.add('active');
                }
            });
        };
        
        const startAutoSlide = () => {
            autoSlideInterval = setInterval(() => {
                goRight();
            }, 8000); // 8 saniye
        };
        
        const stopAutoSlide = () => {
            if (autoSlideInterval) {
                clearInterval(autoSlideInterval);
            }
        };
        
        const goLeft = () => {
            if (current > 0) {
                current--;
            } else {
                current = totalPages - 1;
            }
            showItems();
            updatePageDots();
        };
        
        const goRight = () => {
            if (current < totalPages - 1) {
                current++;
            } else {
                current = 0;
            }
            showItems();
            updatePageDots();
        };
        
        // İlk gösterim
        showItems();
        updatePageDots();
        
        // Manuel kontroller
        btnLeft.addEventListener('click', () => {
            stopAutoSlide();
            goLeft();
            startAutoSlide();
        });
        
        btnRight.addEventListener('click', () => {
            stopAutoSlide();
            goRight();
            startAutoSlide();
        });
        
        // Sayfa numaralarına tıklama
        const pageDots = document.querySelectorAll('.page-dot');
        pageDots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                stopAutoSlide();
                current = index;
                showItems();
                updatePageDots();
                startAutoSlide();
            });
        });
        
        // Mouse hover durumunda otomatik geçişi durdur
        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', startAutoSlide);
        
        // Otomatik geçişi başlat
        startAutoSlide();
        
        // Pencere boyutu değiştiğinde slider'ı yeniden hesapla
        window.addEventListener('resize', function() {
            const newIsMobile = window.innerWidth <= 768;
            const newItemsPerPage = newIsMobile ? 1 : 4;
            const newTotalPages = Math.ceil(items.length / newItemsPerPage);
            
            // Eğer mobil durumu değiştiyse slider'ı sıfırla
            if (newIsMobile !== isMobile) {
                current = 0;
                itemsPerPage = newItemsPerPage;
                totalPages = newTotalPages;
                showItems();
                updatePageDots();
            }
        });
    }
});

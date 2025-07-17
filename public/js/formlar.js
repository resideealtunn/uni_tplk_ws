document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('.menu li a');
    
    menuItems.forEach(item => {
        if (item.getAttribute('href') === currentPath) {
            item.classList.add('active');
        }
    });

    // Form arama işlemleri için değişkenler
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');

    // Temizle butonu işlevi
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            clearSearchBtn.style.display = 'none';
            window.location.reload();
        });
    }

    searchInput.addEventListener('keyup', function () {
        let query = this.value.trim();

        // Temizle butonunu göster/gizle
        if (clearSearchBtn) {
            if (query.length > 0) {
                clearSearchBtn.style.display = 'block';
            } else {
                clearSearchBtn.style.display = 'none';
            }
        }

        // Eğer arama kutusu boşsa, sayfayı yenile (tüm formları göster)
        if (query === '') {
            window.location.reload();
            return;
        }

        // Arama yapmak için minimum 1 karakter gerekli
        if (query.length < 1) {
            return;
        }

        // Debounce: 300ms bekle
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    function performSearch(query) {
        // Loading göstergesini göster
        const searchStatus = document.getElementById('searchStatus');
        if (searchStatus) {
            searchStatus.style.display = 'block';
        }

        fetch('/form-ara?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                // Loading göstergesini gizle
                if (searchStatus) {
                    searchStatus.style.display = 'none';
                }
                
                let formList = document.querySelector('.form-list');
                formList.innerHTML = '';

                if (data.length === 0) {
                    formList.innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #666;">
                            <h3>Arama sonucu bulunamadı</h3>
                            <p>"${query}" ile eşleşen form bulunamadı.</p>
                        </div>`;
                    return;
                }

                data.forEach(form => {
                    let formItem = `
                        <div class="form-item">
                            <a href="/docs/formlar/${form.dosya}" target="_blank">
                                <span>${form.isim}</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>`;
                    formList.innerHTML += formItem;
                });
            })
            .catch(error => {
                // Loading göstergesini gizle
                if (searchStatus) {
                    searchStatus.style.display = 'none';
                }
                
                console.error('Hata oluştu:', error);
                let formList = document.querySelector('.form-list');
                formList.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #dc3545;">
                        <h3>Arama sırasında bir hata oluştu</h3>
                        <p>Lütfen tekrar deneyin.</p>
                    </div>`;
            });
    }
});

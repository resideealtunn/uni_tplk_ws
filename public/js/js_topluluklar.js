// Arama işlemleri için değişkenler
let searchTimeout;
const searchInput = document.getElementById("searchInput");
const clearSearchBtn = document.getElementById("clearSearch");

// Temizle butonu işlevi
clearSearchBtn.addEventListener("click", function() {
    searchInput.value = "";
    clearSearchBtn.style.display = "none";
    window.location.reload();
});

searchInput.addEventListener("keyup", function () {
    let query = this.value.trim();

    // Temizle butonunu göster/gizle
    if (query.length > 0) {
        clearSearchBtn.style.display = "block";
    } else {
        clearSearchBtn.style.display = "none";
    }

    // Eğer arama kutusu boşsa, sayfayı yenile (tüm toplulukları göster)
    if (query === "") {
        window.location.reload();
        return;
    }

    // Arama yapmak için minimum 1 karakter gerekli
    if (query.length < 1) {
        return;
    }

    // Debounce: Her tuş vuruşunda değil, kullanıcı yazmayı bitirdikten sonra arama yap
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        performSearch(query);
    }, 300);
});

function performSearch(query) {
    // Loading göstergesini göster
    const searchStatus = document.getElementById("searchStatus");
    searchStatus.style.display = "block";

    fetch('/topluluk-ara?q=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            // Loading göstergesini gizle
            searchStatus.style.display = "none";
            
            let communityList = document.getElementById("communityList");
            communityList.innerHTML = "";

            if (data.length === 0) {
                communityList.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">
                        <h3>Arama sonucu bulunamadı</h3>
                        <p>"${query}" ile eşleşen topluluk bulunamadı.</p>
                    </div>`;
                return;
            }

            data.forEach(item => {
                let eventCard = `
                    <div class="event-card">
                        <a href="/topluluk/${encodeURIComponent(item.isim)}/${item.id}">
                            <img src="/images/logo/${item.gorsel}" alt="Topluluk Logosu" class="community-logo">
                            <div class="event-details">
                                <h3>${item.isim}</h3>
                            </div>
                        </a>
                    </div>`;

                communityList.innerHTML += eventCard;
            });
        })
        .catch(error => {
            // Loading göstergesini gizle
            searchStatus.style.display = "none";
            
            console.error("Hata oluştu:", error);
            let communityList = document.getElementById("communityList");
            communityList.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #dc3545;">
                    <h3>Arama sırasında bir hata oluştu</h3>
                    <p>Lütfen tekrar deneyin.</p>
                </div>`;
        });
}

document.getElementById("searchInput").addEventListener("keyup", function () {
    let query = this.value;

    fetch('/topluluk-ara?q=' + query)
        .then(response => response.json())
        .then(data => {
            let communityList = document.getElementById("communityList");
            communityList.innerHTML = "";

            data.forEach(item => {
                let eventCard = `
                    <div class="event-card">
                        <a href="/topluluk/${item.id}">
                            <img src="/images/logo/${item.gorsel}" alt="Topluluk Logosu" class="community-logo">
                            <div class="event-details">
                                <h3>${item.isim}</h3>
                            </div>
                        </a>
                    </div>`;

                communityList.innerHTML += eventCard;
            });
        })
        .catch(error => console.error("Hata oluştu:", error));
});

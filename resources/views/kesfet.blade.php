 <!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keşfet Ekranı</title>
    <link rel="stylesheet" href="{{ asset('css/style_kesfet.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <style>
        .menu li a.active {
            color: #FFA500;
        }
        .menu li a {
            padding-left: 10px;
        }
        .title-section {
            background-color:rgb(163, 219, 252);
            padding: 30px 0;
            margin-bottom: 30px;
        }
        #contentTitle {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('images/logo/neu_logo.png') }}" alt="NEU Logo">
            <div class="logo-text">
                <h3>NECMETTİN<br>ERBAKAN<br>ÜNİVERSİTESİ</h3>
                <p>Öğrenci Toplulukları Koordinatörlüğü</p>
            </div>
        </div>
        <ul class="menu">
            <li><a href="/" id="homeBtn">Ana Sayfa</a></li>
            <li><a href="/kesfet" id="kesfetBtn" class="active">Keşfet</a></li>
            <li><a href="/topluluklar" id="communitiesBtn">Topluluklar</a></li>
            <li><a href="/formlar" >Formlar</a></li>
            <li><a href={{route('yonetici.giris')}} id="adminBtn">Yönetici İşlemleri</a></li>
        </ul>
    </div>
    <div class="title-section">
        <h1 id="contentTitle">NEÜ ETKİNLİKLERİ KEŞFET</h1>
        <div class="page-description">
            <p>Necmettin Erbakan Üniversitesi'nin renkli dünyasına hoş geldiniz! Bu platformda yaklaşan etkinlikleri keşfedebilir, yeni topluluklara üye olabilir ve üniversite hayatınızı daha da zenginleştirebilirsiniz. Öğrenci topluluklarımızın düzenlediği seminerler, workshoplar, sosyal etkinlikler ve daha fazlasını burada bulabilirsiniz. Hemen bir etkinliğe katılın ve yeni arkadaşlıklar kurun!</p>
        </div>
    </div>
    <!-- Content Area -->
    <div class="content">
        <div id="contentArea" class="explore-grid">
            @foreach ($kesfet as $item)
                <div class="event-card" data-e_id="{{ $item->eb_id }}" data-t_id="{{ $item->t_id }}" data-metin="{{ $item->eb_metin }}" data-tarih="{{ $item->eb_tarih }}" data-bitis_tarihi="{{ $item->eb_bitis_tarihi }}" data-konum="{{ $item->eb_konum }}">
                    <div class="event-date">
                        @if($item->eb_tarih)
                            {{ \Carbon\Carbon::parse($item->eb_tarih)->format('d.m.Y') }}
                        @endif
                    </div>
                    <div class="event-place">
                        @if($item->eb_konum)
                            📍 {{ $item->eb_konum }}
                        @endif
                    </div>
                    <img src="{{ asset('images/etkinlik/'.$item->eb_gorsel) }}" alt="Etkinlik Görseli">
                    <div class="event-details">
                        <h3>{{ $item->eb_isim }}</h3>
                        <p>{{ $item->t_isim }}</p>
                        <p>{{ $item->eb_bilgi }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Sayfalama -->
<div class="pagination-container">
    <div class="pagination">
        @if ($kesfet->hasPages())
            @if ($kesfet->onFirstPage())
                <span class="pagination-item disabled">« Önceki</span>
            @else
                <a href="{{ $kesfet->previousPageUrl() }}" class="pagination-item">« Önceki</a>
            @endif

            @foreach ($kesfet->getUrlRange(1, $kesfet->lastPage()) as $page => $url)
                @if ($page == $kesfet->currentPage())
                    <span class="pagination-item active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination-item">{{ $page }}</a>
                @endif
            @endforeach

            @if ($kesfet->hasMorePages())
                <a href="{{ $kesfet->nextPageUrl() }}" class="pagination-item">Sonraki »</a>
            @else
                <span class="pagination-item disabled">Sonraki »</span>
            @endif
        @endif
    </div>
</div>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Adres</h3>
            <p>Yaka Mah. Yeni Meram Cad. Kasım Halife Sok. No:11 (B Blok) 42090 Meram/Konya</p>
        </div>
        <div class="footer-section">
            <h3>İletişim</h3>
            <p>Tel : 0 332 221 0 561</p>
            <p>Fax : 0 332 235 98 03</p>
        </div>
        <div class="footer-section">
            <h3>Sosyal Medya & Eposta</h3>
            <div class="social-icons">
                <a href="https://www.facebook.com/NEUniversitesi" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://x.com/NEUniversitesi" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/neuniversitesi/?source=omni_redirect" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com/school/neuniversitesi/about/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://www.youtube.com/necmettinerbakan%C3%BCniversitesitv" target="_blank"><i class="fab fa-youtube"></i></a>
            </div>
            <p>topluluk@erbakan.edu.tr</p>
        </div>
    </div>
    <div class="footer-bottom">
        © 2022 Necmettin Erbakan Üniversitesi
    </div>
</footer>

<!-- Modal -->
<div class="event-modal" id="eventModal">
    <div class="modal-content">
        <div class="modal-left">
            <img id="modalImage" src="" alt="Etkinlik Detayı" style="width:500px; height: 500px; border-radius: 10px;">
        </div>
        <div class="modal-right">
            <h3 id="modalTitle"></h3>
            <p id="modalCommunity"></p>
            <p id="modalShortDesc"></p>
            <p id="modalTarih"></p>
            <p id="modalKonum"></p>
            <p id="modalLongDesc"></p>
            <button class="apply-btn" id="applyBtn">Etkinliğe Başvur</button>
        </div>
        <button class="close-btn" id="closeModal">&times;</button>
    </div>
</div>

<!-- Başvuru Modalı (Sade ve küçük) -->
<div class="mini-modal" id="miniModal">
    <div class="mini-modal-content">
        <span class="mini-close" id="miniClose">&times;</span>
        <h4>Etkinliğe Başvuru</h4>
        <form id="miniApplyForm">
            <input type="hidden" id="miniEId" name="miniEId">
            <input type="hidden" id="miniTId" name="miniTId">
            <label for="minitckNo">TC Kimlik No</label>
            <input type="text" id="minitckNo" name="minitckNo" required>
            <label for="minitckPass">Tek Şifre</label>
            <input type="password" id="minitckPass" name="minitckPass" required>
            <button type="submit">Başvur</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/js_kesfet.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const menuItems = document.querySelectorAll('.menu li a');
        menuItems.forEach(item => {
            if (item.getAttribute('href') === currentPath) {
                item.classList.add('active');
            }
        });
        // Mini başvuru modalı
        const applyBtn = document.getElementById('applyBtn');
        const miniModal = document.getElementById('miniModal');
        const miniClose = document.getElementById('miniClose');
        const miniApplyForm = document.getElementById('miniApplyForm');
        if(applyBtn && miniModal) {
            applyBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                miniModal.style.display = 'flex';
            });
        }
        if(miniClose && miniModal) {
            miniClose.addEventListener('click', function() {
                miniModal.style.display = 'none';
            });
        }
        window.addEventListener('click', function(e) {
            if (e.target === miniModal) {
                miniModal.style.display = 'none';
            }
        });
        if(miniApplyForm) {
            miniApplyForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // Sadece backend'den dönen mesaj JS'de gösterilecek, burada sabit alert yok
            });
        }
    });
</script>
</body>
</html>



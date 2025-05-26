 <!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        </div>
        <ul class="menu">
            <li><a href="/" id="homeBtn">Ana Sayfa</a></li>
            <li><a href="/topluluklar" id="communitiesBtn">Topluluklar</a></li>
            <li><a href="/formlar" >Formlar</a></li>
            <li><a href={{route('yonetici.giris')}} id="adminBtn">Yönetici İşlemleri</a></li>
        </ul>
    </div>
    <div class="title-section">
        <h1 id="contentTitle">NEÜ ETKİNLİKLERİ KEŞFET</h1>
    </div>
    <!-- Content Area -->
    <div class="content">
        <div id="contentArea" class="explore-grid">
            @foreach ($kesfet as $item)
                <div class="event-card" onclick="openEventModal({{ json_encode($item) }})">
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
                <p id="modalLongDesc"></p>
                <button class="apply-btn" id="applyBtn">Başvur</button>
            </div>
            <button class="close-btn" id="closeModal"></button>
        </div>
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
                <i class="fab fa-facebook-f"></i>
                <i class="fab fa-twitter"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-linkedin-in"></i>
                <i class="fab fa-youtube"></i>
            </div>
            <p>topluluk@erbakan.edu.tr</p>
        </div>
    </div>
    <div class="footer-bottom">
        © 2022 Necmettin Erbakan Üniversitesi
    </div>
</footer>

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
    });
</script>
</body>
</html>



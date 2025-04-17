 <!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keşfet Ekranı</title>
    <link rel="stylesheet" href="{{ asset('css/style_kesfet.css') }}">
    <style>
        .menu li a.active {
            color: #FFA500;
        }
        .menu li a {
            padding-left: 10px;
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
            <li><a href="/kesfet" id="homeBtn">Ana Sayfa</a></li>
            <li><a href="/topluluklar" id="communitiesBtn">Topluluklar</a></li>
            <li><a href="#" id="formsBtn">Formlar</a></li>
            <li><a href={{route('yonetici.giris')}} id="adminBtn">Yönetici İşlemleri</a></li>
        </ul>
    </div>

    <!-- Content Area -->
    <div class="content">
        <h1 id="contentTitle">NEÜ Etkinlikleri KEŞFET</h1>
        <div id="contentArea" class="explore-grid">
            @foreach ($kesfet as $item)
                <div class="event-card">
                    <img src="{{ asset('images/etkinlik/'.$item->eb_gorsel) }}" alt="Etkinlik Görseli">
                    <div class="event-details">
                        <h3>{{ $item->eb_isim }}</h3>
                        <p>{{ $item->t_isim }}</p>
                        <p>{{ $item->eb_metin }}</p>
                    </div>
                </div>
            @endforeach
        </div>
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
    });
</script>
</body>
</html>



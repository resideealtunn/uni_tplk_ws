<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topluluklar</title>
    <link rel="stylesheet" href="{{ asset('css/style_topluluklar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                <img src="{{ asset('images/logo/neu_logo.png') }}">
            </div>
            <ul class="menu">
                <li><a href="/kesfet">Ana Sayfa</a></li>
                <li><a href="/topluluklar" class="active">Topluluklar</a></li>
                <li><a href="/formlar">Formlar</a></li>
                <li><a href="{{route('yonetici.giris')}}">Yönetici İşlemleri</a></li>
            </ul>
        </div>

        <!-- İçerik -->
        <div class="content">
            <div id="contentTitle">öğrenci toplulukları koordinatörlüğü</div>

            <!-- Bilgi Kutuları -->
            <div style="display: flex; justify-content: center; gap: 30px; margin-bottom: 40px;">
                <div style="background-color: #fff; border: 2px solid #003366; width: 200px; height: 100px; display: flex; flex-direction: column; justify-content: center; align-items: center; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <span style="font-size: 16px; font-weight: bold; color: #003366;">TOPLULUK SAYISI</span>
                    <span id="communityCount" style="font-size: 24px; font-weight: bold; margin-top: 5px;">{{count($topluluklar)}}</span>
                </div>

                <div style="background-color: #fff; border: 2px solid #003366; width: 200px; height: 100px; display: flex; flex-direction: column; justify-content: center; align-items: center; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <span style="font-size: 16px; font-weight: bold; color: #003366;">ÜYE SAYISI</span>
                    <!--! Değiştirilecek !-->
                    <span style="font-size: 24px; font-weight: bold; margin-top: 5px;">{{count($uyeler)}}</span>
                </div>
            </div>

            <!-- Topluluklarımız Başlık -->
            <h2 style="text-align: center; font-size: 28px; margin-bottom: 20px; color: #003366;">TOPLULUKLARIMIZ</h2>

            <!-- Arama Kutusu -->
            <div style="text-align: center; margin-bottom: 30px;">
                <input type="text" id="searchInput" placeholder="Topluluk ara..." style="padding: 10px 20px; width: 300px; border: 2px solid #003366; border-radius: 25px;">
            </div>

            <!-- Topluluk Listesi -->
            <div class="explore-grid" id="communityList">
                @foreach($topluluklar as $item)
                    <div class="event-card">
                        <a href="{{ route('topluluk_anasayfa', ['isim' => $item->isim, 'id' => $item->id]) }}">
                            <img src="{{ asset('images/logo/'.$item->gorsel) }}" alt="Topluluk Logosu" class="community-logo">
                            <div class="event-details">
                                <h3>{{ $item->isim }}</h3>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>


            <!-- Sayfalama -->
        <div class="pagination" id="paginationContainer"></div>
    </div>
    </div>

    <!-- Footer -->
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
    <script src="{{ asset('js/js_topluluklar.js') }}"></script>
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

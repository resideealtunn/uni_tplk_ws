<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formlar</title>
    <link rel="stylesheet" href="{{ asset('css/formlar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <style>
        .menu li a.active {
            color: #FFA500;
        }
        .menu li a {
            padding-left: 10px;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .content {
            flex: 1;
            padding: 0px;
        }
        .form-list {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-item {
            background-color: #fff;
            border: 2px solid #003366;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .form-item:hover {
            transform: translateY(-2px);
        }
        .form-item a {
            color: #003366;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .form-item i {
            color: #003366;
        }
    </style>
</head>

<body>
    <!-- Hamburger Menü -->
    <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <img src="{{ asset('images/logo/neu_logo.png') }}" alt="NEU Logo">
                <div class="logo-text">
                    <h3>NECMETTİN<br>ERBAKAN<br>ÜNİVERSİTESİ</h3>
                    <p>Öğrenci Toplulukları Koordinatörlüğü</p>
                </div>
            </div>
            <ul class="menu">
                <li><a href="/">Ana Sayfa</a></li>
                <li><a href="/kesfet">Keşfet</a></li>
                <li><a href="/topluluklar" >Topluluklar</a></li>
                <li><a href="/formlar" class="active">Formlar</a></li>
                <li><a href="{{route('yonetici.giris')}}">Yönetici İşlemleri</a></li>
            </ul>
        </div>

        <!-- İçerik -->
        <div class="content">
            <div class="title-section">
                <h1 id="contentTitle">ÖĞRENCİ TOPLULUKLARI KOORDİNATÖRLÜĞÜ</h1>
            </div>

            <!-- Formlar Başlık -->
            <h2 style="text-align: center; font-size: 28px; margin-bottom: 20px; color: #003366;">FORMLAR</h2>

            <!-- Arama Kutusu -->
            <div style="text-align: center; margin-bottom: 30px;">
                <input type="text" id="searchInput" placeholder="Form ara..." style="padding: 10px 20px; width: 300px; border: 2px solid #003366; border-radius: 25px; padding-right: 40px;">
                <button id="clearSearch" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #003366; cursor: pointer; font-size: 16px; display: none;">✕</button>
            </div>
            <div id="searchStatus" style="text-align: center; margin-bottom: 20px; display: none;">
                <span style="color: #003366; font-size: 14px;">Aranıyor...</span>
            </div>

            <!-- Form Listesi -->
            <div class="form-list">
                @foreach($forms as $form)
                <div class="form-item">
                    <a href="{{asset('docs/formlar/'.$form->dosya)}}" target="_blank">
                        <span>{{ $form->isim }}</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Sayfalama -->
            <div class="pagination">
                @if ($currentPage > 1)
                    <a href="{{ route('formlar', ['page' => $currentPage - 1]) }}">&laquo; Önceki</a>
                @endif

                @for($i = 1; $i <= $lastPage; $i++)
                    @if($i == $currentPage)
                        <span class="current-page">{{ $i }}</span>
                    @else
                        <a href="{{ route('formlar', ['page' => $i]) }}">{{ $i }}</a>
                    @endif
                @endfor

                @if ($currentPage < $lastPage)
                    <a href="{{ route('formlar', ['page' => $currentPage + 1]) }}">Sonraki &raquo;</a>
                @endif
            </div>
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

    <script src="{{ asset('js/formlar.js') }}"></script>
    <script src="{{ asset('js/formlar_menu.js') }}"></script>
</body>

</html>

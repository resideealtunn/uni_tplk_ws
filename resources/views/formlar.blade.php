<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formlar</title>
    <link rel="stylesheet" href="{{ asset('css/style_topluluklar.css') }}">
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
            padding: 20px;
        }
        .title-section {
            background-color: rgb(163, 219, 252);
            padding: 30px 0;
            margin-bottom: 30px;
            width: 100%;
        }
        #contentTitle {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            margin-top: 20px;
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
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <img src="{{ asset('images/logo/neu_logo.png') }}">
            </div>
            <ul class="menu">
                <li><a href="/kesfet">Ana Sayfa</a></li>
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
                <input type="text" id="searchInput" placeholder="Form ara..." style="padding: 10px 20px; width: 300px; border: 2px solid #003366; border-radius: 25px;">
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
            <div class="pagination" style="text-align: center; margin-top: 20px;">
                @if ($currentPage > 1)
                    <a href="{{ route('formlar', ['page' => $currentPage - 1]) }}" style="margin-right: 10px;">&laquo; Önceki</a>
                @endif

                <span class="current-page" style="margin: 0 10px;">Sayfa {{ $currentPage }} / {{ $lastPage }}</span>

                @if ($currentPage < $lastPage)
                    <a href="{{ route('formlar', ['page' => $currentPage + 1]) }}" style="margin-left: 10px;">Sonraki &raquo;</a>
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

    <script src="{{ asset('js/formlar.js') }}"></script>
</body>

</html>

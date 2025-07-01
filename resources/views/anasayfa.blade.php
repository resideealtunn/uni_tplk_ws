<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa - Öğrenci Toplulukları Koordinatörlüğü</title>
    <link rel="stylesheet" href="{{ asset('css/anasayfa.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
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
            <li><a href="/" class="active">Ana Sayfa</a></li>
            <li><a href="/kesfet">Keşfet</a></li>
            <li><a href="/topluluklar">Topluluklar</a></li>
            <li><a href="/formlar">Formlar</a></li>
            <li><a href="{{route('yonetici.giris')}}">Yönetici İşlemleri</a></li>
        </ul>
    </div>

    <!-- İçerik -->
    <div class="content">
        <div class="title-section">
            <h1 id="contentTitle">ÖĞRENCİ TOPLULUKLARI KOORDİNATÖRLÜĞÜ</h1>
        </div>

        <div class="main-content">
            <div class="welcome-section">
                <h2>Hoş Geldiniz!</h2>
                <p>Necmettin Erbakan Üniversitesi Öğrenci Toplulukları Koordinatörlüğü'ne hoş geldiniz. Bu platform üzerinden üniversitemizin öğrenci topluluklarını keşfedebilir, etkinliklere katılabilir ve topluluklara üye olabilirsiniz.</p>
            </div>

            <div class="stats-section">
                <h2>İstatistikler</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">{{ $topluluk_sayisi ?? 0 }}</div>
                        <div class="stat-label">Aktif Topluluk</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $uye_sayisi ?? 0 }}</div>
                        <div class="stat-label">Toplam Üye</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $etkinlik_sayisi ?? 0 }}</div>
                        <div class="stat-label">Toplam Etkinlik</div>
                    </div>
                </div>
            </div>

            <div class="slider-section">
                <h2 class="slider-title">Geçmiş Etkinliklerimiz</h2>
                <div class="event-slider" id="eventSlider" data-count="{{ count($gecmis_etkinlikler) }}">
                    @foreach($gecmis_etkinlikler as $i => $etkinlik)
                        <a href="{{ route('etkinlikler', ['topluluk_isim' => Str::slug($etkinlik->topluluk_adi), 'topluluk_id' => $etkinlik->topluluk_id]) }}" class="slider-item{{ $i < 4 ? ' active' : '' }}" data-index="{{ $i }}" style="text-decoration:none; color:inherit;">
                            <div class="slider-img-wrapper">
                                <img src="{{ asset('images/etkinlik/'.$etkinlik->resim) }}" alt="{{ $etkinlik->etkinlik_adi }}" class="slider-img">
                            </div>
                            <div class="slider-info">
                                <div class="slider-title">{{ $etkinlik->etkinlik_adi }}</div>
                                <div class="slider-topluluk" style="color:#1956a3; font-weight:600; text-align:center; margin:6px 0 6px 0;">{{ $etkinlik->topluluk_adi }}</div>
                                <div class="slider-bilgi">{{ $etkinlik->bilgi }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <button class="slider-btn slider-btn-left" id="sliderBtnLeft"><i class="fas fa-chevron-left"></i></button>
                <button class="slider-btn slider-btn-right" id="sliderBtnRight"><i class="fas fa-chevron-right"></i></button>
                <!-- Sayfa Numaraları -->
                <div class="slider-pagination" id="sliderPagination">
                    @php
                        $totalPages = ceil(count($gecmis_etkinlikler) / 4);
                    @endphp
                    @for($i = 1; $i <= $totalPages; $i++)
                        <span class="page-dot{{ $i == 1 ? ' active' : '' }}" data-page="{{ $i - 1 }}">{{ $i }}</span>
                    @endfor
                </div>
            </div>

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

<script src="{{ asset('js/anasayfa.js') }}"></script>
</body>
</html>

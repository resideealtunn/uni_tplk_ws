<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haberler - Öğrenci Toplulukları Koordinatörlüğü</title>
    <link rel="stylesheet" href="{{ asset('css/haberler.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <!-- İçerik -->
    <div class="content">
        <div class="title-section">
            <div class="title-header">
                <a href="{{ route('anasayfa') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Ana Sayfaya Dön
                </a>
                <h1 id="contentTitle">HABERLER</h1>
            </div>
        </div>

        <div class="main-content">
            <div class="haberler-container">
                @foreach($etkinlikler as $etkinlik)
                    <div class="haber-card" onclick="window.location.href='{{ route('etkinlikler', ['topluluk_isim' => Str::slug($etkinlik->topluluk_adi), 'topluluk_id' => $etkinlik->topluluk_id]) }}'">
                        <div class="haber-img">
                            <img src="{{ asset('images/etkinlik/'.$etkinlik->resim) }}" alt="{{ $etkinlik->etkinlik_adi }}">
                        </div>
                        <div class="haber-content">
                            <div class="haber-meta">
                                <span class="haber-topluluk">{{ $etkinlik->topluluk_adi }}</span>
                                <span class="haber-tarih">{{ \Carbon\Carbon::parse($etkinlik->tarih)->format('d.m.Y') }}</span>
                            </div>
                            <h3 class="haber-baslik">{{ $etkinlik->etkinlik_adi }}</h3>
                            <p class="haber-ozet">{{ $etkinlik->bilgi }}</p>
                            <div class="haber-devami">
                                <span>Devamını Oku</span>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sayfalama -->
            @if($etkinlikler->hasPages())
                <div class="pagination-container">
                    {{ $etkinlikler->links('vendor.pagination.turkish') }}
                </div>
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

<script src="{{ asset('js/haberler.js') }}"></script>
</body>
</html>

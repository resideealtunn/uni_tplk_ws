<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $topluluk->isim }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/tplk_anasayfa.css') }}">
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/logo/'.$topluluk->gorsel) }}">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('topluluk_anasayfa', ['isim' => $topluluk->isim, 'id' => $topluluk->id]) }}">Anasayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etkinlikler', ['topluluk_isim' => $topluluk->isim, 'topluluk_id' => $topluluk->id]) }}">
                        Etkinlikler
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Üye İşlemleri</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Yönetici İşlemleri</a>
                </li>
            </ul>

            <!-- NEU Logo -->
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo/neu_logo.png') }}" >
            </a>
        </div>
    </div>
</nav>

<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">{{ $topluluk->isim }}</h1>
        <p class="hero-subtitle">{{$topluluk->slogan}}</p>
    </div>
</section>

<section class="vision-mission">
    <div class="container">
        <h2 class="section-title">Vizyonumuz ve Misyonumuz</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card vision-card p-4">
                    <div class="text-center">
                        <i class="fas fa-eye card-icon"></i>
                        <h3 class="card-title">Vizyonumuz</h3>
                    </div>
                    <p class="card-text">
                        {{ $topluluk->vizyon }}
                    </p>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card mission-card p-4">
                    <div class="text-center">
                        <i class="fas fa-bullseye card-icon"></i>
                        <h3 class="card-title">Misyonumuz</h3>
                    </div>
                    <p class="card-text">
                        {{ $topluluk->misyon }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <h2 class="section-title">İletişim</h2>
        <div class="contact-form">
            <form action="" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Ad Soyad</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-posta Adresi</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Mesajınız</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-submit">Geri Bildirim Gönder</button>
                </div>
            </form>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h3 class="footer-title">Hakkımızda</h3>
                <p>Necmettin Erbakan Üniversitesi Bilişim Topluluğu olarak teknolojiye olan tutkumuzu paylaşıyor ve geleceği birlikte şekillendiriyoruz.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h3 class="footer-title">Hızlı Bağlantılar</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('topluluk_anasayfa', ['isim' => $topluluk->isim, 'id' => $topluluk->id]) }}"><i class="fas fa-chevron-right"></i> Anasayfa</a></li>
                    <li><a href="{{ route('etkinlikler', ['topluluk_isim' => $topluluk->isim, 'topluluk_id' => $topluluk->id]) }}"><i class="fas fa-chevron-right"></i> Etkinlikler</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Üye İşlemleri</a></li>
                    <li><a href="{{route('yonetici.giris')}}"><i class="fas fa-chevron-right"></i> Yönetici İşlemleri</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h3 class="footer-title">İletişim</h3>
                <div class="footer-contact">
                    <p><i class="fas fa-map-marker-alt"></i> Necmettin Erbakan Üniversitesi, Meram/Konya</p>
                    <p><i class="fas fa-envelope"></i> bilisim@erbakan.edu.tr</p>
                    <p><i class="fas fa-phone"></i> +90 332 323 82 20</p>
                </div>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Necmettin Erbakan Üniversitesi Bilişim Topluluğu. Tüm hakları saklıdır.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/tplk_anasayfa.js') }}"></script>
</body>
</html>

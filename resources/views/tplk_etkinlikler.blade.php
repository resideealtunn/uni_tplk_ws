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
    <section class="events-section">
        <div class="container">
            <!-- Aktif Etkinlikler -->
            <div class="section-header">
                <h2>Aktif Etkinlikler</h2>
                <p>Yaklaşan ve devam eden etkinliklerimizi keşfedin</p>
            </div>
            <div class="row">
                @foreach($activeEvents as $event)
                    <div class="col-md-3">
                        <div class="event-card">
                            <img src="{{ asset('images/etkinlik/'.$event->gorsel) }}"  class="event-image">
                            <div class="event-content">
                                <h3 class="event-title">{{ $event->isim }}</h3>
                                <p class="event-short-desc">{{ $event->bilgi }}</p>
                            </div>
                            <div class="event-long-desc">
                                <p>{{ $event->metin }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Geçmiş Etkinlikler -->
            <div class="section-header mt-5">
                <h2>Geçmiş Etkinlikler</h2>
                <p>Tamamlanmış etkinliklerimizi inceleyin</p>
            </div>
            <div class="row">
                @foreach($pastEvents as $event)
                    <div class="col-md-3">
                        <div class="event-card">
                            <img src="{{ asset('images/etkinlik/'.$event->gorsel) }}" class="event-image">
                            <div class="event-content">
                                <h3 class="event-title">{{ $event->isim }}</h3>
                                <p class="event-short-desc">{{ $event->bilgi }}</p>
                            </div>
                            <div class="event-long-desc">
                                <p>{{ $event->metin }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
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
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Yönetici İşlemleri</a></li>
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

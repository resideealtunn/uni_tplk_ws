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
        <!-- LOGO FLEX WRAP BAŞLANGIÇ -->
        <div class="navbar-logos d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('topluluk_anasayfa', ['isim' => $topluluk->isim, 'id' => $topluluk->id]) }}">
                @if(isset($logo_onay) && ($logo_onay == 1 || $logo_onay == 4) && $topluluk->gorsel)
                    <img src="{{ asset('images/logo/'.$topluluk->gorsel) }}">
                @endif
            </a>
            <a class="navbar-brand neu-logo-mobile d-lg-none" href="{{route('kesfet')}}">
                <img src="{{ asset('images/logo/neu_logo.png') }}" >
            </a>
        </div>
        <!-- LOGO FLEX WRAP BİTİŞ -->

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('topluluk_anasayfa', ['isim' => $topluluk->isim, 'id' => $topluluk->id]) }}">Anasayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etkinlikler', ['topluluk_isim' => $topluluk->isim, 'topluluk_id' => $topluluk->id]) }}">
                        Etkinlikler
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('uyeislemleri', ['isim' => Str::slug($topluluk->isim), 'id' => $topluluk->id]) }}">Üye İşlemleri</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('yonetici.giris')}}">Yönetici İşlemleri</a>
                </li>
            </ul>

            <a class="navbar-brand neu-logo-desktop d-none d-lg-block" href="{{route('kesfet')}}">
                <img src="{{ asset('images/logo/neu_logo.png') }}" >
            </a>
        </div>
    </div>
</nav>
@if(session('success'))
    <div class="alert alert-success" style="text-align:center">
        {{ session('success') }}
    </div>
@endif
@if(session('danger'))
    <div class="alert alert-danger" ali>
        {{ session('danger') }}
    </div>
@endif
<section class="hero-section" style="@if(isset($bg_onay) && ($bg_onay == 1 || $bg_onay == 4) && $topluluk->bg)background-image: url('{{ asset('images/background/'.$topluluk->bg) }}');@endif">
    <div class="hero-content">
        <h1 class="hero-title">{{ $topluluk->isim }}</h1>
        @if(isset($slogan_onay) && ($slogan_onay == 1 || $slogan_onay == 4))
            <p class="hero-subtitle">{{$topluluk->slogan}}</p>
        @endif
        
        <!-- İstatistik kutucukları -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $uye_sayisi }}</div>
                        <div class="stat-label">Üye</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $etkinlik_sayisi }}</div>
                        <div class="stat-label">Etkinlik</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="vision-mission">
    <div class="container">
        <h2 class="section-title">Vizyonumuz, Misyonumuz ve Tüzüğümüz</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card vision-card p-4">
                    <div class="text-center">
                        <i class="fas fa-eye card-icon"></i>
                        <h3 class="card-title">Vizyonumuz</h3>
                    </div>
                    <p class="card-text">
                        @if(isset($vizyon_onay) && ($vizyon_onay == 1 || $vizyon_onay == 4))
                            {{ $topluluk->vizyon }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card mission-card p-4">
                    <div class="text-center">
                        <i class="fas fa-bullseye card-icon"></i>
                        <h3 class="card-title">Misyonumuz</h3>
                    </div>
                    <p class="card-text">
                        @if(isset($misyon_onay) && ($misyon_onay == 1 || $misyon_onay == 4))
                            {{ $topluluk->misyon }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card mission-card p-4">
                    <div class="text-center">
                        <i class="fas fa-file-pdf card-icon"></i>
                        <h3 class="card-title">Topluluk Tüzüğü</h3>
                    </div>
                    <p class="card-text text-center">
                        @if(isset($tuzuk_onay) && ($tuzuk_onay == 1 || $tuzuk_onay == 4) && $topluluk->tuzuk)
                            <a href="{{ asset('files/tuzuk/'.$topluluk->tuzuk) }}" target="_blank" class="btn btn-outline-primary">
                                <i class="fas fa-file-pdf"></i> Tüzüğü Görüntüle
                            </a>
                        @endif
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
            <form action="{{route('iletisim')}}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="tckno" class="form-label">TC Kimlik Numarası</label>
                    <input type="text" class="form-control" id="tckno" name="tckno" required maxlength="11" minlength="11" pattern="[0-9]{11}">
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Mesajınız</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>
                <input type="hidden" value="{{$topluluk->id}}" name="id">
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
                    <li><a href="{{ route('uyeislemleri', ['isim' => Str::slug($topluluk->isim), 'id' => $topluluk->id]) }}"><i class="fas fa-chevron-right"></i> Üye İşlemleri</a></li>
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
                    @if(isset($sosyal_medya) && $sosyal_medya->w_onay == 1 && $sosyal_medya->whatsapp)
                        <a href="{{$sosyal_medya->whatsapp}}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    @endif
                    @if(isset($sosyal_medya) && $sosyal_medya->i_onay == 1 && $sosyal_medya->instagram)
                        <a href="{{$sosyal_medya->instagram}}" target="_blank"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if(isset($sosyal_medya) && $sosyal_medya->l_onay == 1 && $sosyal_medya->linkedln)
                        <a href="{{$sosyal_medya->linkedln}}" target="_blank"><i class="fab fa-linkedin"></i></a>
                    @endif
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

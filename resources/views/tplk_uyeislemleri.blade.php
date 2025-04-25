<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $topluluk->isim }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/tplk_anasayfa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tplk_uyeislemleri.css') }}">
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
                    <a class="nav-link" href="{{ route('uyeislemleri', ['isim' => Str::slug($topluluk->isim), 'id' => $topluluk->id]) }}">Üye İşlemleri</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('yonetici.giris')}}">Yönetici İşlemleri</a>
                </li>
            </ul>

            <!-- NEU Logo -->
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo/neu_logo.png') }}" >
            </a>
        </div>
    </div>
</nav>

    <section class="membership-section">
        <div class="container">
            <h1 class="text-center mb-4">Üye İşlemleri</h1>
            <h2 class="text-center mb-5">{{ $topluluk->isim }}</h2>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('danger'))
                <div class="alert alert-danger">
                    {{ session('danger') }}
                </div>
            @endif
            <div class="membership-form">
                <form method="POST" enctype="multipart/form-data" action="{{route('kayitol')}}">
                    @csrf
                    <div class="mb-3">
                        <label for="student_number" class="form-label">TC. No</label>
                        <input type="text" class="form-control" id="student_number" name="tc" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Tek Şifre</label>
                        <input type="password" class="form-control" id="password" name="sifre" required>
                    </div>

                    <div class="mb-3">
                        <label for="membership_form" class="form-label">Topluluk Üyelik Formu</label>
                        <input type="file" class="form-control" id="membership_form" name="membership_form" required>
                    </div>

                    <a href="{{ asset('docs/kayit_belge/uyelik.docx') }}" class="download-link" target="_blank">
                        <i class="fas fa-download"></i> Topluluk Üyelik Formunu İndir
                    </a>

                    <div class="text-center">,
                        <input type="hidden" value="{{ $topluluk->id }}" name="topluluk">
                        <button type="submit" class="btn btn-primary">Topluluğa Üye Ol</button>
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
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Anasayfa</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Etkinlikler</a></li>
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
    <script src="{{ asset('js/tplk_uyeislemleri.js') }}"></script>
</body>
</html>

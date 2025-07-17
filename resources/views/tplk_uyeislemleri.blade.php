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
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <!-- LOGO FLEX WRAP BAŞLANGIÇ -->
        <div class="navbar-logos d-flex align-items-center">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo/'.$topluluk->gorsel) }}">
            </a>
            <a class="navbar-brand neu-logo-mobile d-lg-none" href="#">
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
                    <a class="nav-link" href="{{ route('topluluk_anasayfa', ['isim' => $topluluk->isim, 'id' => $topluluk->id]) }}">Anasayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('etkinlikler', ['topluluk_isim' => $topluluk->isim, 'topluluk_id' => $topluluk->id]) }}">
                        Etkinlikler
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('uyeislemleri', ['isim' => Str::slug($topluluk->isim), 'id' => $topluluk->id]) }}">Üye İşlemleri</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('yonetici.giris')}}">Yönetici İşlemleri</a>
                </li>
            </ul>

            <a class="navbar-brand neu-logo-desktop d-none d-lg-block" href="#">
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
            <div class="uyeislemleri-accordion-container">
                <div class="accordion custom-accordion" id="uyeAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <i class="fas fa-user-plus me-2"></i> Topluluğa Üye Ol
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#uyeAccordion">
                            <div class="accordion-body">
                                <form method="POST" enctype="multipart/form-data" action="{{ route('kayitol') }}" class="uye-form" id="uyeForm">
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
                                        <label for="membership_form" class="form-label">Topluluk Üyelik Formu (PDF)</label>
                                        <input type="file" class="form-control" id="membership_form" name="membership_form" accept="application/pdf" required>
                                    </div>
                                    @php
                                        $uyelikForm = DB::table('formlar')->where('id', 12)->first();
                                    @endphp
                                    <a href="{{ $uyelikForm ? asset('docs/formlar/' . $uyelikForm->dosya) : '#' }}" class="download-link" target="_blank">
                                        <i class="fas fa-download"></i> Topluluk Üyelik Formunu İndir
                                    </a>
                                    <input type="hidden" value="{{ $topluluk->id }}" name="topluluk">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-uye">Topluluğa Üye Ol</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fas fa-users me-2"></i> Yönetim Kuruluna Başvur
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#uyeAccordion">
                            <div class="accordion-body">
                                <form method="POST" action="{{ route('yonetimkurulu.basvuru') }}" class="yonetim-form">
                                    @csrf
                                    <input type="hidden" name="topluluk_id" value="{{ $topluluk->id }}">
                                    <div class="mb-3">
                                        <label for="yk_student_number" class="form-label">TC. No</label>
                                        <input type="text" class="form-control" id="yk_student_number" name="tc" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="yk_password" class="form-label">Tek Şifre</label>
                                        <input type="password" class="form-control" id="yk_password" name="sifre" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="niyet_metni" class="form-label">Niyet Metni</label>
                                        <textarea class="form-control" id="niyet_metni" name="niyet_metni" rows="4" placeholder="Neden yönetim kurulunda yer almak istiyorsunuz?" required></textarea>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-uye">Başvur</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <i class="fas fa-calendar-check me-2"></i> Etkinliğe Başvur
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#uyeAccordion">
                            <div class="accordion-body">
                                <form method="POST" action="#" class="etkinlik-form">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="etk_student_number" class="form-label">TC. No</label>
                                        <input type="text" class="form-control" id="etk_student_number" name="tc" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="etk_password" class="form-label">Tek Şifre</label>
                                        <input type="password" class="form-control" id="etk_password" name="sifre" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="etkinlik" class="form-label">Etkinlik Seç</label>
                                        <select class="form-control" id="etkinlik" name="etkinlik" required>
                                            <option value="">Bir etkinlik seçiniz</option>
                                            <!-- AJAX ile doldurulacak -->
                                        </select>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-uye">Başvur</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                <i class="fas fa-clipboard-list me-2"></i> Yoklama Durumu Görüntüle
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#uyeAccordion">
                            <div class="accordion-body">
                                <form class="yoklama-form" onsubmit="event.preventDefault(); document.getElementById('yoklamaSonuc').style.display='block';">
                                    <div class="mb-3">
                                        <label for="yoklama_student_number" class="form-label">TC. No</label>
                                        <input type="text" class="form-control" id="yoklama_student_number" name="tc" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="yoklama_password" class="form-label">Tek Şifre</label>
                                        <input type="password" class="form-control" id="yoklama_password" name="sifre" required>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn yoklama-btn">Yoklama Görüntüle</button>
                                    </div>
                                </form>
                                <div id="yoklamaSonuc" class="yoklama-sonuc mt-4" style="display:none;">
                                    <div class="yoklama-katilim text-center mb-3">
                                        <span class="badge bg-info text-dark fs-5">Katılım: 3/5</span>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table yoklama-table align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Etkinlik</th>
                                                    <th>Katılım Durumu</th>
                                                </tr>
                                            </thead>
                                            <tbody id="yoklamaSonucBody">
                                                <!-- JS ile doldurulacak -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                <i class="fas fa-check-square me-2"></i> Yoklama Gir
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#uyeAccordion">
                            <div class="accordion-body">
                                <form id="yoklamaForm" method="POST">
                                    <div class="mb-3">
                                        <label for="yoklamaTc" class="form-label">TC. No</label>
                                        <input type="text" class="form-control" id="yoklamaTc" name="tc" required maxlength="11">
                                    </div>
                                    <div class="mb-3">
                                        <label for="yoklamaSifre" class="form-label">Tek Şifre</label>
                                        <input type="password" class="form-control" id="yoklamaSifre" name="sifre" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="yoklamaEtkinlik" class="form-label">Etkinlik Seç</label>
                                        <select class="form-control" id="yoklamaEtkinlik" name="etkinlik" required>
                                            <option value="">Bir etkinlik seçiniz</option>
                                            <!-- AJAX ile doldurulacak -->
                                        </select>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="katildimCheck" name="katildim">
                                        <label class="form-check-label" for="katildimCheck">Katıldım</label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-uye">Yoklamayı Kaydet</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
                        <li><a href="{{ route('yonetici.giris') }}"><i class="fas fa-chevron-right"></i> Yönetici İşlemleri</a></li>
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
    <script src="{{ asset('js/tplk_uyeislemleri.js') }}"></script>
    <script>
        window.toplulukId = {{ $topluluk->id }};
    </script>
</body>
</html>

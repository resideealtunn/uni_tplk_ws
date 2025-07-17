<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $topluluk->isim }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/tplk_anasayfa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tplk_etkinlikler.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <!-- LOGO FLEX WRAP BAŞLANGIÇ -->
        <div class="navbar-logos d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('topluluk_anasayfa', ['isim' => $topluluk->isim, 'id' => $topluluk->id]) }}">
                @if(isset($topluluk->gorsel))
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
                    <a class="nav-link" href="{{ route('topluluk_anasayfa', ['isim' => $topluluk->isim, 'id' => $topluluk->id]) }}">Anasayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('etkinlikler', ['topluluk_isim' => $topluluk->isim, 'topluluk_id' => $topluluk->id]) }}">
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
    <section class="events-section">
        <div class="container">
            <!-- Aktif Etkinlikler -->
            <div class="section-header">
                <h2>Aktif Etkinlikler</h2>
                <p>Yaklaşan ve devam eden etkinliklerimizi keşfedin</p>
            </div>
            <div class="row" id="activeEvents">
                @foreach($activeEvents as $event)
                    <div class="col-md-3">
                        <div class="event-card active-event"
                             data-gorsel="{{ asset('images/etkinlik/'.$event->gorsel) }}"
                             data-baslik="{{ $event->isim }}"
                             data-bilgi="{{ $event->bilgi }}"
                             data-metin="{{ $event->metin }}"
                             data-tarih="{{ $event->tarih }}"
                             data-bitis-tarihi="{{ $event->bitis_tarihi }}"
                             data-e_id="{{ $event->id }}"
                             data-t_id="{{ $topluluk->id }}">
                            <img src="{{ asset('images/etkinlik/'.$event->gorsel) }}"  class="event-image">
                            <div class="event-content">
                                <h3 class="event-title">{{ $event->isim }}</h3>
                                <p class="event-short-desc">{{ $event->bilgi }}</p>
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
            <div class="row" id="pastEvents">
                @foreach($pastEvents as $event)
                    <div class="col-md-3">
                        <div class="event-card past-event"
                             data-gorsel="{{ asset('images/etkinlik/'.$event->resim) }}"
                             data-baslik="{{ $event->isim }}"
                             data-bilgi="{{ $event->gecmis_bilgi }}"
                             data-metin="{{ $event->aciklama }}"
                             data-tarih="{{ $event->tarih }}"
                             data-bitis-tarihi="{{ $event->bitis_tarihi }}"
                             data-e_id="{{ $event->id }}">
                            <img src="{{ asset('images/etkinlik/'.$event->resim) }}" class="event-image">
                            <div class="event-content">
                                <h3 class="event-title">{{ $event->isim }}</h3>
                                <p class="event-short-desc">{{ $event->gecmis_bilgi }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="event-modal" id="eventModal" style="display:none;">
        <div class="modal-content-modern">
            <div class="modal-left-modern">
                <img id="modalImage" src="" alt="Etkinlik Detayı">
            </div>
            <div class="modal-right-modern">
                <h2 id="modalTitle"></h2>
                <div id="modalCommunity" class="modal-community"></div>
                <div id="modalShortDesc" class="modal-short"></div>
                <div id="modalDates" class="modal-dates"></div>
                <div id="modalLongDesc" class="modal-long" style="word-break: break-word; white-space: pre-line;"></div>
                <button class="apply-btn-modern" id="applyBtn" style="display:none;">Etkinliğe Başvur</button>
            </div>
            <button class="close-btn-modern" id="closeModal">&times;</button>
        </div>
    </div>

    <!-- Mini Başvuru Modalı -->
    <div class="mini-modal" id="miniModal" style="display:none;">
        <div class="mini-modal-content">
            <span class="mini-close" id="miniClose">&times;</span>
            <h4>Etkinliğe Başvuru</h4>
            <form id="miniApplyForm" method="post" autocomplete="off" action="javascript:void(0);">
                <label for="minitckNo">TC Kimlik No</label>
                <input type="text" id="minitckNo" name="tckn" required maxlength="11">
                <label for="minitckPass">Tek Şifre</label>
                <input type="password" id="minitckPass" name="sifre" required>
                <input type="hidden" id="minieId" name="e_id">
                <input type="hidden" id="minitId" name="t_id">
                <button type="submit">Başvur</button>
            </form>
        </div>
    </div>

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
    <script src="{{ asset('js/tplk_etkinlikler.js') }}"></script>
</body>
</html>

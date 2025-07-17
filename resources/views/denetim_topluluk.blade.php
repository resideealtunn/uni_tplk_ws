<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Topluluk Denetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/denetim_topluluk.css') }}">
</head>
<body>
<!-- Hamburger Menü -->
<div class="hamburger" id="hamburger">
    <span></span>
    <span></span>
    <span></span>
</div>
<div class="sidebar" id="sidebar">
    <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
    <h2>{{session('isim')}}</h2>
    <h3>{{session('unvan')}}</h3>
    <p>{{session('birim')}}</p>
    <div class="menu">
        <a href="{{ route('denetim.topluluk') }}" class="menu-item active">Topluluk İşlemleri</a>
        <a href="{{ route('denetim.etkinlik') }}" class="menu-item">Etkinlik İşlemleri</a>
        <a href="{{ route('denetim.uye') }}" class="menu-item">Üye İşlemleri</a>
        <a href="{{ route('denetim.formlar') }}" class="menu-item">Form İşlemleri</a>
        <a href="{{ route('denetim.panel') }}" class="menu-item">Web Arayüz İşlemleri</a>
        <div class="menu-item" onclick="window.location.href='{{ route('anasayfa') }}'">Çıkış</div>
    </div>
</div>

<div class="content" id="web">
    <div class="form-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Topluluk Denetim İşlemleri</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('danger'))
            <div class="alert alert-danger">{{ session('danger') }}</div>
        @endif

        <!-- Topluluk Ekle -->
        <div class="mb-4">
            <h4>Topluluk Ekle</h4>
        </div>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Topluluk Adı</th>
                    <th>Geçici Başkan TCK No</th>
                    <th>Kuruluş Başvuru Belgesi</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <form method="POST" action="{{ route('denetim.topluluk-ekle') }}" enctype="multipart/form-data">
                        @csrf
                        <td><input type="text" class="form-control" name="isim" required></td>
                        <td><input type="text" class="form-control" name="baskan_no" id="baskanNoInput" 
                            maxlength="11" minlength="11" pattern="[1-9][0-9]{10}" 
                            title="11 haneli, 0 ile başlamayan TC Kimlik No giriniz" required >
                            <div id="tcError" style="color: #dc3545; font-size: 12px; margin-top: 5px; display: none;"></div>
                        </td>
                        <td><input type="file" class="form-control" name="kurulus_belge" id="kurulusBelgeInput" accept="application/pdf" required>
                            <div id="pdfError" style="color: #dc3545; font-size: 12px; margin-top: 5px; display: none;"></div>
                        </td>
                        <td><button type="submit" class="btn btn-success">Kaydet</button></td>
                    </form>
                </tr>
            </tbody>
        </table>

        <!-- Aktif Topluluklar -->
        <div class="mb-4">
            <h4>Aktif Topluluklar</h4>
        </div>
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" placeholder="Topluluk ara...">
        </div>
        <div class="community-cards" id="communityCards">
            @foreach($topluluklar->forPage($currentPage, 5) as $topluluk)
                <div class="community-card" data-community-id="{{ $topluluk->id }}"
                     data-community-name="{{ $topluluk->isim }}"
                     data-community-gorsel="{{ $topluluk->gorsel ? asset('images/logo/' . $topluluk->gorsel) : asset('images/logo/default.png') }}"
                     data-community-slogan="{{ $topluluk->slogan }}"
                     data-durum="1">
                    <div class="card-content">
                        <img src="{{ $topluluk->gorsel ? asset('images/logo/' . $topluluk->gorsel) : asset('images/logo/default.png') }}" alt="Logo">
                        <h3>{{ $topluluk->isim }}</h3>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal -->
        <div class="modal fade" id="communityModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="communityTitle">Topluluk Detayı</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p id="communityDescription"></p>
                    </div>
                    <div class="modal-footer">
                        <!-- Butonlar JS ile eklenecek, başlangıçta boş -->
                    </div>
                </div>
            </div>
        </div>

        <div class="pagination" style="text-align: center; margin-top: 20px;">
            @if ($currentPage > 1)
                <a href="{{ route('denetim.topluluk', ['page' => $currentPage - 1]) }}" style="margin-right: 10px;">&laquo; Önceki</a>
            @endif
            <span class="current-page" style="margin: 0 10px;">Sayfa {{ $currentPage }} / {{ ceil($totalForms / 5) }}</span>
            @if ($currentPage < ceil($totalForms / 5))
                <a href="{{ route('denetim.topluluk', ['page' => $currentPage + 1]) }}" style="margin-left: 10px;">Sonraki &raquo;</a>
            @endif
        </div>

        <!-- Pasif Topluluklar -->
        <div class="mb-4">
            <h4>Pasif Topluluklar</h4>
        </div>
        <div class="community-cards">
            @foreach($ptopluluklar->forPage($pcurrentPage, 5) as $topluluk)
                <div class="community-card" data-community-id="{{ $topluluk->id }}"
                     data-community-name="{{ $topluluk->isim }}"
                     data-community-gorsel="{{ $topluluk->gorsel ? asset('images/logo/' . $topluluk->gorsel) : asset('images/logo/default.png') }}"
                     data-community-slogan="{{ $topluluk->slogan }}"
                     data-durum="2">
                    <div class="card-content">
                        <img src="{{ $topluluk->gorsel ? asset('images/logo/' . $topluluk->gorsel) : asset('images/logo/default.png') }}" alt="Logo">
                        <h3>{{ $topluluk->isim }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="pagination" style="text-align: center; margin-top: 20px;">
            @if ($pcurrentPage > 1)
                <a href="{{ route('denetim.topluluk', ['sayfa' => $pcurrentPage - 1]) }}" style="margin-right: 10px;">&laquo; Önceki</a>
            @endif
            <span class="current-page" style="margin: 0 10px;">Sayfa {{ $pcurrentPage }} / {{ ceil($ptotalForms / 5) }}</span>
            @if ($pcurrentPage < ceil($ptotalForms / 5))
                <a href="{{ route('denetim.topluluk', ['sayfa' => $pcurrentPage + 1]) }}" style="margin-left: 10px;">Sonraki &raquo;</a>
            @endif
        </div>
    </div>
</div>

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
            <h3>Eposta</h3>
            <p>topluluk@erbakan.edu.tr</p>
        </div>
    </div>
    <div class="footer-bottom">
        © 2022 Necmettin Erbakan Üniversitesi
    </div>
</footer>

<!-- Topluluk Arama İşlevi İçin JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const cardsContainer = document.getElementById('communityCards');
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const value = this.value.trim();
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (value === '') {
                        window.location.reload();
                        return;
                    }
                    fetch(`/denetim/topluluk-ara?q=${encodeURIComponent(value)}`)
                        .then(res => res.json())
                        .then(data => {
                            cardsContainer.innerHTML = '';
                            if (data.length === 0) {
                                cardsContainer.innerHTML = `<div style='grid-column: 1 / -1; text-align: center; color: #666; padding: 40px;'><h3>Arama sonucu bulunamadı</h3></div>`;
                                return;
                            }
                            data.forEach(topluluk => {
                                const gorsel = topluluk.gorsel ? `/images/logo/${topluluk.gorsel}` : '/images/logo/default.png';
                                const card = document.createElement('div');
                                card.className = 'community-card';
                                card.setAttribute('data-community-id', topluluk.id);
                                card.setAttribute('data-community-name', topluluk.isim);
                                card.setAttribute('data-community-gorsel', gorsel);
                                card.setAttribute('data-community-slogan', topluluk.slogan || '');
                                card.setAttribute('data-durum', '1');
                                card.innerHTML = `<div class="card-content">
                                    <img src="${gorsel}" alt="Logo">
                                    <h3>${topluluk.isim}</h3>
                                </div>`;
                                cardsContainer.appendChild(card);
                            });
                        });
                }, 300);
            });
        }

        // TC Kimlik No validasyonu
        const tcInput = document.getElementById('baskanNoInput');
        const tcError = document.getElementById('tcError');
        if (tcInput) {
            tcInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = value;
                if (value.length !== 11) {
                    tcError.textContent = 'TC Kimlik No 11 haneli olmalı.';
                    tcError.style.display = 'block';
                } else if (value[0] === '0') {
                    tcError.textContent = 'TC Kimlik No 0 ile başlayamaz.';
                    tcError.style.display = 'block';
                } else {
                    tcError.textContent = '';
                    tcError.style.display = 'none';
                }
            });
        }
        // PDF validasyonu
        const belgeInput = document.getElementById('kurulusBelgeInput');
        const pdfError = document.getElementById('pdfError');
        if (belgeInput) {
            belgeInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type !== 'application/pdf') {
                    pdfError.textContent = 'Sadece PDF dosyası yükleyebilirsiniz.';
                    pdfError.style.display = 'block';
                    e.target.value = '';
                } else {
                    pdfError.textContent = '';
                    pdfError.style.display = 'none';
                }
            });
        }
    });
</script>
</body>
<script src="{{ asset('js/denetim_topluluk.js') }}"></script>
<script src="{{ asset('js/denetim_topluluk_menu.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>

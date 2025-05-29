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
<div class="sidebar">
    <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
    <h2>{{session('unvan')}}</h2>
    <h3>{{session('isim')}}</h3>
    <p>{{session('birim')}}</p>
    <div class="menu">
        <a href="{{ route('denetim.topluluk') }}" class="menu-item active">Topluluk İşlemleri</a>
        <a href="{{ route('denetim.etkinlik') }}" class="menu-item">Etkinlik İşlemleri</a>
        <a href="{{ route('denetim.uye') }}" class="menu-item">Üye İşlemleri</a>
        <a href="{{ route('denetim.formlar') }}" class="menu-item">Form İşlemleri</a>
        <a href="{{ route('denetim.panel') }}" class="menu-item">Web Arayüz İşlemleri</a>
        <a href="{{ route('kesfet') }}" class="menu-item">Çıkış</a>
    </div>
</div>

<div class="content" id="web">
    <div class="form-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Topluluk Denetim İşlemleri</h2>
        </div>

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

        <div class="mb-4">
            <h4>Aktif Topluluklar</h4>
        </div>

        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" placeholder="Topluluk ara...">
        </div>

        <div class="community-cards">
            @foreach($topluluklar as $topluluk)
                <div class="community-card" data-community-id="{{ $topluluk->id }}"
                     data-community-name="{{ $topluluk->isim }}"
                     data-community-gorsel="{{ asset('images/logo/' . $topluluk->gorsel) }}"
                     data-community-slogan="{{ $topluluk->slogan }}">
                    <div class="card-content">
                        <img src="{{ asset('images/logo/' . $topluluk->gorsel) }}" alt="Logo">
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
                        <button id="deleteCommunity" class="btn btn-danger">Sil</button>
                        <a id="goToCommunityPage"  class="btn btn-primary">Sayfaya Git</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="pagination" style="text-align: center; margin-top: 20px;">
            @if ($currentPage > 1)
                <a href="{{ route('denetim.topluluk', ['page' => $currentPage - 1]) }}" style="margin-right: 10px;">&laquo; Önceki</a>
            @endif

            <span class="current-page" style="margin: 0 10px;">Sayfa {{ $currentPage }} / {{ $lastPage }}</span>

            @if ($currentPage < $lastPage)
                <a href="{{ route('denetim.topluluk', ['page' => $currentPage + 1]) }}" style="margin-left: 10px;">Sonraki &raquo;</a>
            @endif
        </div>
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
                    <td>
                        <input type="text" class="form-control" name="isim" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="baskan_no" required>
                    </td>
                    <td>
                        <input type="file" class="form-control" name="kurulus_belge" required>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-success">Kaydet</button>
                    </td>
                </form>
            </tr>
            </tbody>
        </table>

        <div class="mb-4">
            <h4>Kapanan Topluluklar</h4>
        </div>

        <div class="community-cards">
            @foreach($ptopluluklar as $topluluk)
                <div class="community-card">
                    <div class="card-content">
                        <img src="{{ asset('images/logo/' . $topluluk->gorsel) }}" alt="Logo">
                        <h3>{{ $topluluk->isim }}</h3>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination" style="text-align: center; margin-top: 20px;">
            @if ($pcurrentPage > 1)
                <a href="{{ route('denetim.topluluk', ['sayfa' => $pcurrentPage - 1]) }}" style="margin-right: 10px;">&laquo; Önceki</a>
            @endif

            <span class="current-page" style="margin: 0 10px;">Sayfa {{ $pcurrentPage }} / {{ $plastPage }}</span>

            @if ($pcurrentPage < $plastPage)
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
            <h3>Sosyal Medya & Eposta</h3>
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
        const cards = document.querySelectorAll('.community-card');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const value = this.value.toLowerCase();
                cards.forEach(card => {
                    const name = card.querySelector('h3').textContent.toLowerCase();
                    card.style.display = name.includes(value) ? '' : 'none';
                });
            });
        }
    });
</script>
</body>
<script src="{{ asset('js/denetim_topluluk.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>

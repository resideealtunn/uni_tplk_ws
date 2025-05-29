<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topluluk Denetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/denetim_formlar.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="sidebar">
        <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
        <h2>{{session('unvan')}}</h2>
        <h3>{{session('isim')}}</h3>
        <p>{{session('birim')}}</p>
        <div class="menu">
            <a href="{{ route('denetim.topluluk') }}" class="menu-item">Topluluk İşlemleri</a>
            <a href="{{ route('denetim.etkinlik') }}" class="menu-item">Etkinlik İşlemleri</a>
            <a href="{{ route('denetim.uye') }}" class="menu-item">Üye İşlemleri</a>
            <a href="{{ route('denetim.formlar') }}" class="menu-item active">Form İşlemleri</a>
            <a href="{{ route('denetim.panel') }}" class="menu-item ">Web Arayüz İşlemleri</a>
            <div class="menu-item" onclick="window.location.href='{{ route('kesfet') }}'">Çıkış</div>
        </div>
    </div>
    <div class="content" id="web">
        <div class="form-container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Form Denetim İşlemleri</h2>
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
                <h4>Yeni Form Ekle</h4>
            </div>

            <form id="formAddEditForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="formTitle" class="form-label">Form Başlığı</label>
                    <input type="text" class="form-control" id="formTitle" name="formTitle" required>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Form (PDF)</label>
                    <input type="file" class="form-control" id="formFile" name="formFile" accept="application/pdf">
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-success">Kaydet</button>
                </div>
            </form>

            <div class="info-section mb-4">
                <h3>Formlar</h3>
                <div id="formList">
                    <div class="form-list">
                        @foreach($forms as $form)
                            <div class="form-item">
                                <a href="{{asset('docs/formlar/'.$form->dosya)}}" target="_blank">
                                    <span>{{ $form->isim }}</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                                <a href="{{ route('denetim.form-sil', ['id' => $form->id]) }}" class="btn btn-danger btn-sm">Sil</a>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sayfalama -->
                    <div class="pagination" style="text-align: center; margin-top: 20px;">
                        @if ($currentPage > 1)
                            <a href="{{ route('denetim.formlar', ['page' => $currentPage - 1]) }}" style="margin-right: 10px;">&laquo; Önceki</a>
                        @endif

                        <span class="current-page" style="margin: 0 10px;">Sayfa {{ $currentPage }} / {{ $lastPage }}</span>

                        @if ($currentPage < $lastPage)
                            <a href="{{ route('denetim.formlar', ['page' => $currentPage + 1]) }}" style="margin-left: 10px;">Sonraki &raquo;</a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Form Ekle/Düzenle Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formAddEditForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formModalTitle">Form Ekle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="formTitle" class="form-label">Form Başlığı</label>
                            <input type="text" class="form-control" id="formTitle" name="formTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Form (PDF)</label>
                            <input type="file" class="form-control" id="formFile" name="formFile" accept="application/pdf">
                        </div>
                        <input type="hidden" id="formId" name="formId">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Kaydet</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Silme Onay Modalı -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Formu Sil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    Emin misiniz? Bu işlem geri alınamaz.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Sil</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                </div>
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
                <div class="social-icons">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-linkedin-in"></i>
                    <i class="fab fa-youtube"></i>
                </div>
                <p>topluluk@erbakan.edu.tr</p>
            </div>
        </div>
        <div class="footer-bottom">
            © 2022 Necmettin Erbakan Üniversitesi
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/denetim_formlar.js') }}"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topluluk Denetimi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/denetim_etkinlik.css') }}">
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
            <a href="{{ route('denetim.etkinlik') }}" class="menu-item active">Etkinlik İşlemleri</a>
            <a href="{{ route('denetim.uye') }}" class="menu-item">Üye İşlemleri</a>
            <a href="{{ route('denetim.formlar') }}" class="menu-item">Form İşlemleri</a>
            <a href="{{ route('denetim.panel') }}" class="menu-item">Web Arayüz İşlemleri</a>
            <div class="menu-item" onclick="window.location.href='{{ route('kesfet') }}'">Çıkış</div>
        </div>

    </div>

    <div class="content" id="web">
        <div class="form-container">
            <h2>Etkinlik Denetim İşlemleri</h2>
            <div class="info-section mb-4">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <h3>Talep Etkinlikler</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>Topluluk Adı</th>
                                <th>Logo</th>
                                <th>Etkinlik Başlığı</th>
                                <th>Etkinlik Tarihi</th>
                                <th>Etkinlik Tanıtım Afişi</th>
                                <th>Etkinlik Detayları</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($onayBekleyenEtkinlikler as $etkinlik)
                                <tr>
                                    <td>{{ $etkinlik->topluluk_adi }}</td>
                                    <td><a href="#" onclick="openImage('{{ asset('images/logo/'.$etkinlik->logo) }}')">
                                            <img src="{{ asset('images/logo/'.$etkinlik->logo) }}" alt="Logo" width="60">
                                        </a></td>
                                    <td>{{$etkinlik->etkinlik_adi}}</td>
                                    <td>{{ \Carbon\Carbon::parse($etkinlik->tarih)->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <a href="#" onclick="openImage('{{ asset('images/etkinlik/'.$etkinlik->gorsel) }}')">
                                            <img src="{{  asset('images/etkinlik/'.$etkinlik->gorsel) }}" alt="Afiş" width="100">
                                        </a>
                                    </td>
                                    <td><button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#textModal"
                                                data-text="**Kısa Metin:** {{$etkinlik->bilgi}}**Açıklama:** {{$etkinlik->metin}}">Detaylar</button></td>
                                    <td>
                                        <form action="{{ route('onay.islemi') }}" method="POST" class="etkinlik-form">
                                            @csrf
                                            <input type="hidden" name="tip" value=1>
                                        <input type="hidden" value="{{$etkinlik->onay_id}}" name="onay_id">
                                        <button  type="submit" class="btn btn-approve" name="onay" value="1">Onayla</button>
                                        <button  type="submit" class="btn btn-reject btn-sm" name='reddet'onclick="toggleRejectReason(this)">Reddet</button>
                                        <div class="red-reason" style="display:none">
                                            <textarea name="mesaj" placeholder="Red sebebini yazınız..."></textarea>
                                            <button type="submit" name="onay" value="2" class="btn-send btn-sm">Gönder</button>
                                        </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="info-section mb-4">
                <h3>Gerçekleşmiş Etkinlikler</h3>
                @if(session('successp    '))
                    <div class="alert alert-success">
                        {{ session('successp') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center w-100">
                        <thead class="table-dark">
                            <tr>
                                <th>Topluluk Adı</th>
                                <th>Logo</th>
                                <th>Etkinlik Başlığı</th>
                                <th>Etkinlik Tarihi</th>
                                <th>Etkinlik Resmi</th>
                                <th>Etkinlik Detayları</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($aktifEtkinlikler as $etkinlik)
                            <tr>
                                <td>{{ $etkinlik->topluluk_adi }}</td>
                                <td><a href="#" onclick="openImage('{{ asset('images/logo/'.$etkinlik->logo) }}')">
                                        <img src="{{ asset('images/logo/'.$etkinlik->logo) }}" alt="Logo" width="60">
                                    </a></td>
                                <td>{{$etkinlik->etkinlik_adi}}</td>
                                <td>{{ \Carbon\Carbon::parse($etkinlik->tarih)->format('d.m.Y H:i') }}</td>
                                <td>
                                    <a onclick="openImage('{{ asset('images/etkinlik/'.$etkinlik->gorsel) }}')">
                                        <img src="{{  asset('images/etkinlik/'.$etkinlik->gorsel) }}" alt="Afiş" width="100">
                                    </a>
                                </td>
                                <td><button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#textModal"
                                            data-text="**Kısa Metin:** {{$etkinlik->bilgi}}**Açıklama:** {{$etkinlik->metin}}">Detaylar</button></td>
                                <td>
                                    <form action="{{ route('onay.islemi') }}" method="POST" class="etkinlik-form">
                                        @csrf
                                        <input type="hidden" name="tip" value=2>
                                        <input type="hidden" value="{{$etkinlik->onay_id}}" name="onay_id">
                                        <button  type="submit" class="btn btn-approve" name="onay" value="1">Onayla</button>
                                        <div class="red-reason" style="display:block">
                                            <textarea name="mesaj" placeholder="Red sebebini yazınız..."></textarea>
                                            <button type="submit" name="onay" value="2" class="btn-reject btn-sm">Gönder</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="textModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Etkinlik Detayları</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body" id="modalTextContent">
                    </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img id="modalImage" class="modal-img" src="" alt="Büyük Görsel" style="max-width: 100%; height: auto;">
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
    <script src="{{ asset('js/denetim_etkinlik.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#textModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) // Tetikleyen buton
                var text = button.data('text') // Butondan etkinlik detaylarını al
                var modalBodyInput = $(this).find('#modalTextContent')
                modalBodyInput.html('<p>' + text.replace(/\n/g, '<br>') + '</p>'); // Metni modal içeriğine ekle ve satır sonlarını <br> ile değiştir
            });
        });
        function openImage(imageUrl) {
            $('#modalImage').attr('src', imageUrl);
            $('#imageModal').modal('show');
        }
            function toggleRejectReason(button) {
            const reasonDiv = button.nextElementSibling;
            if (reasonDiv.style.display === "none" || reasonDiv.style.display === "") {
            reasonDiv.style.display = "block";
        } else {
            reasonDiv.style.display = "none";
        }
        }
    </script>

</body>

</html>

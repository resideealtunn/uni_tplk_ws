<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topluluk Denetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/denetim_etkinlik.css') }}">
</head>

<body>
<div class="sidebar">
    <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
    <h2>{{session('unvan')}}</h2>
    <h3>{{session('isim')}}</h3>
    <p>{{session('birim')}}</p>

    <div class="menu">
        <a href="{{ route('denetim.topluluk') }}" class="menu-item">Topluluk İşlemleri</a>
        <a href="{{ route('denetim.etkinlik') }}" class="menu-item ">Etkinlik İşlemleri</a>
        <a href="{{ route('denetim.uye') }}" class="menu-item">Üye İşlemleri</a>
        <a href="{{ route('denetim.formlar') }}" class="menu-item">Form İşlemleri</a>
        <a href="{{ route('denetim.panel') }}" class="menu-item active">Web Arayüz İşlemleri</a>
        <div class="menu-item" onclick="window.location.href='{{ route('kesfet') }}'">Çıkış</div>
    </div>
</div>

<div class="content" id="web">
    <div class="form-container">
        <h2>Web Arayüz Denetim İşlemleri</h2>

        <div class="info-section mb-4">
            <h3>Topluluklar Listesi</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center w-100">
                    <thead class="table-dark">
                    <tr>
                        <th>Topluluk ID</th>
                        <th>Topluluk Adı</th>
                        <th>Logo</th>
                        <th>Arkaplan</th>
                        <th>Slogan</th>
                        <th>Vizyon & Misyon</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Bilişim Topluluğu</td>
                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#logoModal1"><img src="{{ asset('images/logo/bilisimlogo.png') }}" alt="Logo" width="60"></a></td>
                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#backgroundModal1"><img src="{{ asset('images/etkinlik/img1.jpg') }}" alt="Arkaplan" width="100"></a></td>
                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#sloganModal1">"Geleceği beraber şekillendireceğiz"</a></td>
                        <td><button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#textModal"
                                    data-text="**Vizyon:** Teknolojiyi anlayan, üreten ve geleceğe yön veren bir topluluk olmak.&#10;**Misyon:** Üyelerimize en son teknolojiler hakkında eğitimler vermek, projeler geliştirmelerini desteklemek ve sektördeki gelişmelerden haberdar olmalarını sağlamak.">Detaylar</button></td>
                        <td>
                            <button class="btn btn-approve btn-sm" onclick="approve()">Onayla</button>
                            <button class="btn btn-reject btn-sm" onclick="showRejectReason(this)">Reddet</button>
                            <div class="red-reason" style="display:none;">
                                <textarea placeholder="Red sebebini yazınız..."></textarea>
                                <button class="btn-send btn-sm">Gönder</button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Sağlık Topluluğu</td>
                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#logoModal2"><img src="{{ asset('images/logo/logo.png') }}" alt="Logo" width="60"></a></td>
                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#backgroundModal2"><img src="{{ asset('images/etkinlik/img2.jpg') }}" alt="Arkaplan" width="100"></a></td>
                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#sloganModal2">"Sağlıkla Geleceğe"</a></td>
                        <td><button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#textModal"
                                    data-text="**Vizyon:** Sağlıklı bir üniversite topluluğu ve bilinçli bireyler yetiştirmek.&#10;**Misyon:** Sağlık seminerleri düzenlemek, farkındalık kampanyaları yürütmek ve öğrencilerin sağlıklı yaşam alışkanlıkları kazanmalarına destek olmak.">Detaylar</button></td>
                        <td>
                            <button class="btn btn-approve btn-sm" onclick="approve()">Onayla</button>
                            <button class="btn btn-reject btn-sm" onclick="toggleRejectReason(this)">Reddet</button>
                            <div class="red-reason" style="display:none;">
                                <textarea placeholder="Red sebebini yazınız..."></textarea>
                                <button class="btn-send btn-sm">Gönder</button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>Müzik Topluluğu</td>
                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#logoModal3"><img src="{{ asset('images/logo/logo2.png') }}" alt="Logo" width="60"></a></td>
                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#backgroundModal3"><img src="{{ asset('images/etkinlik/img3.jpg') }}" alt="Arkaplan" width="100"></a></td>
                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#sloganModal3">"Ritmi Hissedin"</a></td>
                        <td><button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#textModal"
                                    data-text="**Vizyon:** Müziğin birleştirici gücünü kullanarak sanata değer veren bir öğrenci topluluğu oluşturmak.&#10;**Misyon:** Müzik etkinlikleri organize etmek, farklı enstrümanları tanıtmak ve öğrencilerin müzikal yeteneklerini geliştirmelerine olanak sağlamak.">Detaylar</button></td>
                        <td>
                            <button class="btn btn-approve btn-sm" onclick="approve()">Onayla</button>
                            <button class="btn btn-reject btn-sm" onclick="toggleRejectReason(this)">Reddet</button>
                            <div class="red-reason" style="display:none;">
                                <textarea placeholder="Red sebebini yazınız..."></textarea>
                                <button class="btn-send btn-sm">Gönder</button>
                            </div>
                        </td>
                    </tr>
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
                <h5 class="modal-title">Detaylı Görüntü</h5>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/denetim_etkinlik.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#textModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Tetikleyen buton
            var text = button.data('text') // Butondan vizyon ve misyon metnini al
            var modalBodyInput = $(this).find('#modalTextContent')
            modalBodyInput.html('<p>' + text.replace(/\n/g, '<br>') + '</p>'); // Metni modal içeriğine ekle ve satır sonlarını <br> ile değiştir
        });
    });

    function toggleRejectReason(button) {
        const reasonDiv = button.closest('tr').querySelector('.red-reason');
        reasonDiv.style.display = reasonDiv.style.display === 'block' ? 'none' : 'block';
    }

    function approve() {
        alert("Topluluk Onaylandı.");
    }
    function openImage(imageUrl) {
        $('#modalImage').attr('src', imageUrl);
        $('#imageModal').modal('show');
    }
    function showContent(id) {
        var contents = document.querySelectorAll('.content');
        contents.forEach(function(content) {
            content.style.display = 'none';
        });
        document.getElementById(id).style.display = 'block';

        var menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(function(item) {
            item.classList.remove('active');
            if (item.getAttribute('onclick') && item.getAttribute('onclick').includes(`showContent('${id}')`)) {
                item.classList.add('active');
            } else if (item.getAttribute('href') && item.getAttribute('href').includes(id.toLowerCase().replace('i̇', 'i').replace(' ', '_'))) {
                item.classList.add('active');
            }
        });
        if (id === 'web') {
            const webMenuItem = Array.from(menuItems).find(item => item.textContent.includes('Web Arayüz İşlemleri'));
            if (webMenuItem) {
                webMenuItem.classList.add('active');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        showContent('web');
    });
</script>
<footer class="footer" style="margin-left:150px; width: calc(100%); background-color: #ffffff; color: #003366; padding: 40px 20px 20px; font-family: Arial, sans-serif; box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05); border-top: 2px solid #dce3ea;">
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
</body>

</html>

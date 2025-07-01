<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topluluk Yönetimi</title>
    <link rel="stylesheet" href="{{ asset('css/style_panels.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="page-wrapper">
    <div class="sidebar">
        <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
        <h2>{{ session('topluluk') }}</h2>
        <h3>{{ session('isim') }}</h3>
        <p>{{ session('rol') }}</p>

        <div class="menu">
            <a href="/yonetici_panel" class="menu-item active">Web Arayüz İşlemleri</a>
            <a href="/etkinlik_islemleri" class="menu-item">Etkinlik İşlemleri</a>
            <a href="/uye_islemleri" class="menu-item">Üye İşlemleri</a>
            <a href="/panel_geribildirim" class="menu-item ">Denetim Geri Bildirimleri</a>
            <a href="javascript:void(0);" class="menu-item" onclick="document.getElementById('cikisForm').submit();">Çıkış</a>
            <form id="cikisForm" action="{{ route('cikis') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    <div class="main-content">
        <div class="content" id="web">
            <div class="form-container">
                <h2>Web Arayüz Bilgileri</h2>
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
                <form method="POST" enctype="multipart/form-data" action="{{ route('yonetici.guncelle') }}" id="logoForm">
                    @csrf
                    <div class="form-group">
                        <label for="logo">Topluluk Logosu</label>
                        <input type="file" id="logo" name="logo" accept="image/*" onchange="enableButton('logok', this)">
                        <div id="logo-preview"></div>
                        <input type="submit" name="logok" id="logok" value="Kaydet" disabled>
                        <div id="logo-alert"></div>
                    </div>
                </form>
                <form method="POST" enctype="multipart/form-data" action="{{ route('yonetici.guncelle') }}" id="bgForm">
                    @csrf
                    <div class="form-group">
                        <label for="background">Sayfa Arka Plan Resmi</label>
                        <input type="file" id="background" name="bg" accept="image/*" onchange="enableButton('bgk', this)">
                        <div id="background-preview"></div>
                        <input type="submit" name="bgk" id="bgk" value="Kaydet" disabled>
                        <div id="bg-alert"></div>
                    </div>
                </form>
                <form method="POST" action="{{ route('yonetici.guncelle') }}" id="sloganForm">
                    @csrf
                    <div class="form-group">
                        <label for="slogan">Slogan</label>
                        <input type="text" id="slogan" name='slogan' placeholder="Slogan giriniz" maxlength="100" oninput="enableButton('slogank', this); checkSloganLength(this)">
                        <div id="slogan-length-warning" style="color: red; display: none; font-size: 0.95em;">En fazla 100 karakter girebilirsiniz.</div>
                        <div id="slogan-length-info" style="color: #888; font-size: 0.95em;"></div>
                        <input type="submit" name="slogank" id="slogank" value="Kaydet" disabled>
                        <div id="slogan-alert"></div>
                    </div>
                </form>
                <form method="POST" action="{{ route('yonetici.guncelle') }}" id="vizyonForm">
                    @csrf
                    <div class="form-group">
                        <label for="vizyon">Vizyon</label>
                        <textarea id="vizyon" rows="2" placeholder="Vizyon giriniz" name="vizyon" oninput="enableButton('vizyonk', this)"></textarea>
                        <input type="submit" name="vizyonk" id="vizyonk" value="Kaydet" disabled>
                        <div id="vizyon-alert"></div>
                    </div>
                </form>
                <form method="POST" action="{{ route('yonetici.guncelle') }}" id="misyonForm">
                    @csrf
                    <div class="form-group">
                        <label for="misyon">Misyon</label>
                        <textarea id="misyon" rows="2" placeholder="Misyon giriniz" name="misyon" oninput="enableButton('misyonk', this)"></textarea>
                        <input type="submit" name="misyonk" id="misyonk" value="Kaydet" disabled>
                        <div id="misyon-alert"></div>
                    </div>
                </form>
                <form method="POST" enctype="multipart/form-data" action="{{ route('yonetici.guncelle') }}" id="tuzukForm">
                    @csrf
                    <div class="form-group">
                        <label for="tuzuk">Tüzük</label>
                        <input type="file" id="tuzuk" name="tuzuk" accept="application/pdf" onchange="enableButton('tuzukk', this)">
                        <input type="submit" name="tuzukk" id="tuzukk" value="Kaydet" disabled>
                        <div id="tuzuk-alert"></div>
                    </div>
                </form>
                <form method="POST" action="{{ route('yonetici.guncelle') }}" id="instagramForm">
                    @csrf
                    <div class="form-group">
                        <label for="instagram">Instagram Linki</label>
                        <input type="url" id="instagram" name="instagram" placeholder="Instagram linki giriniz" oninput="enableButton('instagramk', this)">
                        <input type="submit" name="instagramk" id="instagramk" value="Kaydet" disabled>
                        <div id="instagram-alert"></div>
                    </div>
                </form>
                <form method="POST" action="{{ route('yonetici.guncelle') }}" id="whatsappForm">
                    @csrf
                    <div class="form-group">
                        <label for="whatsapp">WhatsApp Linki</label>
                        <input type="url" id="whatsapp" name="whatsapp" placeholder="WhatsApp linki giriniz" oninput="enableButton('whatsappk', this)">
                        <input type="submit" name="whatsappk" id="whatsappk" value="Kaydet" disabled>
                        <div id="whatsapp-alert"></div>
                    </div>
                </form>
                <form method="POST" action="{{ route('yonetici.guncelle') }}" id="linkedlnForm">
                    @csrf
                    <div class="form-group">
                        <label for="linkedln">LinkedIn Linki</label>
                        <input type="url" id="linkedln" name="linkedln" placeholder="LinkedIn linki giriniz" oninput="enableButton('linkedlnk', this)">
                        <input type="submit" name="linkedlnk" id="linkedlnk" value="Kaydet" disabled>
                        <div id="linkedln-alert"></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="content" id="etkinlik">
            <div class="form-container">
                <h2>Etkinlik İşlemleri</h2>
                <p>Buraya etkinlik işlemleri form veya liste gelebilir.</p>
            </div>
        </div>
        <div class="content" id="uye">
            <div class="form-container">
                <h2>Üye İşlemleri</h2>
                <p>Buraya üye işlemleri form veya liste gelebilir.</p>
            </div>
        </div>
        <div class="content" id="cikis">
            <div class="form-container">
                <h2>Çıkış</h2>
                <p>Buraya üye işlemleri form veya liste gelebilir.</p>
            </div>
        </div>


    </div>
</div>
@php
            $sosyal = null;
            if(session('t_id')) {
                $sosyal = \DB::table('sosyal_medya')->where('t_id', session('t_id'))->first();
            }
@endphp
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
                @if($sosyal && $sosyal->i_onay == 1 && $sosyal->instagram)
                    <a href="{{ $sosyal->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                @else
                    <span class="social-icon-disabled"><i class="fab fa-instagram"></i></span>
                @endif
                @if($sosyal && $sosyal->w_onay == 1 && $sosyal->whatsapp)
                    <a href="{{ $sosyal->whatsapp }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                @else
                    <span class="social-icon-disabled"><i class="fab fa-whatsapp"></i></span>
                @endif
                @if($sosyal && $sosyal->l_onay == 1 && $sosyal->linkedln)
                    <a href="{{ $sosyal->linkedln }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                @else
                    <span class="social-icon-disabled"><i class="fab fa-linkedin-in"></i></span>
                @endif
            </div>
            <p>topluluk@erbakan.edu.tr</p>
        </div>
    </div>
    <div class="footer-bottom">
        © 2022 Necmettin Erbakan Üniversitesi
    </div>
</footer>
<script src="{{ asset('js/js_panels.js') }}"></script>
<script>
    function enableButton(buttonId, input) {
        if (input.type === "file") {
            document.getElementById(buttonId).disabled = !input.files.length;
        } else {
            document.getElementById(buttonId).disabled = !input.value.trim();
        }
    }

    // Logo önizleme
    document.getElementById('logo').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('logo-preview').innerHTML = `<img src="${e.target.result}" alt="Logo Önizleme" style="max-width: 100px;">`;
            }
            reader.readAsDataURL(file);
        }
    });

    // Arkaplan önizleme
    document.getElementById('background').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('background-preview').innerHTML = `<img src="${e.target.result}" alt="Arkaplan Önizleme" style="max-width: 200px;">`;
            }
            reader.readAsDataURL(file);
        }
    });

    function checkSloganLength(input) {
        const warning = document.getElementById('slogan-length-warning');
        const info = document.getElementById('slogan-length-info');
        const max = 100;
        const len = input.value.length;
        if (len >= max) {
            warning.style.display = 'block';
        } else {
            warning.style.display = 'none';
        }
        info.textContent = `${len}/${max} karakter`;
    }
</script>
</body>

</html>

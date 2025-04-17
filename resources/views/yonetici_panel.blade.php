<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topluluk Yönetimi</title>
    <link rel="stylesheet" href="{{ asset('css/style_panels.css') }}">
</head>

<body>
<div class="sidebar">
    <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
    <h2>{{ session('topluluk') }}</h2>
    <h3>{{ session('isim') }}</h3>
    <p>{{ session('rol') }}</p>

    <div class="menu">
        <div class="menu-item" onclick="showContent('web')">Web Arayüz İşlemleri</div>
        <a href="/etkinlik_islemleri" class="menu-item">Etkinlik İşlemleri</a>
        <div class="menu-item" onclick="showContent('uye')">Üye İşlemleri</div>
        <div class="menu-item" onclick="showContent('cikis')">Çıkış</div>
    </div>
</div>

<div class="content" id="web">
    <div class="form-container">
        <h2>Web Arayüz Bilgileri</h2>
        <form method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="logo">Topluluk Logosu</label>
                <input type="file" id="logo" name="logo" value="{{ asset('images/logo'.$veri->gorsel ?? '') }}">
                <div id="logo-preview"></div>
            </div>
            <div class="form-group">
                <label for="background">Sayfa Arka Plan Resmi</label>
                <input type="file" id="background" name="bg">
                <div id="background-preview"></div>
            </div>
            <div class="form-group">
                <label for="slogan">Slogan</label>
                <input type="text" id="slogan" name="slogan" value="{{ $veri->slogan ?? '' }}">
            </div>
            <div class="form-group">
                <label for="vizyon">Vizyon</label>
                <textarea id="vizyon" rows="2" placeholder="Vizyon giriniz" name="vizyon">{{ $veri->vizyon ?? '' }}</textarea>
            </div>
            <div class="form-group">
                <label for="misyon">Misyon</label>
                <textarea id="misyon" rows="2" placeholder="Misyon giriniz" name="misyon">{{ $veri->misyon ?? '' }}</textarea>
            </div>
            <div class="form-group">
                <label for="tuzuk">Tüzük</label>
                <input type="file" id="tuzul" name="tuzuk">
            </div>
            <button type="submit" name="Guncelle">Güncelle</button>
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

<script src="{{ asset('js/js_panels.js') }}"></script>
<script>
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
</script>
</body>

</html>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Girişi - NEU</title>
    <link rel="stylesheet" href="{{ asset('css/yonetici_giris.css') }}">
</head>
<body>
<div class="login-container">
    <div class="login-box">
        <div class="logo-container">
            <img src="{{ asset('images/logo/neu_logo.png') }}" alt="NEU Logo" class="neu-logo">
        </div>
        @if(session('error'))
            <div class="alert alert-danger" style="text-align:center">
                {{ session('error') }}
            </div>
        @endif
        @if(session('danger'))
            <div class="alert alert-danger" style="text-align:center">
                {{ session('danger') }}
            </div>
        @endif
        <form id="loginForm" class="login-form" method="POST" action="{{ route('yonetici.giris.post') }}">
            @csrf
            <div class="form-group">
                <label for="tc">TC Kimlik No</label>
                <input type="text" id="tcKimlik" name="tc" required>
            </div>
            <div class="form-group">
                <label for="sifre">Şifre</label>
                <input type="password" id="sifre" name="sifre" required>
            </div>
            <div class="form-group">
            <div class="g-recaptcha" data-sitekey="6LcFD6YpAAAAAGSGbeYUc0HSaJZZp_EBJfMqyX2Q"></div>
            </div>
            <button type="submit" class="login-button">Giriş Yap</button>
        </form>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Geri Bildirim</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        h3 { color: #333; }
        p { font-size: 16px; color: #555; }
    </style>
</head>
<body>
<div class="container">
    <h3>Yeni Geri Bildirim Geldi!</h3>
    <p><strong>Ad Soyad:</strong> {{ $isim }}</p>
    <p><strong>E-posta:</strong> {{ $email }}</p>
    <p><strong>Mesaj:</strong></p>
    <p>{{ $mesaj }}</p>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keşfet Ekranı</title>
    <link rel="stylesheet" href="{{ asset('css/style_kesfet.css') }}">
    <style>
        .menu li a.active {
            color: #FFA500;
        }
        .menu li a {
            padding-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                 <img src="{{ asset('images/neu_logo.png') }}">
            </div>
            <ul class="menu">
                <li><a href="/kesfet" id="homeBtn">Ana Sayfa</a></li>
                <li><a href="/topluluklar" id="communitiesBtn">Topluluklar</a></li>
                <li><a href="#" id="formsBtn">Formlar</a></li>
                <li><a href="/yonetici" id="adminBtn">Yönetici İşlemleri</a></li>
            </ul>
        </div>

        <!-- Content Area -->
        <div class="content">
            <h1 id="contentTitle">NEÜ Etkinlikleri KEŞFET</h1>
            <div id="contentArea" class="explore-grid">
                <!-- Etkinliklerin Görselleri -->
                <div class="event-card">
                <img src="{{ asset('images/images/img1.jpg') }}" alt="Etkinlik 1">
                <div class="event-details">
                        <h3>Etkinlik 1</h3>
                        <p>Topluluk: KeşfetTopluluğu</p>
                        <p>Açıklama: Bu etkinlik keşfetmek için harika bir fırsat.</p>
                    </div>
                </div>
                <div class="event-card">
                <img src="{{ asset('images/images/img2.jpg') }}" alt="Etkinlik 1">
                <div class="event-details">
                        <h3>Etkinlik 2</h3>
                        <p>Topluluk: KeşfetTopluluğu</p>
                        <p>Açıklama: Eğlenceli etkinlikler sizi bekliyor.</p>
                    </div>
                </div>
                <div class="event-card">
                <img src="{{ asset('images/images/img3.jpg') }}" alt="Etkinlik 1">
                <div class="event-details">
                        <h3>Etkinlik 3</h3>
                        <p>Topluluk: KeşfetTopluluğu</p>
                        <p>Açıklama: Yeni insanlar tanımak için harika bir yer.</p>
                    </div>
                </div>
                <div class="event-card">
                <img src="{{ asset('images/images/img4.jpg') }}" alt="Etkinlik 1">
                <div class="event-details">
                        <h3>Etkinlik 4</h3>
                        <p>Topluluk: KeşfetTopluluğu</p>
                        <p>Açıklama: Eğlenceli aktiviteler burada sizi bekliyor.</p>
                    </div>
                </div>
                <div class="event-card">
                <img src="{{ asset('images/images/img5.jpg') }}" alt="Etkinlik 1">
                <div class="event-details">
                        <h3>Etkinlik 5</h3>
                        <p>Topluluk: KeşfetTopluluğu</p>
                        <p>Açıklama: Eğlenceli bir keşif yolculuğu.</p>
                    </div>
                </div>
                <div class="event-card">
                <img src="{{ asset('images/images/img6.jpg') }}" alt="Etkinlik 1">
                <div class="event-details">
                        <h3>Etkinlik 6</h3>
                        <p>Topluluk: KeşfetTopluluğu</p>
                        <p>Açıklama: Hayatınızı değiştirecek deneyimler burada.</p>
                    </div>
                </div>
                <div class="event-card">
                <img src="{{ asset('images/images/img7.jpg') }}" alt="Etkinlik 1">
                <div class="event-details">
                        <h3>Etkinlik 7</h3>
                        <p>Topluluk: KeşfetTopluluğu</p>
                        <p>Açıklama: Yeni yerler keşfedin ve unutulmaz anlar biriktirin.</p>
                    </div>
                </div>
                <div class="event-card">
                <img src="{{ asset('images/images/img8.jpg') }}" alt="Etkinlik 1">
                <div class="event-details">
                        <h3>Etkinlik 8</h3>
                        <p>Topluluk: KeşfetTopluluğu</p>
                        <p>Açıklama: Doğayla iç içe bir etkinlik deneyimi.</p>
                    </div>
                </div>
                <div class="event-card">
                <img src="{{ asset('images/images/img9.jpg') }}" alt="Etkinlik 1">
                <div class="event-details">
                        <h3>Etkinlik 9</h3>
                        <p>Topluluk: KeşfetTopluluğu</p>
                        <p>Açıklama: Unutulmaz bir deneyim için katılın.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Modal -->
<div class="event-modal" id="eventModal">
    <div class="modal-content">
        <div class="modal-left">
            <img id="modalImage" src="" alt="Etkinlik Detayı" style="width:500px; height: 500px; border-radius: 10px;">
        </div>
        <div class="modal-right">
            <h3 id="modalTitle"></h3>
            <p id="modalCommunity"></p>
            <p id="modalShortDesc"></p>
            <p id="modalLongDesc"></p>
        </div>
        <button class="close-btn" id="closeModal">&times;</button>
    </div>
</div>

<script src="{{ asset('js/js_kesfet.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const menuItems = document.querySelectorAll('.menu li a');
        
        menuItems.forEach(item => {
            if (item.getAttribute('href') === currentPath) {
                item.classList.add('active');
            }
        });
    });
</script>
</body>
</html>

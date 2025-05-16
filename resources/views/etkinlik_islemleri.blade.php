<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlik İşlemleri</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style_panels.css') }}">
    <link rel="stylesheet" href="{{ asset('css/etkinlik_islemleri.css') }}">
</head>
<body>
<div class="sidebar">
    <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
    <h2>{{ session('topluluk') }}</h2>
    <h3>{{ session('isim') }}</h3>
    <p>{{ session('rol') }}</p>

    <div class="menu">
        <a href="/yonetici_panel" class="menu-item">Web Arayüz İşlemleri</a>
        <a href="/etkinlik_islemleri" class="menu-item active">Etkinlik İşlemleri</a>
        <a href="/uye_islemleri" class="menu-item ">Üye İşlemleri</a>

        <!-- Çıkış Div'i -->
        <div class="menu-item" ">
            Çıkış
        </div>
    </div>

</div>

    <div class="content active">
        <div class="action-container">
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
            <h1>Etkinlik İşlemleri</h1>

            <div class="action-card" onclick="showEtkinlikEkleModal()">
                <h2>Etkinlik Ekle</h2>
                <p>Yeni bir etkinlik oluşturmak için tıklayın</p>
            </div>

            <div class="action-card" onclick="showBasvuruModal()">
                <h2>Başvuru Aç/Kapat</h2>
                <p>Etkinlik başvurularını yönetmek için tıklayın</p>
            </div>

            <div class="action-card" onclick="showYoklamaModal()">
                <h2>Yoklama Aç/Kapat</h2>
                <p>Etkinlik yoklamalarını yönetmek için tıklayın</p>
            </div>

            <div class="action-card" onclick="showPaylasModal()">
                <h2>Etkinlik Paylaş</h2>
                <p>Etkinliği paylaşmak için tıklayın</p>
            </div>

            <div class="action-card" onclick="showBasvuruListeModal()">
                <h2>Etkinlik Başvurularını Listele</h2>
                <p>Etkinlik başvurularını görüntülemek için tıklayın</p>
            </div>
        </div>
    </div>
    <!-- Etkinlik Ekle Modal -->
    <div id="etkinlikEkleModal" class="modal">
        <div class="modal-content">
            <h2>Etkinlik Ekle</h2>
            <form id="etkinlikEkleForm" enctype="multipart/form-data" action="{{ route('etkinlik.ekle') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="etkinlikBaslik">Etkinlik Başlığı:</label>
                    <input type="text" id="etkinlikBaslik" name="baslik" required>
                </div>
                <div class="form-group">
                    <label for="etkinlikKisaBilgi">Kısa Bilgi:</label>
                    <input type="text" id="etkinlikKisaBilgi" name="kisa_bilgi" required>
                </div>
                <div class="form-group">
                    <label for="etkinlikAciklama">Açıklama:</label>
                    <textarea id="etkinlikAciklama" name="aciklama" required></textarea>
                </div>
                <div class="form-group">
                    <label for="etkinlikAfiş">Tanıtım Afişi:</label>
                    <input type="file" id="etkinlikAfiş" name="afis" required>
                </div>
                <div class="form-group">
                    <label for="etkinliktarih">Etkinlik Tarihi:</label>
                    <input type="datetime-local" id="etkinliktarih" name="tarih" required>
                </div>
                <input type="submit" class="btn" name="etkinlik_ekle" value="Etkinlik Ekle">
                <button type="button" class="btn btn-cancel" onclick="closeModal('etkinlikEkleModal')">İptal</button>
            </form>
        </div>
    </div>

    <!-- Başvuru Aç/Kapat Modal -->
    <div id="basvuruModal" class="modal">
        <div class="modal-content">
            <h2>Başvuru Aç/Kapat</h2>
            <form id="basvuruForm" method="POST" action="{{ route('basvuru.guncelle') }}">
                @csrf
                <div class="form-group">
                    <label for="etkinlikSec">Etkinlik Seçin:</label>
                    <select id="etkinlikSec" name="etkinlik_id" class="form-control" onchange="guncelleDurum(this)" required>
                        <option value="">Etkinlik seçiniz</option>
                        @foreach($onaylanmisEtkinlikler as $etkinlik)
                            <option value="{{ $etkinlik->id }}" data-durum="{{ $etkinlik->b_durum }}">
                                {{ $etkinlik->isim }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Mevcut Durum:</label>
                    <p id="mevcutDurum" class="durum-text">Etkinlik seçiniz</p>
                </div>
                <button type="submit" class="btn">Başvuru Aç/Kapat</button>
                <button type="button" class="btn btn-cancel" onclick="closeModal('basvuruModal')">İptal</button>
            </form>
        </div>
    </div>

    <!-- Yoklama Aç/Kapat Modal -->
    <div id="yoklamaModal" class="modal">
        <div class="modal-content">
            <h2>Yoklama Aç/Kapat</h2>
            <form id="yoklamaForm" method="POST" action="{{ route('yoklama.guncelle') }}">
                @csrf
                <div class="form-group">
                    <label for="etkinlik_id">Etkinlik Seçin:</label>
                    <select id="etkinlik_id" name="etkinlik_id" class="form-control" required>
                        @foreach($onaylanmisEtkinlikler as $etkinlik)
                            <option value="{{ $etkinlik->id }}" {{ $etkinlik->y_durum == 1 ? 'selected' : '' }}>
                                {{ $etkinlik->isim }} ({{ $etkinlik->y_durum ? 'Açık' : 'Kapalı' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="durum" value="toggle"> {{-- Sadece toggle işlemi yapılır --}}

                <button type="submit" class="btn">Yoklamayı Aç / Kapat</button>
                <button type="button" class="btn btn-cancel" onclick="closeModal('yoklamaModal')">İptal</button>
            </form>
        </div>
    </div>

    <!-- Etkinlik Paylaş Modal -->
    <div id="paylasModal" class="modal">
        <div class="modal-content">
            <h2>Etkinlik Paylaş</h2>
            <form id="paylasForm" enctype="multipart/form-data" action="{{ route('etkinlik.paylas') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="paylasEtkinlikSec">Etkinlik Seçin:</label>
                    <select id="paylasEtkinlikSec" class="form-control" name="paylasEtkinlikSec" required>
                        <option value="">Etkinlik seçiniz</option>
                        @foreach($onaylanmisEtkinlikler as $etkinlik)
                            <option value="{{ $etkinlik->id }}">{{ $etkinlik->isim }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="paylasKisaBilgi">Kısa Bilgi:</label>
                    <input type="text" id="paylasKisaBilgi" class="form-control" name="paylasKisaBilgi" required>
                </div>
                <div class="form-group">
                    <label for="paylasAciklama">Açıklama:</label>
                    <textarea id="paylasAciklama" rows="4" class="form-control" name="paylasAciklama" required></textarea>
                </div>
                <div class="form-group">
                    <label for="paylasResim">Etkinlik Resmi:</label>
                    <input type="file" id="paylasResim" name="paylasResim" class="form-control" required>
                    <div id="resimOnizleme" class="resim-onizleme"></div>
                </div>
                <button type="submit" class="btn">Paylaş</button>
                <button type="button" class="btn btn-cancel" onclick="closeModal('paylasModal')">İptal</button>
            </form>
        </div>
    </div>

    <!-- Etkinlik Başvurularını Listele Modal -->
    <div id="basvuruListeModal" class="modal">
        <div class="modal-content">
            <h2>Etkinlik Başvurularını Listele</h2>
            <form id="basvuruListeForm" method="POST" action="{{ route('basvuru.goster') }}">
                @csrf
                <div class="form-group">
                    <label for="basvuruListeEtkinlikSec">Etkinlik Seçin:</label>
                    <select id="basvuruListeEtkinlikSec" class="form-control"  name="etkinlik_id" required>
                        @foreach($onaylanmisEtkinlikler as $etkinlik)
                            <option value="{{ $etkinlik->id }}" data-durum="{{ $etkinlik->b_durum }}">
                                {{ $etkinlik->isim }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn">Göster</button>
                </div>
            </form>
                <div class="basvuru-listesi">
                    <table>
                        <thead>
                            <tr>
                                <th>Ad Soyad</th>
                                <th>Ögr. No</th>
                                <th>Bölüm</th>
                                <th>Telefon</th>
                                <th>Toplam Katılım</th>
                            </tr>
                        </thead>
                        <tbody id="basvuruListesi">

                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('basvuruListeModal')">Kapat</button>
        </div>
    </div>

    <script src="{{ asset('js/js_etkinlik_islemleri.js') }}"></script>
    <script>
        function guncelleDurum(selectElement) {
            const secilen = selectElement.options[selectElement.selectedIndex];
            const durum = secilen.getAttribute("data-durum");

            const durumYazi = document.getElementById("mevcutDurum");
            if (durum === "1") {
                durumYazi.textContent = "Başvurular Açık";
            } else if (durum === "0") {
                durumYazi.textContent = "Başvurular Kapalı";
            } else {
                durumYazi.textContent = "Durum bilinmiyor";
            }
        }

        document.getElementById('basvuruListeForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const etkinlikId = document.getElementById('basvuruListeEtkinlikSec').value;

            fetch('{{ route("basvuru.goster") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ etkinlik_id: etkinlikId })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const basvuruListesi = document.getElementById('basvuruListesi');
                basvuruListesi.innerHTML = ''; // Listeyi temizle

                if (data.length === 0) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = '<td colspan="5" class="text-center">Bu etkinliğe henüz başvuru yapılmamış.</td>';
                    basvuruListesi.appendChild(tr);
                    return;
                }

                data.forEach(basvuru => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${basvuru.isim}</td>
                        <td>${basvuru.numara}</td>
                        <td>${basvuru.bolum}</td>
                        <td>${basvuru.tel}</td>
                        <td>${basvuru.toplam_katilim}</td>
                    `;
                    basvuruListesi.appendChild(tr);
                });
            })
            .catch(error => {
                console.error('Başvuru verisi alınamadı:', error);
                const basvuruListesi = document.getElementById('basvuruListesi');
                basvuruListesi.innerHTML = '<tr><td colspan="5" class="text-center">Başvurular yüklenirken bir hata oluştu.</td></tr>';
            });
        });
    </script>
</body>
</html>

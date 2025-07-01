<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Etkinlik İşlemleri</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style_panels.css') }}">
    <link rel="stylesheet" href="{{ asset('css/etkinlik_islemleri.css') }}">
</head>
<body>
<div class="page-wrapper">
    <div class="sidebar">
        <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
        <h2>{{ session('topluluk') }}</h2>
        <h3>{{ session('isim') }}</h3>
        <p>{{ session('rol') }}</p>
        <div class="menu">
            <a href="/yonetici_panel" class="menu-item">Web Arayüz İşlemleri</a>
            <a href="/etkinlik_islemleri" class="menu-item active">Etkinlik İşlemleri</a>
            <a href="/uye_islemleri" class="menu-item">Üye İşlemleri</a>
            <a href="/panel_geribildirim" class="menu-item ">Denetim Geri Bildirimleri</a>
            <a href="javascript:void(0);" class="menu-item" onclick="document.getElementById('cikisForm').submit();">Çıkış</a>
            <form id="cikisForm" action="{{ route('cikis') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    <div class="main-content">
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
                    <p>Etkinlik başvurularını yönetmek için tıklayın. Başvuruyu açtığınızda etkinlik keşfette görünecektir.</p>
                </div>
                <div class="action-card" onclick="showKesfetModal()">
                    <h2>Etkinliği Keşfette Göster/Gizle</h2>
                    <p>Etkinliğin keşfet sayfasında görünürlüğünü yönetmek için tıklayın</p>
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
                <div class="action-card" onclick="showYoklamaDurumModal()">
                    <h2>Yoklama Durumunu Güncelle</h2>
                    <p>Etkinlik yoklama durumlarını güncellemek için tıklayın</p>
                </div>
            </div>
        </div>
        <!-- Etkinlik Ekle Modal -->
        <div id="etkinlikEkleModal" class="modal">
            <div class="modal-content">
                <h2>Etkinlik Ekle</h2>
                <form id="etkinlikEkleForm" enctype="multipart/form-data" action="{{ route('etkinlik.ekle') }}" method="POST" onsubmit="return validateEtkinlikForm()">
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
                        <label for="etkinlikKonum">Konum:</label>
                        <input type="text" id="etkinlikKonum" name="konum" required>
                    </div>
                    <div class="form-group">
                        <label for="etkinlikAfiş">Tanıtım Afişi:</label>
                        <input type="file" id="etkinlikAfiş" name="afis" required accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="etkinlikBaslangicTarih">Etkinlik Başlangıç Tarihi ve Saati:</label>
                        <input type="datetime-local" id="etkinlikBaslangicTarih" name="tarih" required>
                    </div>
                    <div class="form-group">
                        <label for="etkinlikBitisTarih">Etkinlik Bitiş Tarihi ve Saati:</label>
                        <input type="datetime-local" id="etkinlikBitisTarih" name="bitis_tarihi" required>
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

        <!-- Keşfette Göster/Gizle Modal -->
        <div id="kesfetModal" class="modal">
            <div class="modal-content">
                <h2>Etkinliği Keşfette Göster/Gizle</h2>
                <form id="kesfetForm" method="POST" action="{{ route('kesfet.guncelle') }}">
                    @csrf
                    <div class="form-group">
                        <label for="kesfetEtkinlikSec">Etkinlik Seçin:</label>
                        <select id="kesfetEtkinlikSec" name="etkinlik_id" class="form-control" onchange="guncelleKesfetDurum(this)" required>
                            <option value="">Etkinlik seçiniz</option>
                            @foreach($onaylanmisEtkinlikler as $etkinlik)
                                <option value="{{ $etkinlik->id }}" data-durum="{{ $etkinlik->k_durum }}">
                                    {{ $etkinlik->isim }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mevcut Durum:</label>
                        <p id="kesfetMevcutDurum" class="durum-text">Etkinlik seçiniz</p>
                    </div>
                    <button type="submit" class="btn">Keşfette Göster/Gizle</button>
                    <button type="button" class="btn btn-cancel" onclick="closeModal('kesfetModal')">İptal</button>
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
                        <input type="file" id="paylasResim" name="paylasResim" class="form-control" required accept="image/*">
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

        <!-- Yoklama Durumunu Güncelle Modal -->
        <div id="yoklamaDurumModal" class="modal">
            <div class="modal-content">
                <h2>Yoklama Durumunu Güncelle</h2>
                <div class="form-group">
                    <label for="yoklamaDurumEtkinlikSec">Etkinlik Seçin:</label>
                    <select id="yoklamaDurumEtkinlikSec" class="form-control">
                        <option value="">Etkinlik seçiniz</option>
                    </select>
                </div>
                <div class="basvuru-listesi">
                    <table>
                        <thead>
                            <tr>
                                <th>Ad Soyad</th>
                                <th>Öğr. No</th>
                                <th>Bölüm</th>
                                <th>Telefon</th>
                                <th>Yoklama Durumu</th>
                            </tr>
                        </thead>
                        <tbody id="yoklamaDurumListesi"></tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-cancel" onclick="closeModal('yoklamaDurumModal')">Kapat</button>
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

    function guncelleKesfetDurum(selectElement) {
        const secilen = selectElement.options[selectElement.selectedIndex];
        const durum = secilen.getAttribute("data-durum");

        const durumYazi = document.getElementById("kesfetMevcutDurum");
        if (durum === "1") {
            durumYazi.textContent = "Keşfette Görünür";
        } else if (durum === "0") {
            durumYazi.textContent = "Keşfette Gizli";
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
                    <td>${basvuru.isim} ${basvuru.soyisim}</td>
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

    function showYoklamaDurumModal() {
        document.getElementById('yoklamaDurumModal').style.display = 'block';
        // Etkinlikleri getir
        fetch('/panel/geribildirim/gerceklesen-etkinlik')
            .then(res => res.json())
            .then(data => {
                const etkinlikSelect = document.getElementById('yoklamaDurumEtkinlikSec');
                etkinlikSelect.innerHTML = '<option value="">Etkinlik seçiniz</option>';
                data.forEach(etk => {
                    if (etk.e_onay == 1) {
                        etkinlikSelect.innerHTML += `<option value="${etk.e_id}">${etk.baslik} (${etk.tarih})</option>`;
                    }
                });
            });
        document.getElementById('yoklamaDurumListesi').innerHTML = '';
    }

    document.getElementById('yoklamaDurumEtkinlikSec').addEventListener('change', function() {
        const e_id = this.value;
        if (!e_id) return;
        fetch('/yoklama-uyeler', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ e_id })
        })
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('yoklamaDurumListesi');
            tbody.innerHTML = '';
            data.forEach(uye => {
                const btnClass = uye.yoklama == 1 ? 'btn-danger' : 'btn-success';
                const btnText = uye.yoklama == 1 ? 'Katılmadı' : 'Katıldı';
                tbody.innerHTML += `
                    <tr>
                        <td>${uye.isim} ${uye.soyisim}</td>
                        <td>${uye.numara}</td>
                        <td>${uye.bolum}</td>
                        <td>${uye.tel}</td>
                        <td><button class="btn ${btnClass}" onclick="toggleYoklama(${uye.yoklama_id}, ${uye.yoklama}, ${uye.uye_id}, ${uye.e_id})">${btnText}</button></td>
                    </tr>
                `;
            });
        });
    });

    function toggleYoklama(yoklama_id, mevcut, uye_id = null, e_id = null) {
        const yeni = mevcut == 1 ? 0 : 1;
        fetch('/yoklama-durum-guncelle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ yoklama_id, yoklama: yeni, uye_id, e_id })
        })
        .then(res => res.json())
        .then(resp => {
            alert(resp.message);
            if (resp.success) {
                // Seçili etkinliği tekrar yükle
                document.getElementById('yoklamaDurumEtkinlikSec').dispatchEvent(new Event('change'));
            }
        });
    }
</script>
</body>
</html>

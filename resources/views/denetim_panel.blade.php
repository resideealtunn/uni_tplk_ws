<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topluluk Denetimi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/denetim_etkinlik.css') }}">
</head>

<body>

<!-- Hamburger Menü -->
<div class="hamburger" id="hamburger">
    <span></span>
    <span></span>
    <span></span>
</div>

<div class="sidebar" id="sidebar">
    <img src="{{ asset('images/logo/neu_logo.png') }}" alt="Logo">
    <h2>{{session('isim')}}</h2>
    <h3>{{session('unvan')}}</h3>
    <p>{{session('birim')}}</p>
    <div class="menu">
        <a href="{{ route('denetim.topluluk') }}" class="menu-item">Topluluk İşlemleri</a>
        <a href="{{ route('denetim.etkinlik') }}" class="menu-item">Etkinlik İşlemleri</a>
        <a href="{{ route('denetim.uye') }}" class="menu-item">Üye İşlemleri</a>
        <a href="{{ route('denetim.formlar') }}" class="menu-item">Form İşlemleri</a>
        <a href="{{ route('denetim.panel') }}" class="menu-item active">Web Arayüz İşlemleri</a>
        <div class="menu-item" onclick="window.location.href='{{ route('anasayfa') }}'">Çıkış</div>
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
                        <th>Tüzük</th>
                        <th>Vizyon & Misyon</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($topluluklar as $topluluk)
                    <tr>
                        <td>{{ $topluluk->id }}</td>
                        <td>{{ $topluluk->isim }}</td>
                        <td>
                            @if($topluluk->gorsel)
                                <img src="{{ asset('images/logo/' . $topluluk->gorsel) }}" alt="Logo" width="60" style="cursor:pointer" onclick="openImageModal('{{ asset('images/logo/' . $topluluk->gorsel) }}')">
                            @else
                                Yok
                            @endif
                        </td>
                        <td>
                            @if($topluluk->bg)
                                @php
                                    $bgPathEtkinlik = public_path('images/etkinlik/' . $topluluk->bg);
                                    $bgPathBackground = public_path('images/background/' . $topluluk->bg);
                                @endphp
                                @if(file_exists($bgPathBackground))
                                    <img src="{{ asset('images/background/' . $topluluk->bg) }}" alt="Arkaplan" width="100" style="cursor:pointer" onclick="openImageModal('{{ asset('images/background/' . $topluluk->bg) }}')">
                                @elseif(file_exists($bgPathEtkinlik))
                                    <img src="{{ asset('images/etkinlik/' . $topluluk->bg) }}" alt="Arkaplan" width="100" style="cursor:pointer" onclick="openImageModal('{{ asset('images/etkinlik/' . $topluluk->bg) }}')">
                                @else
                                    {{ $topluluk->bg }}
                                @endif
                            @else
                                Yok
                            @endif
                        </td>
                        <td>{{ $topluluk->slogan ?? 'Yok' }}</td>
                        <td>
                            @if($topluluk->tuzuk)
                                <a href="{{ asset('files/tuzuk/' . $topluluk->tuzuk) }}" target="_blank">
                                    <i class="fa fa-file-pdf" style="font-size:24px;color:red"></i>
                                </a>
                            @else
                                Yok
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="showVizyonMisyonModal(`{{ $topluluk->vizyon ?? 'Yok' }}`, `{{ $topluluk->misyon ?? 'Yok' }}`)">Görüntüle</button>
                        </td>
                        <td>
                            <button class="btn btn-approve btn-sm" onclick="approveAll({{ $topluluk->id }})">Onayla</button>
                            <button class="btn btn-reject btn-sm" onclick="openRedModal({{ $topluluk->id }})">Reddet</button>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <h2>Sosyal Medya Denetim İşlemleri</h2>
        <div class="form-container">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center w-100" id="sosyalMedyaTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Topluluk ID</th>
                            <th>Topluluk Adı</th>
                            <th>Logo</th>
                            <th>Instagram</th>
                            <th>WhatsApp</th>
                            <th>LinkedIn</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="sosyalMedyaBody">
                        <!-- JS ile doldurulacak -->
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

<div class="modal fade" id="vizyonMisyonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vizyon & Misyon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="vizyonMisyonContent"></div>
        </div>
    </div>
</div>

<div id="redModal" style="display:none; position:fixed; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px; border-radius:8px; min-width:300px; max-width:90vw;">
        <h4 id="redModalTitle">Red Sebebi</h4>
        <form id="redForm">
            <div id="redCheckboxes">
                <label><input type="checkbox" name="redTypes" value="logo"> Logo</label><br>
                <label><input type="checkbox" name="redTypes" value="bg"> Arkaplan</label><br>
                <label><input type="checkbox" name="redTypes" value="slogan"> Slogan</label><br>
                <label><input type="checkbox" name="redTypes" value="vizyon"> Vizyon</label><br>
                <label><input type="checkbox" name="redTypes" value="misyon"> Misyon</label><br>
                <label><input type="checkbox" name="redTypes" value="tuzuk"> Tüzük</label><br>
            </div>
            <input type="hidden" id="red_topluluk_id" name="topluluk_id">
            <textarea id="red_aciklama" name="aciklama" placeholder="Red sebebini giriniz" style="width:100%; margin:10px 0; height:100px;"></textarea>
            <button type="button" id="redGonderBtn" disabled>Gönder</button>
            <button type="button" onclick="closeRedModal()">İptal</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/denetim_etkinlik.js') }}"></script>
<script src="{{ asset('js/denetim_panel_menu.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#textModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Tetikleyen buton
            var text = button.data('text') // Butondan vizyon ve misyon metnini al
            var modalBodyInput = $(this).find('#modalTextContent')
            modalBodyInput.html('<p>' + text.replace(/\n/g, '<br>') + '</p>'); // Metni modal içeriğine ekle ve satır sonlarını <br> ile değiştir
        });
    });

    let selectedToplulukId = null;
    function openRedModal(toplulukId) {
        selectedToplulukId = toplulukId;
        document.getElementById('red_topluluk_id').value = toplulukId;
        document.getElementById('redModal').style.display = 'flex';
    }
    
    function closeRedModal() {
        document.getElementById('redModal').style.display = 'none';
        // Reset form
        document.getElementById('redForm').reset();
        document.getElementById('redGonderBtn').disabled = true;
    }
    
    // Enable/disable submit button based on form validation
    document.addEventListener('DOMContentLoaded', function() {
        const redForm = document.getElementById('redForm');
        const redGonderBtn = document.getElementById('redGonderBtn');
        
        redForm.addEventListener('change', function() {
            const checked = redForm.querySelectorAll('input[type=checkbox]:checked').length > 0;
            const aciklama = document.getElementById('red_aciklama').value.trim().length > 0;
            redGonderBtn.disabled = !(checked && aciklama);
        });
        
        document.getElementById('red_aciklama').addEventListener('input', function() {
            const checked = redForm.querySelectorAll('input[type=checkbox]:checked').length > 0;
            const aciklama = this.value.trim().length > 0;
            redGonderBtn.disabled = !(checked && aciklama);
        });
        
        redGonderBtn.addEventListener('click', function() {
            const fields = [];
            redForm.querySelectorAll('input[type=checkbox]:checked').forEach(function(checkbox) {
                fields.push(checkbox.value);
            });
            const aciklama = document.getElementById('red_aciklama').value;
            const toplulukId = document.getElementById('red_topluluk_id').value;
            
            $.post('/denetim/panel/reddet', {
                _token: '{{ csrf_token() }}',
                topluluk_id: toplulukId,
                fields: fields,
                aciklama: aciklama
            }, function(resp) {
                alert('Red işlemi kaydedildi!');
                closeRedModal();
                location.reload();
            });
        });
    });

    function approveAll(toplulukId) {
        $.post('/denetim/panel/onayla', {
            _token: '{{ csrf_token() }}',
            topluluk_id: toplulukId
        }, function(resp) {
            alert('Tüm bilgiler onaylandı!');
            location.reload();
        });
    }

    function showVizyonMisyonModal(vizyon, misyon) {
        let html = `<strong>Vizyon:</strong> ${vizyon}<br><strong>Misyon:</strong> ${misyon}`;
        document.getElementById('vizyonMisyonContent').innerHTML = html;
        var myModal = new bootstrap.Modal(document.getElementById('vizyonMisyonModal'));
        myModal.show();
    }

    function openImageModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
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

    // Sosyal medya verilerini çek ve tabloya doldur
    fetch('/denetim/sosyal-medya-listesi')
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById('sosyalMedyaBody');
            tbody.innerHTML = '';
            data.forEach(row => {
                // Sadece en az bir sosyal medya linki beklemede ise göster
                const igBeklemede = row.i_onay == 2;
                const wpBeklemede = row.w_onay == 2;
                const lnBeklemede = row.l_onay == 2;
                if (!igBeklemede && !wpBeklemede && !lnBeklemede) return; // Hepsi onaylı veya reddedilmişse gösterme
                tbody.innerHTML += `
                    <tr>
                        <td>${row.t_id}</td>
                        <td>${row.topluluk_adi}</td>
                        <td><img src="/images/logo/${row.logo}" style="max-width:40px;"></td>
                        <td>
                            ${row.instagram ? `<button class='btn btn-sm' style='background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%); border: none; color: white;' onclick="window.open('${row.instagram}','_blank')">Sayfaya Git</button>` : '-'}
                            ${igBeklemede ? '' : (row.i_onay == 1 ? '<span style=\'color:green\'>Onaylandı</span>' : row.i_onay == 0 ? '<span style=\'color:red\'>Reddedildi</span>' : '')}
                        </td>
                        <td>
                            ${row.whatsapp ? `<button class='btn btn-success btn-sm' onclick="window.open('${row.whatsapp}','_blank')">Grubu Görüntüle</button>` : '-'}
                            ${wpBeklemede ? '' : (row.w_onay == 1 ? '<span style=\'color:green\'>Onaylandı</span>' : row.w_onay == 0 ? '<span style=\'color:red\'>Reddedildi</span>' : '')}
                        </td>
                        <td>
                            ${row.linkedln ? `<button class='btn btn-info btn-sm' onclick="window.open('${row.linkedln}','_blank')">Sayfaya Git</button>` : '-'}
                            ${lnBeklemede ? '' : (row.l_onay == 1 ? '<span style=\'color:green\'>Onaylandı</span>' : row.l_onay == 0 ? '<span style=\'color:red\'>Reddedildi</span>' : '')}
                        </td>
                        <td>
                            ${(igBeklemede || wpBeklemede || lnBeklemede) ? `<button class='btn btn-approve btn-sm' onclick='onaylaHepsi(${JSON.stringify(row)})'>Onayla</button> <button class='btn btn-reject btn-sm' onclick='acRedModal(${JSON.stringify(row)})'>Reddet</button>` : '<span style=\"color:#888\">İşlem Yok</span>'}
                        </td>
                    </tr>
                `;
            });
        });

    function acRedModal(row) {
        let html = '<form id="redFormCheck">';
        if(row.i_onay == 2) html += '<label><input type="checkbox" name="redTypes" value="instagram"> Instagram</label><br>';
        if(row.w_onay == 2) html += '<label><input type="checkbox" name="redTypes" value="whatsapp"> WhatsApp</label><br>';
        if(row.l_onay == 2) html += '<label><input type="checkbox" name="redTypes" value="linkedln"> LinkedIn</label><br>';
        html += '<input type="hidden" id="redTId" value="'+row.t_id+'">';
        html += '<input type="text" id="redReasonInput" placeholder="Red sebebini giriniz" style="width:100%; margin:10px 0;">';
        html += '<button type="button" id="redSubmitBtn">Gönder</button>';
        html += '<button type="button" onclick="document.getElementById(\'redModal\').style.display=\'none\'">İptal</button>';
        html += '</form>';
        document.getElementById('redModal').style.display = 'flex';
        document.getElementById('redModal').innerHTML = '<div class="red-modal-content">'+html+'</div>';
        document.getElementById('redSubmitBtn').onclick = function() {
            const t_id = document.getElementById('redTId').value;
            const sebep = document.getElementById('redReasonInput').value.trim();
            const checked = Array.from(document.querySelectorAll('#redFormCheck input[name=redTypes]:checked')).map(x=>x.value);
            const allTypes = [];
            if(row.i_onay == 2) allTypes.push('instagram');
            if(row.w_onay == 2) allTypes.push('whatsapp');
            if(row.l_onay == 2) allTypes.push('linkedln');
            if (!sebep || checked.length === 0) { alert('Red sebebi ve en az bir seçim zorunlu!'); return; }
            // Seçilenler için red (0), seçilmeyenler için onay (1)
            const promises = [];
            allTypes.forEach(type => {
                if (checked.includes(type)) {
                    promises.push(fetch('/denetim/sosyal-medya-reddet', {
                        method: 'POST',
                        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
                        body: JSON.stringify({ t_id, type, sebep })
                    }));
                } else {
                    promises.push(fetch('/denetim/sosyal-medya-onayla', {
                        method: 'POST',
                        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
                        body: JSON.stringify({ t_id, type })
                    }));
                }
            });
            Promise.all(promises).then(()=>{
                // Satırı tablodan kaldır
                document.getElementById('redModal').style.display = 'none';
                // Tabloyu yeniden yükle (sayfa yenilemeden)
                fetch('/denetim/sosyal-medya-listesi')
                    .then(r => r.json())
                    .then(data => {
                        const tbody = document.getElementById('sosyalMedyaBody');
                        tbody.innerHTML = '';
                        data.forEach(row => {
                            const igBeklemede = row.i_onay == 2;
                            const wpBeklemede = row.w_onay == 2;
                            const lnBeklemede = row.l_onay == 2;
                            if (!igBeklemede && !wpBeklemede && !lnBeklemede) return;
                            tbody.innerHTML += `
                                <tr>
                                    <td>${row.t_id}</td>
                                    <td>${row.topluluk_adi}</td>
                                    <td><img src="/images/logo/${row.logo}" style="max-width:40px;"></td>
                                    <td>
                                        ${row.instagram ? `<button class='btn btn-sm' style='background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%); border: none; color: white;' onclick="window.open('${row.instagram}','_blank')">Sayfaya Git</button>` : '-'}
                                        ${igBeklemede ? '' : (row.i_onay == 1 ? '<span style=\'color:green\'>Onaylandı</span>' : row.i_onay == 0 ? '<span style=\'color:red\'>Reddedildi</span>' : '')}
                                    </td>
                                    <td>
                                        ${row.whatsapp ? `<button class='btn btn-success btn-sm' onclick=\"window.open('${row.whatsapp}','_blank')\">Grubu Görüntüle</button>` : '-'}
                                        ${wpBeklemede ? '' : (row.w_onay == 1 ? '<span style=\'color:green\'>Onaylandı</span>' : row.w_onay == 0 ? '<span style=\'color:red\'>Reddedildi</span>' : '')}
                                    </td>
                                    <td>
                                        ${row.linkedln ? `<button class='btn btn-info btn-sm' onclick=\"window.open('${row.linkedln}','_blank')\">Sayfaya Git</button>` : '-'}
                                        ${lnBeklemede ? '' : (row.l_onay == 1 ? '<span style=\'color:green\'>Onaylandı</span>' : row.l_onay == 0 ? '<span style=\'color:red\'>Reddedildi</span>' : '')}
                                    </td>
                                    <td>
                                        ${(igBeklemede || wpBeklemede || lnBeklemede) ? `<button class='btn btn-approve btn-sm' onclick='onaylaHepsi(${JSON.stringify(row)})'>Onayla</button> <button class='btn btn-reject btn-sm' onclick='acRedModal(${JSON.stringify(row)})'>Reddet</button>` : '<span style=\"color:#888\">İşlem Yok</span>'}
                                    </td>
                                </tr>
                            `;
                        });
                    });
            });
        }
    }

    function onaylaHepsi(row) {
        const t_id = row.t_id;
        const bekleyenler = [];
        if(row.i_onay == 2) bekleyenler.push('instagram');
        if(row.w_onay == 2) bekleyenler.push('whatsapp');
        if(row.l_onay == 2) bekleyenler.push('linkedln');
        if(bekleyenler.length === 0) return;
        Promise.all(bekleyenler.map(type => fetch('/denetim/sosyal-medya-onayla', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
            body: JSON.stringify({ t_id, type })
        }))).then(()=>{
            alert('Tüm hesaplar onaylanmıştır!');
            location.reload();
        });
    }
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
            <h3>Eposta</h3>
            <p>topluluk@erbakan.edu.tr</p>
        </div>
    </div>
    <div class="footer-bottom">
        © 2022 Necmettin Erbakan Üniversitesi
    </div>
</footer>
</body>

</html>

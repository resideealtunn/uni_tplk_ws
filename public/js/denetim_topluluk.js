document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.community-card');
    const modalElement = document.getElementById('communityModal');
    const modalContent = modalElement.querySelector('.modal-content');
    const modal = new bootstrap.Modal(modalElement);
    const goToBtn = document.getElementById('goToCommunityPage');
    let selectedId = null;
    let isPassive = false;
    let silinmeSebebi = '';

    // Pasife Al butonunu oluştur
    let pasifeAlBtn = document.getElementById('pasifeAlCommunity');
    if (!pasifeAlBtn) {
        pasifeAlBtn = document.createElement('button');
        pasifeAlBtn.id = 'pasifeAlCommunity';
        pasifeAlBtn.className = 'btn btn-danger';
        pasifeAlBtn.textContent = 'Pasife Al';
        pasifeAlBtn.style.marginRight = '8px';
        let footer = modalElement.querySelector('.modal-footer');
        footer.insertBefore(pasifeAlBtn, goToBtn);
    }
    pasifeAlBtn.style.display = 'none';

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const name = card.getAttribute('data-community-name');
            const id = card.getAttribute('data-community-id');
            const slogan = card.getAttribute('data-community-slogan');
            selectedId = id;
            const durum = card.getAttribute('data-durum');
            isPassive = durum === '2';
            const footer = modalElement.querySelector('.modal-footer');
            // Footer'ı temizle
            footer.innerHTML = '';
            if (isPassive) {
                // Pasif topluluk: sadece Aktife Al butonu
                fetch(`/denetim/topluluk-silinme-sebebi/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        silinmeSebebi = data.sebep || '-';
                        document.getElementById('communityTitle').textContent = name;
                        document.getElementById('communityDescription').innerHTML = `<b>Silinme Sebebi:</b> ${silinmeSebebi}`;
                        // Aktife Al butonu
                        let aktifBtn = document.createElement('button');
                        aktifBtn.id = 'aktifCommunity';
                        aktifBtn.className = 'btn btn-success';
                        aktifBtn.textContent = 'Aktife Al';
                        footer.appendChild(aktifBtn);
                        aktifBtn.onclick = function () {
                            Swal.fire({
                                title: 'Aktife almak istediğinize emin misiniz?',
                                text: 'Topluluk tekrar aktif olacak.',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Evet, aktife al',
                                cancelButtonText: 'İptal',
                                confirmButtonColor: '#28a745',
                                cancelButtonColor: '#3085d6',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    fetch('/denetim/topluluk-aktife-al', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: JSON.stringify({ id: selectedId })
                                    })
                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.success) {
                                                Swal.fire('Başarılı', 'Topluluk aktife alındı.', 'success').then(() => location.reload());
                                            } else {
                                                Swal.fire('Hata', data.message, 'error');
                                            }
                                        });
                                }
                            });
                        };
                        modalContent.style.background = '';
                        modal.show();
                    });
            } else {
                // Aktif topluluk: Pasife Al ve Sayfaya Git butonları
                document.getElementById('communityTitle').textContent = name;
                document.getElementById('communityDescription').innerHTML = `<b>Slogan:</b> ${slogan}`;
                // Pasife Al butonu
                let pasifeAlBtn = document.createElement('button');
                pasifeAlBtn.id = 'pasifeAlCommunity';
                pasifeAlBtn.className = 'btn btn-danger';
                pasifeAlBtn.textContent = 'Pasife Al';
                pasifeAlBtn.style.marginRight = '8px';
                footer.appendChild(pasifeAlBtn);
                // Sayfaya Git butonu
                let goToBtn = document.createElement('a');
                goToBtn.id = 'goToCommunityPage';
                goToBtn.className = 'btn btn-primary';
                goToBtn.textContent = 'Sayfaya Git';
                goToBtn.href = `/topluluklar/${name}/${id}`;
                footer.appendChild(goToBtn);
                pasifeAlBtn.onclick = function () {
                    modal.hide();
                    setTimeout(() => {
                        Swal.fire({
                            title: 'Pasife almak istediğinize emin misiniz?',
                            text: 'Bu topluluk pasif topluluklara düşecek ve görünmeyecek.',
                            icon: 'warning',
                            input: 'text',
                            inputLabel: 'Silinme Sebebi',
                            inputPlaceholder: 'Silinme sebebini giriniz',
                            inputAttributes: {
                                autocapitalize: 'off',
                                autocomplete: 'off',
                                spellcheck: 'true',
                                maxlength: 255
                            },
                            allowOutsideClick: false,
                            allowEscapeKey: true,
                            inputValidator: (value) => {
                                if (!value) return 'Silinme sebebi girilmelidir!';
                            },
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Evet, pasife al!',
                            cancelButtonText: 'İptal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch('/denetim/topluluk-sil', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({ id: selectedId, sebep: result.value })
                                })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            Swal.fire('Pasife Alındı!', 'Topluluk başarıyla pasife alındı.', 'success').then(() => {
                                                location.reload();
                                            });
                                        } else {
                                            Swal.fire('Hata', data.message, 'error');
                                        }
                                    });
                            }
                        });
                    }, 300);
                };
                modalContent.style.background = '';
                modal.show();
            }
        });
    });
});

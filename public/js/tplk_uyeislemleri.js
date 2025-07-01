document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const studentNumber = document.getElementById('student_number').value;
            const password = document.getElementById('password').value;
            const membershipForm = document.getElementById('membership_form').files[0];

            if (!studentNumber) {
                alert('Lütfen öğrenci numaranızı giriniz.');
                e.preventDefault();
                return;
            }

            if (!password) {
                alert('Lütfen tek şifrenizi giriniz.');
                e.preventDefault();
                return;
            }

            if (password.length < 6) {
                alert('Şifreniz en az 6 karakter olmalıdır.');
                e.preventDefault();
                return;
            }

            if (!membershipForm) {
                alert('Lütfen topluluk üyelik formunu yükleyiniz.');
                e.preventDefault();
                return;
            }

            if (membershipForm.type !== 'application/pdf') {
                alert('Lütfen sadece PDF formatında dosya yükleyiniz.');
                e.preventDefault();
                return;
            }
        });
    }

    // Yoklama Gir: Etkinlikleri doldur
    const yoklamaEtkinlik = document.getElementById('yoklamaEtkinlik');
    if (yoklamaEtkinlik) {
        const toplulukId = window.toplulukId || (typeof topluluk !== 'undefined' ? topluluk.id : null);
        fetch(`/yoklama-etkinlikler?t_id=${toplulukId}`)
            .then(res => res.json())
            .then(data => {
                yoklamaEtkinlik.innerHTML = '<option value="">Bir etkinlik seçiniz</option>';
                data.forEach(ev => {
                    yoklamaEtkinlik.innerHTML += `<option value="${ev.id}">${ev.isim}</option>`;
                });
            })
            .catch(() => {
                alert('Etkinlikler yüklenemedi!');
            });
    }

    // Mini başvuru modalı elemanlarını güvenli şekilde tanımla
    const miniApplyForm = document.getElementById('miniApplyForm');
    const miniModal = document.getElementById('miniModal');
    const miniClose = document.getElementById('miniClose');

    // Mini başvuru form submit
    if (miniApplyForm) {
        miniApplyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const tckn = document.getElementById('minitckNo')?.value;
            const sifre = document.getElementById('minitckPass')?.value;
            const e_id = document.getElementById('minieId')?.value;
            const t_id = document.getElementById('minitId')?.value;
            if (!tckn || !sifre || !e_id || !t_id) {
                alert('Lütfen tüm alanları doldurun.');
                return;
            }
            fetch('/etkinlik-basvuru', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tckn, sifre, e_id, t_id })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    miniModal.style.display = 'none';
                    miniApplyForm.reset();
                }
            })
            .catch(() => {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            });
        });
    }

    // Yoklama Gir: Form submit
    const yoklamaForm = document.getElementById('yoklamaForm');
    if (yoklamaForm) {
        yoklamaForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const tcElem = document.getElementById('yoklamaTc');
            const sifreElem = document.getElementById('yoklamaSifre');
            const etkinlikElem = document.getElementById('yoklamaEtkinlik');
            const katildimElem = document.getElementById('katildimCheck');
            if (!tcElem || !sifreElem || !etkinlikElem || !katildimElem) {
                alert('Form elemanları bulunamadı!');
                return;
            }
            const tc = tcElem.value;
            const sifre = sifreElem.value;
            const e_id = etkinlikElem.value;
            const katildim = katildimElem.checked ? 1 : 0;
            if (!tc || !sifre || !e_id) {
                alert('Lütfen tüm alanları doldurun.');
                return;
            }
            console.log('Yoklama formu gönderiliyor:', { tc, sifre, e_id, katildim });
            fetch('/yoklama-kaydet', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tc, sifre, e_id, katildim })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    yoklamaForm.reset();
                }
            })
            .catch(() => {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            });
        });
    }

    // Etkinliğe Başvur: Etkinlikleri doldur
    const etkinlikSelect = document.getElementById('etkinlik');
    if (etkinlikSelect) {
        const toplulukId = window.toplulukId;
        if (toplulukId) {
            fetch(`/etkinlikler/${toplulukId}/aktif`)
                .then(res => res.json())
                .then(data => {
                    etkinlikSelect.innerHTML = '<option value="">Bir etkinlik seçiniz</option>';
                    data.forEach(ev => {
                        etkinlikSelect.innerHTML += `<option value="${ev.id}">${ev.isim}</option>`;
                    });
                })
                .catch(() => {
                    alert('Etkinlikler yüklenemedi!');
                });
        } else {
            etkinlikSelect.innerHTML = '<option value="">Topluluk ID bulunamadı</option>';
        }
    }

    // Etkinliğe Başvur: Form submit
    const etkinlikForm = document.querySelector('.etkinlik-form');
    if (etkinlikForm) {
        etkinlikForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const tc = document.getElementById('etk_student_number').value;
            const sifre = document.getElementById('etk_password').value;
            const etkinlikId = document.getElementById('etkinlik').value;
            const toplulukId = window.toplulukId;
            if (!tc || !sifre || !etkinlikId) {
                alert('Lütfen tüm alanları doldurun.');
                return;
            }
            fetch('/etkinlik-basvuru', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tckn: tc, sifre: sifre, e_id: etkinlikId, t_id: toplulukId })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    etkinlikForm.reset();
                }
            })
            .catch(() => {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            });
        });
    }

    // Yoklama Durumu Görüntüle: Form submit
    const yoklamaGoruntuleForm = document.querySelector('.yoklama-form');
    if (yoklamaGoruntuleForm) {
        yoklamaGoruntuleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const tc = document.getElementById('yoklama_student_number').value;
            const sifre = document.getElementById('yoklama_password').value;
            fetch('/yoklama-durumu-goruntule', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tc, sifre })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message);
                    return;
                }
                if (!data.sonuclar || data.sonuclar.length === 0) {
                    document.getElementById('yoklamaSonucBody').innerHTML = '';
                    document.querySelector('.yoklama-katilim .badge').innerText = 'Katılım: 0/0';
                    document.getElementById('yoklamaSonuc').style.display = 'block';
                    return;
                }
                // Sadece ilk topluluk için göster
                const topluluk = data.sonuclar[0];
                let html = '';
                topluluk.detaylar.forEach(detay => {
                    html += `<tr><td>${detay.etkinlik}</td><td><span class="badge ${detay.katildi ? 'bg-success' : 'bg-danger'}">${detay.katildi ? 'Katıldı' : 'Katılmadı'}</span></td></tr>`;
                });
                document.getElementById('yoklamaSonucBody').innerHTML = html;
                document.querySelector('.yoklama-katilim .badge').innerText = `Katılım: ${topluluk.katildigi}/${topluluk.toplam_etkinlik}`;
                document.getElementById('yoklamaSonuc').style.display = 'block';
            })
            .catch(() => alert('Bir hata oluştu.'));
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.community-card');
    const modalElement = document.getElementById('communityModal');
    const modalContent = modalElement.querySelector('.modal-content');
    const modal = new bootstrap.Modal(modalElement);

    const deleteBtn = document.getElementById('deleteCommunity');
    let selectedId = null;

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const name = card.getAttribute('data-community-name');
            const id = card.getAttribute('data-community-id');
            const gorsel = card.getAttribute('data-community-gorsel');
            const slogan = card.getAttribute('data-community-slogan');

            document.getElementById('communityTitle').textContent = name;
            document.getElementById('communityDescription').textContent = `Slogan : ${slogan}`;
            document.getElementById('goToCommunityPage').href = `/topluluklar/${name}/${id}`;

            selectedId = id;

            modalContent.style.backgroundImage = `url('${gorsel}')`;
            modalContent.style.backgroundSize = 'cover';
            modalContent.style.backgroundRepeat = 'no-repeat';
            modalContent.style.backgroundPosition = 'center';
            modalContent.style.backgroundColor = 'rgba(255,255,255,1)';

            modal.show();
        });
    });

    deleteBtn.addEventListener('click', function () {
        if (!selectedId) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu Topluluk Pasif Topluluklara Düşecek ve Görünmeyecek",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/denetim/topluluk-sil', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ id: selectedId })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Silindi!',
                                'Topluluk başarıyla pasife alındı.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Hata', data.message, 'error');
                        }
                    });
            }
        });
    });
});

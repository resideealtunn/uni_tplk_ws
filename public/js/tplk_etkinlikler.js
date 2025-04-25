document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const studentNumber = document.getElementById('student_number').value;
            const membershipForm = document.getElementById('membership_form').files[0];

            if (!studentNumber) {
                alert('Lütfen öğrenci numaranızı giriniz.');
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
});

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
        });
    }
});

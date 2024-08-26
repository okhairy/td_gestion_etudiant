function redirectTo(url) {
    window.location.href = url;
}


  
// Fonction pour afficher/masquer le mot de passe
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️‍🗨️' : '👁️';
        });

        // Fonction pour afficher/masquer le mot de passe de confirmation
        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const confirmPasswordField = document.getElementById('confirm_password');
            const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️‍🗨️' : '👁️';
        });
    document.addEventListener('DOMContentLoaded', function () {
        const dateNaissanceInput = document.getElementById('date_naissance');
        const maxDate = new Date(2006, 11, 31); // Date maximale : 31 décembre 2006
        dateNaissanceInput.max = maxDate.toISOString().split('T')[0];
    });


 



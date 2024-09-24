document.addEventListener('DOMContentLoaded', function() {
    // Manejar el envío del formulario para seleccionar el tipo de usuario
    const selectForm = document.getElementById('select-register');
    if (selectForm) {
        selectForm.addEventListener('submit', function (e) {
            e.preventDefault();
            let userType = document.querySelector('input[name="userType"]:checked').value;
            
            if (userType === 'propietario') {
                window.location.href = '/registerOwner.php';
            } else if (userType === 'turista') {
                window.location.href = './view/registerTouristView.php';
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    
    document.getElementById('formCreate').addEventListener('submit', function (e) {
        e.preventDefault();

        const ownerName = document.getElementById('name').value.trim();
        const ownerSurnames = document.getElementById('surnames').value.trim();
        const ownerLegalIdentification = document.getElementById('legalIdentification').value.trim();
        const ownerPhone = document.getElementById('phone').value.trim();
        const ownerEmail = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('confirmPassword').value.trim();
        const imageFile = document.getElementById('imagen').files[0];

        // Validación básica
        if (ownerLegalIdentification === '') {
            alert('La identificación legal no puede estar vacía.');
            return;
        }
        
        if (ownerEmail === '') {
            alert('El correo no puede estar vacío.');
            return;
        }

        // Validación de contraseñas
        if (password === '' || confirmPassword === '') {
            alert('Las contraseñas no pueden estar vacías.');
            return;
        }

        if (password !== confirmPassword) {
            alert('Las contraseñas no coinciden.');
            return;
        }

        const formData = new FormData();
        formData.append('name', ownerName);
        formData.append('surnames', ownerSurnames);
        formData.append('legalIdentification', ownerLegalIdentification);
        formData.append('phone', ownerPhone);
        formData.append('email', ownerEmail);
        formData.append('direction', document.getElementById('direction').value.trim());
        formData.append('password', password);  // Agregar la contraseña
        formData.append('imagen', imageFile);
        formData.append('idType', document.getElementById('idType').value);
        formData.append('create', 'create');

        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/ownerAction.php', true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.status === 'success') {
                            alert(response.message);
                            document.getElementById('formCreate').reset();
                            location.reload();
                            redirectToCleanURL();

                        } else {
                            alert('Error: ' + response.message);
                        }
                    } else {
                        alert('HTTP Error: ' + xhr.status);
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', xhr.responseText);
                    alert('Error procesando la respuesta del servidor.');
                }
            }
        };

        xhr.send(formData);
    
    });
});

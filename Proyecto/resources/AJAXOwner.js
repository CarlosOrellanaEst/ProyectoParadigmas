document.addEventListener('DOMContentLoaded', function () {
    
    document.getElementById('ownerForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const ownerName = document.getElementById('name').value.trim();
        const ownerSurnames = document.getElementById('surnames').value.trim();
        const ownerLegalIdentification = document.getElementById('legalIdentification').value.trim();
        const ownerPhone = document.getElementById('phone').value.trim();
        const ownerEmail = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('confirmPassword').value.trim();
        const imageFile = document.getElementById('imagen').files[0];
        const idType = document.getElementById('idType').value;

        // Validación de campo obligatorio para el nombre
        if (ownerName === "") {
            alert('El campo "Nombre" es requerido.');
            return;
        }

        // Validación de que las contraseñas coinciden
        if (password !== confirmPassword) {
            alert('Las contraseñas no coinciden.');
            return;
        }

        const formData = new FormData();
        formData.append('ownerName', ownerName);
        formData.append('ownerSurnames', ownerSurnames);
        formData.append('ownerLegalIdentification', ownerLegalIdentification);
        formData.append('ownerPhone', ownerPhone);
        formData.append('ownerEmail', ownerEmail);
        formData.append('password', password);  
        formData.append('ownerDirection', document.getElementById('direction').value.trim());
        formData.append('imagen', imageFile);
        formData.append('idType', idType);
        formData.append('create', 'create');

        let xhr = new XMLHttpRequest();
        xhr.open('POST', './business/ownerAction.php', true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);  
                    if (xhr.status === 200 && response.status === 'success') {
                        alert(response.message);
                        // Redirigir al index después de éxito
                        window.location.href = './index.php'; // Cambia a la página que desees
                    } else {
                        handleErrorResponse(response);
                    }
                } catch (e) {
                    console.error('Error al procesar la respuesta JSON:', e);
                    alert('Error al procesar la respuesta del servidor.');
                }
            }
        };

        xhr.send(formData);
    });
});

function showAlertBasedOnURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const paramSuccess = urlParams.get('success');
    const paramError = urlParams.get('error');

    if (urlParams.has('success')) {
        if (paramSuccess && urlParams.get('success') === 'inserted') {
            alert('Se ha creado con éxito.');
        } 
    } 
}

// Función para manejar los errores devueltos por el servidor
function handleErrorResponse(response) {
    switch (response.error_code) {
        case 'invalid_name':
            alert('Error: El nombre contiene caracteres inválidos.');
            break;
        case 'invalid_surnames':
            alert('Error: Los apellidos contienen caracteres inválidos.');
            break;
        case 'invalid_costa_rica_id':
            alert('Error: La identificación de Costa Rica debe contener exactamente 9 dígitos.');
            break;
        case 'invalid_foreign_id':
            alert('Error: La identificación extranjera solo debe contener números.');
            break;
        case 'invalid_phone':
            alert('Error: El número de teléfono debe contener exactamente 8 dígitos.');
            break;
        case 'invalid_email':
            alert('Error: El formato del correo electrónico no es válido.');
            break;
        case 'invalid_file_type':
            alert('Error: El formato de la imagen no es válido.');
            break;
        case 'image_upload_failed':
            alert('Error: Fallo al subir la imagen.');
            break;
        case 'db_error':
            alert('Error: Fallo al agregar el propietario en la base de datos.');
            break;
        case 'missing_fields':
            alert('Error: Datos incompletos o inválidos.');
            break;
        case 'unknown_error':
            alert('Error: Ocurrió un error desconocido.');
            break;
        default:
            alert('Error desconocido: ' + response.message);
            break;
    }
}

function showAlertBasedOnURL() {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('success')) {
        switch (urlParams.get('success')) {
            case 'updated':
                alert('Se ha actualizado correctamente.');
                break;
            case 'deleted':
                alert('Se ha eliminado correctamente.');
                break;
            case 'inserted':
                alert('Se ha ingresado correctamente.');
                break;
            default:
                break;
        }
    } else if (urlParams.has('error')) {
        switch (urlParams.get('error')) {
            case 'dbError':
                alert('Error del sistema al realizar la acción.');
                break;
            case 'emptyField':
                alert('Debes ingresar un nombre, el campo de texto no debe estar vacío.');
                break;
            default:
                alert('Ocurrió un error.');
                break;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    showAlertBasedOnURL();
});


document.getElementById('formCreate').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevenir que la página se recargue

    // Validaciones
    const magicName = document.getElementById('magicName').value.trim();
    const legalName = document.getElementById('legalName').value.trim();
    const owner = document.getElementById('ownerId').value;
    const companyType = document.getElementById('companyType').value;
    const images = document.getElementById('imagenes').files;
    const status = document.getElementById('status').value;
    /*
    if (magicName === '') {
        alert('El nombre mágico no puede estar vacío.');
        return;
    }
    if (legalName === '') {
        alert('El nombre legal no puede estar vacío.');
        return;
    }
        */
    if (owner === '0') {
        alert('El propietario no puede ser "Ninguno".');
        return;
    }
    /*
    if (companyType === '0') {
        alert('El tipo de empresa no puede ser "Ninguno".');
        return;
    }
    */
    // Crear el objeto FormData
    const formData = new FormData();
    formData.append('magicName', magicName);
    formData.append('legalName', legalName);
    formData.append('ownerId', owner);
    formData.append('companyType', companyType);
    formData.append('status', status);

    // Añadir las imágenes seleccionadas
    for (let i = 0; i < images.length; i++) {
        formData.append('imagenes[]', images[i]);
    }
    
    formData.append('create', 'create'); // Asegúrate de que el campo de acción esté presente

    // Configuración de la solicitud AJAX
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '../business/touristCompanyAction.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                let response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    alert(response.message);
                    document.getElementById('formCreate').reset();
                    
                    // Redirigir después de la inserción exitosa
                    window.location.href = "touristCompanyView.php?success=inserted";
                } else {
                    alert('Error: ' + response.message);
                }
            } catch (e) {
                console.error('Respuesta JSON inválida:', xhr.responseText);
                alert('Error procesando la respuesta del servidor.');
            }
        } else if (xhr.readyState === 4) {
            alert('Error HTTP: ' + xhr.status);
        }
    };

    // Enviar los datos al servidor
    xhr.send(formData);
});
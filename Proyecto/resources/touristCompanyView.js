function showAlertBasedOnURL() {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('success')) {
        switch (urlParams.get('success')) {
            case 'updated':
                alert('La empresa se ha actualizado correctamente.');
                break;
            case 'deleted':
                alert('La empresa se ha eliminado correctamente.');
                break;
            case 'inserted':
                alert('La empresa se ha creado correctamente.');
                break;
            default:
                break;
        }
    } else if (urlParams.has('error')) {
        switch (urlParams.get('error')) {
            case 'uploadFailed':
                alert('Error al subir la imagen. Por favor, inténtelo de nuevo.');
                break;
            case 'invalidFileType':
                alert('Formato de imagen inválido. Solo se permiten JPG, PNG, JPEG y GIF.');
                break;
            case 'dbError':
                alert('Error en la base de datos al realizar la acción.');
                break;
            case 'emptyField':
                alert('El campo de texto no puede estar vacío.');
                break;
            case 'invalidOwnerOrCompanyType':
                alert('Propietario o tipo de empresa no válido. Por favor, revise los campos.');
                break;
            case 'invalidId':
                alert('ID inválido. No se pudo completar la acción.');
                break;
            case 'missingFields':
                alert('Faltan campos obligatorios. Por favor, complete todos los datos.');
                break;
            case 'deleteFailed':
                alert('Error al eliminar la empresa.');
                break;
            case 'updateFailed':
                alert('Error al actualizar la empresa.');
                break;
            default:
                alert('Ocurrió un error inesperado.');
                break;
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    showAlertBasedOnURL();
});

function confirmAction(event) {
    if (!confirm("¿Estás seguro de que quieres eliminar esta empresa?")) {
        event.preventDefault(); // Detener la acción si no se confirma
    }
}


document.getElementById('formCreate').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevenir recarga de página

    // Validaciones
    const magicName = document.getElementById('magicName').value.trim();
    const legalName = document.getElementById('legalName').value.trim();
    const owner = document.getElementById('ownerId').value;
    const companyType = document.getElementById('companyType').value;
    const images = document.getElementById('imagenes').files;
    const status = document.getElementById('status').value;
    const ownerError = document.getElementById('ownerError');
    // Validación específica de campos

    ownerError.style.display = 'none';

    // Validación
    if (owner === '0') { // Si el valor seleccionado es "Ninguno"
        ownerError.style.display = 'inline'; // Mostrar el mensaje de error
        return; // Detener el envío del formulario
    }
    
    if (owner === '0') {
        alert('Debes seleccionar un propietario.');
        return;
    }
 


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

    formData.append('create', 'create'); // Campo de acción

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

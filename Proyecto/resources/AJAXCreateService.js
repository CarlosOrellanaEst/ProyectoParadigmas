document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('formCreate').addEventListener('submit', function (event) {
        event.preventDefault();

        const companyID = document.getElementById('companyID').value.trim();
        const serviceIdInputs = document.querySelectorAll('select[name="serviceId"]'); // Capturar todos los select de servicios
        const servicesIDArray = Array.from(serviceIdInputs).map(input => input.value).join(','); // Obtener todos los IDs de servicios seleccionados
        const images = document.getElementById('imagenes').files;

        const formData = new FormData();
        formData.append('companyID', companyID);
        formData.append('serviceId', servicesIDArray);

        // Añadir las imágenes al FormData
        for (let i = 0; i < images.length; i++) {
            formData.append('imagenes[]', images[i]);
        }

        formData.append('create', 'create');

        // Enviar los datos con AJAX
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/serviceCompanyAction.php', true);
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        alert(response.message);
                        document.getElementById('formCreate').reset();
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                } catch (e) {
                    console.error('Error al procesar la respuesta del servidor', e);
                }
            } else {
                console.error('Error en la solicitud', xhr.statusText);
            }
        };
        xhr.send(formData);
    });

    // Obtener parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const error = urlParams.get('error');

    // Mostrar alertas basadas en los parámetros de la URL
    if (success) {
        switch (success) {
            case 'created':
                alert('Servicio agregado correctamente.');
                break;
            case 'updated':
                alert('Servicio actualizado correctamente.');
                break;
            case 'deleted':
                alert('Servicio eliminado correctamente.');
                break;
            case 'image_deleted':
                alert('Imagen eliminada correctamente.');
                break;
        }
    }

    if (error) {
        switch (error) {
            case 'image_error':
                alert('Error al mover la imagen al directorio.');
                break;
            case 'invalidImageType':
                alert('Formato de imagen inválido. Solo se permiten JPG, PNG, JPEG, y GIF.');
                break;
            case 'noImages':
                alert('No se han subido imágenes.');
                break;
            case 'emptyFields':
                alert('No se permiten campos vacíos.');
                break;
            case 'dbError':
                alert('Error en la base de datos.');
                break;
            case 'notFound':
                alert('Servicio no encontrado.');
                break;
            case 'alreadyExists':
                alert('El servicio ya existe.');
                break;
            case 'missingData':
                alert('Faltan datos en el formulario.');
                break;
            case 'invalidInput':
                alert('Datos inválidos. Asegúrate de que todos los campos sean numéricos.');
                break;
            case 'emptyField':
                alert('El ID del servicio está vacío.');
                break;
            case 'image_not_found':
                alert('Imagen no encontrada.');
                break;
            default:
                alert('Ocurrió un error desconocido.');
                break;
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Función para mostrar alertas
    function showAlert(message) {
        alert(message); // Puedes reemplazar 'alert()' con cualquier otra implementación de alerta si lo deseas
    }

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
                        showAlert(response.message);
                        document.getElementById('formCreate').reset();
                        location.reload();
                    } else {
                        showAlert('Error: ' + response.message);
                    }
                } catch (e) {
                    console.error('Error al procesar la respuesta del servidor', e);
                    showAlert('Error al procesar la respuesta del servidor.');
                }
            } else {
                console.error('Error en la solicitud', xhr.statusText);
                showAlert('Error en la solicitud: ' + xhr.statusText);
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
                showAlert('Servicio agregado correctamente.');
                break;
            case 'updated':
                showAlert('Servicio actualizado correctamente.');
                break;
            case 'deleted':
                showAlert('Servicio eliminado correctamente.');
                break;
            case 'image_deleted':
                showAlert('Imagen eliminada correctamente.');
                break;
        }
    }

    if (error) {
        switch (error) {
            case 'image_error':
                showAlert('Error al mover la imagen al directorio.');
                break;
            case 'invalidImageType':
                showAlert('Formato de imagen inválido. Solo se permiten JPG, PNG, JPEG, y GIF.');
                break;
            case 'noImages':
                showAlert('No se han subido imágenes.');
                break;
            case 'emptyFields':
                showAlert('No se permiten campos vacíos.');
                break;
            case 'dbError':
                showAlert('Error en la base de datos.');
                break;
            case 'notFound':
                showAlert('Servicio no encontrado.');
                break;
            case 'alreadyExists':
                showAlert('El servicio ya existe.');
                break;
            case 'duplicateCompanyID':
                showAlert('El ID de la compañía ya existe. Por favor, ingresa un ID diferente.');
                break;
            case 'missingData':
                showAlert('Faltan datos en el formulario.');
                break;
            case 'invalidInput':
                showAlert('Datos inválidos. Asegúrate de que todos los campos sean numéricos.');
                break;
            case 'emptyField':
                showAlert('El ID del servicio está vacío.');
                break;
            case 'image_not_found':
                showAlert('Imagen no encontrada.');
                break;
            default:
                showAlert('Ocurrió un error desconocido.');
                break;
        }
    }
    
    $(document).ready(function () {
        // Comprobar si la compañía seleccionada tiene servicios activos
        $('#companyID').change(function () {
            const companyID = $(this).val();
            
            $.ajax({
                url: '../business/serviceCompanyAction.php',
                type: 'POST',
                data: { companyID: companyID },
                success: function (response) {
                    if (response === '1') {
                        $('#create').hide();
                        alert("Esta empresa ya tiene servicios activos.");
                    } else {
                        $('#create').show();
                    }
                }
            });
        });
    });
   
});

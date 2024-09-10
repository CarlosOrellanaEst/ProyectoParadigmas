document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('formCreate').addEventListener('submit', function (event) {
        event.preventDefault();

        const companyID = document.getElementById('companyID').value.trim();
        const serviceIdInputs = document.querySelectorAll('select[name="serviceId[]"]'); // Capturar todos los select de servicios
        const servicesIDArray = Array.from(serviceIdInputs).map(input => input.value); // Obtener todos los IDs de servicios seleccionados
        const images = document.getElementById('imagenes').files;

        const formData = new FormData();
        formData.append('companyID', companyID);

        // Añadir cada ID de servicio al FormData
        servicesIDArray.forEach(id => formData.append('serviceId[]', id));

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
});

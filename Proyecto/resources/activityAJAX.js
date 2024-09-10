document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('formCreate').addEventListener('submit', function (event) {
        event.preventDefault();

        const nameTBActivity = document.getElementById('nameTBActivity').value;

        const serviceID = document.getElementById('serviceId1').value;

        // Obtener los atributos y datos
        const attributeInputs = document.querySelectorAll('input[name="attributeTBActivityArray"]');
        const dataInputs = document.querySelectorAll('input[name="dataAttributeTBActivityArray"]');

        // Convertir los atributos y datos en cadenas separadas por comas
        const attributeTBActivityArray = Array.from(attributeInputs).map(input => input.value).join(',');
        const dataAttributeTBActivityArray = Array.from(dataInputs).map(input => input.value).join(',');

        // Obtener las imágenes
        const images = document.getElementById('imagenes').files;

        // Crear el objeto FormData para enviar con AJAX
        const formData = new FormData();

        formData.append('nameTBActivity', nameTBActivity);
        formData.append('serviceId', serviceID);
        formData.append('attributeTBActivityArray', attributeTBActivityArray);
        formData.append('dataAttributeTBActivityArray', dataAttributeTBActivityArray);

        // Añadir las imágenes al FormData
        for (let i = 0; i < images.length; i++) {
            formData.append('imagenes[]', images[i]);
        }

        formData.append('create', 'create');

        // Enviar la solicitud AJAX
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/activityAction.php', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.status === 'success') {
                            alert(response.message);
                            document.getElementById('formCreate').reset();
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } else {
                        alert('Error HTTP: ' + xhr.status);
                    }
                } catch (e) {
                    console.error('Respuesta JSON inválida:', xhr.responseText);
                    alert('Error procesando la respuesta del servidor.');
                }
            }
        };

        xhr.send(formData);
    });

});


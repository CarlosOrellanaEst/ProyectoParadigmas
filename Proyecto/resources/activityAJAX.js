document.addEventListener('DOMContentLoaded', function () {

    function handleErrorResponse(response) {
        alert('Error: ' + response.message);
    }

    // Manejador específico para el formulario de creación
    document.getElementById('formCreate').addEventListener('submit', function (event) {
        event.preventDefault();

        const nameTBActivity = document.getElementById('nameTBActivity').value;
        const serviceID = document.getElementById('serviceId1').value;

        let attributeInputs = document.querySelectorAll('input[name="attributeTBActivityArrayFORM"]');
        let dataInputs = document.querySelectorAll('input[name="dataAttributeTBActivityArrayFORM"]');

        const attributeTBActivityArray = Array.from(attributeInputs).map(input => input.value).join(',');
        const dataAttributeTBActivityArray = Array.from(dataInputs).map(input => input.value).join(',');

        const images = document.getElementById('imagenes').files;

        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;
        const activityDate = document.getElementById('activityDate').value.replace('T', ' ') + ':00';

        const formData = new FormData();
        formData.append('nameTBActivity', nameTBActivity);
        formData.append('serviceId', serviceID);
        formData.append('attributeTBActivityArray', attributeTBActivityArray);
        formData.append('dataAttributeTBActivityArray', dataAttributeTBActivityArray);  
        formData.append('latitude', latitude);
        formData.append('longitude', longitude);
        formData.append('activityDate', activityDate);

        for (let i = 0; i < images.length; i++) {
            formData.append('imagenes[]', images[i]);
        }

        formData.append('create', 'create');

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
                            handleErrorResponse(response);
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

    // Manejador para todos los formularios excepto el de creación
    const forms = document.querySelectorAll('form');
    forms.forEach(function (form) {
        if (form.id !== 'formCreate') {  // Excluye el formulario 'formCreate' del forEach
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const actionType = event.submitter.name; 
                const formData = new FormData(form);

                formData.delete('create');
                formData.delete('update');
                formData.delete('delete');
                formData.delete('deleteImage');

                if (actionType) {
                    formData.append(actionType, actionType);
                }

                let xhr = new XMLHttpRequest();
                xhr.open('POST', '../business/activityAction.php', true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        console.log('Raw response:', xhr.responseText);
                        if (xhr.status === 200) {
                            try {
                                let response = JSON.parse(xhr.responseText.trim());
                                if (response.status === 'success') {
                                    alert(response.message);
                                    location.reload(); 
                                } else {
                                    handleErrorResponse(response);
                                }
                            } catch (e) {
                                console.error('Respuesta JSON inválida:', xhr.responseText);
                                alert('Error procesando la respuesta del servidor. Respuesta no válida.');
                            }
                        } else {
                            alert('Error HTTP: ' + xhr.status);
                        }
                    }
                };
                xhr.send(formData);
            });
        }
    });
});

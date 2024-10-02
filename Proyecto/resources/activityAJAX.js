document.addEventListener('DOMContentLoaded', function () {

   
    document.getElementById('formCreate').addEventListener('submit', function (event) {
        event.preventDefault();

        const nameTBActivity = document.getElementById('nameTBActivity').value;
        const serviceID = document.getElementById('serviceId1').value;

        const attributeInputs = document.querySelectorAll('input[name="attributeTBActivityArray[]"]');
        const dataInputs = document.querySelectorAll('input[name="dataAttributeTBActivityArray[]"]');

        const attributeTBActivityArray = Array.from(attributeInputs).map(input => input.value).join(',');
        const dataAttributeTBActivityArray = Array.from(dataInputs).map(input => input.value).join(',');

        const images = document.getElementById('imagenes').files;

        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;
        const activityDate = document.getElementById('activityDate').value.replace('T', ' ') + ':00'; // Asegura el formato correcto



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

  
    const forms = document.querySelectorAll('form');
    forms.forEach(function (form) {


        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const actionType = event.submitter.name; 
            const formData = new FormData(form);

            if (actionType === 'update') {
                formData.append('update', 'update');
            } else if (actionType === 'delete') {
                formData.append('delete', 'delete');
            } else if (actionType === 'deleteImage') {
                formData.append('deleteImage', 'deleteImage');
            }

            let xhr = new XMLHttpRequest();
            xhr.open('POST', '../business/activityAction.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    try {
                        let response = JSON.parse(xhr.responseText);
                        if (xhr.status === 200) {
                            if (response.status === 'success') {
                                alert(response.message); 
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
});

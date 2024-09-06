document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('formCreate').addEventListener('submit', function (event) {
        event.preventDefault();

        // Obtener los atributos y datos
        const attributeInputs = document.querySelectorAll('input[name="attributeTBActivityArray"]');
        const dataInputs = document.querySelectorAll('input[name="dataAttributeTBActivityArray"]');

        // Convertir los atributos y datos en cadenas separadas por comas
        const attributeTBActivityArray = Array.from(attributeInputs).map(input => input.value).join(',');
        const dataAttributeTBActivityArray = Array.from(dataInputs).map(input => input.value).join(',');

        const formData = new FormData(this);
        formData.append('attributeTBActivityArray', attributeTBActivityArray);
        formData.append('dataAttributeTBActivityArray', dataAttributeTBActivityArray);
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



    // Manejo de eliminación y actualización
    document.querySelectorAll('form[action="../business/activityAction.php"]').forEach(function(form) {
        form.addEventListener('submit', function (event) {
            let action = event.submitter ? event.submitter.name : '';

            if (!confirmAction(event)) {
                event.preventDefault();
                return;
            }

            if (action === 'delete') {
                event.preventDefault();
                handleDeleteActivity(form);
            } else if (action === 'update') {
                event.preventDefault();
                handleUpdateActivity(form);
            }
        });
    });

    // Función para manejar la eliminación
    function handleDeleteActivity(form) {
        console.log('Enviando solicitud de eliminación');
        let idTBActivity = form.querySelector("input[name='idTBActivity']").value;

        let formData = new FormData();
        formData.append('idTBActivity', idTBActivity);
        formData.append('delete', true);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/activityAction.php', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                let response = xhr.responseText;

                if (response.includes('Activity deleted successfully')) {
                    alert('Actividad eliminada exitosamente.');
                    location.reload();
                } else {
                    alert('Error al eliminar la actividad: ' + response);
                }
            } else {
                alert('Error en la solicitud AJAX.');
            }
        };
        xhr.send(formData);
    }

    // Función para manejar la actualización
    function handleUpdateActivity(form) {
        console.log('Enviando solicitud de actualización');
        let idTBActivity = form.querySelector("input[name='idTBActivity']").value;
        let nameTBActivity = form.querySelector("input[name='nameTBActivity']").value;

        let attributeTBActivityArray = [];
        form.querySelectorAll("input[name='attributeTBActivityArray[]']").forEach(function(input) {
            attributeTBActivityArray.push(input.value);
        });

        let dataAttributeTBActivityArray = [];
        form.querySelectorAll("input[name='dataAttributeTBActivityArray[]']").forEach(function(input) {
            dataAttributeTBActivityArray.push(input.value);
        });

        let statusTBActivity = form.querySelector("input[name='statusTBActivity']").value;

        let formData = new FormData();
        formData.append('idTBActivity', idTBActivity);
        formData.append('nameTBActivity', nameTBActivity);
        formData.append('attributeTBActivityArray', JSON.stringify(attributeTBActivityArray));
        formData.append('dataAttributeTBActivityArray', JSON.stringify(dataAttributeTBActivityArray));
        formData.append('statusTBActivity', statusTBActivity);
        formData.append('update', true);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/activityAction.php', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                let response = xhr.responseText;

                if (response.includes('Activity updated successfully')) {
                    alert('Actividad actualizada exitosamente.');
                    location.reload();
                } else {
                    alert('Error al actualizar la actividad: ' + response);
                }
            } else {
                alert('Error en la solicitud AJAX.');
            }
        };
        xhr.send(formData);
    }
});

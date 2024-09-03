document.addEventListener('DOMContentLoaded', function () {
    // Manejo del formulario de creación
    document.getElementById('formCreate').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevenir el envío estándar del formulario

        let nameTBActivity = document.getElementById('nameTBActivity').value;

        // Recolectar los datos de los inputs y convertirlos en arrays
        let attributeTBActivityArray = [];
        document.querySelectorAll("input[name='attributeTBActivityArray[]']").forEach(function(input) {
            attributeTBActivityArray.push(input.value);
        });

        let dataAttributeTBActivityArray = [];
        document.querySelectorAll("input[name='dataAttributeTBActivityArray[]']").forEach(function(input) {
            dataAttributeTBActivityArray.push(input.value);
        });

        let statusTBActivity = document.getElementById('statusTBActivity').value;

        // Preparar el FormData para enviar al servidor
        let formData = new FormData();
        formData.append('nameTBActivity', nameTBActivity);
        formData.append('attributeTBActivityArray', JSON.stringify(attributeTBActivityArray));
        formData.append('dataAttributeTBActivityArray', JSON.stringify(dataAttributeTBActivityArray));
        formData.append('statusTBActivity', statusTBActivity);
        formData.append('create', true);

        // Enviar la solicitud AJAX
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/activityAction.php', true);
        
        xhr.onload = function () {
            console.log(xhr.responseText); // Verificar la respuesta del servidor
            if (xhr.status === 200) {
                let response = xhr.responseText;
                if (response.includes('Activity inserted successfully')) {
                    alert('Actividad insertada exitosamente.');
                    document.getElementById('formCreate').reset(); // Limpiar el formulario
                    document.getElementById('attributes').innerHTML = `
                        <div>
                            <label for="attribute1">Atributo: </label>
                            <input type="text" name="attributeTBActivityArray[]" id="attribute1" placeholder="Atributo" required />
                            <label for="dataAttributeTBActivityArray[]">Dato: </label>
                            <input type="text" name="dataAttributeTBActivityArray[]" placeholder="Dato" required />
                        </div>
                    `; // Limpiar los atributos agregados dinámicamente
                    location.reload(); // Recargar la página para actualizar la tabla
                } else {
                    alert('Error en la operación: ' + response);
                }
            } else {
                alert('Error en la solicitud AJAX: ' + xhr.status + ' - ' + xhr.statusText);
            }
        };
        
        xhr.onerror = function () {
            alert('Error en la solicitud AJAX. No se pudo completar la solicitud.');
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

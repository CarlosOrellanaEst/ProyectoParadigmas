document.addEventListener('DOMContentLoaded', function () {


    // Insertar actividad
    document.addEventListener('DOMContentLoaded', function () {
        // Insertar actividad
        document.getElementById('formCreate').addEventListener('submit', function (event) {
            event.preventDefault();
    
            let nameTBActivity = document.getElementById('nameTBActivity').value;
    
            // Recolectar los datos de los inputs
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
            formData.append('insert', true);
    
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '../business/activityAction.php', true);
    
            xhr.onload = function () {
                console.log(xhr.responseText); // Verifica lo que el servidor está enviando de vuelta
                if (xhr.status === 200) {
                    let response = xhr.responseText;
            
                    if (response.includes('Activity inserted successfully')) {
                        alert('Actividad insertada exitosamente.');
                        document.getElementById('formCreate').reset();
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
            
    
            xhr.onerror = function () {
                alert('Error en la solicitud AJAX. No se pudo completar la solicitud.');
            };
    
            xhr.send(formData);
        });
    });
    
    

    // Eliminar actividad
    document.querySelectorAll('form[action="../business/activityAction.php"]').forEach(function(form) {
        form.addEventListener('submit', function (event) {
            if (!confirmAction(event)) {
                event.preventDefault();
                return;
            }
    
            if (event.submitter && event.submitter.name === 'delete') {  // Añadí una verificación adicional para `event.submitter`
                event.preventDefault();
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
        });
    });
    
    // Editar actividad
    document.querySelectorAll('form[action="../business/activityAction.php"]').forEach(function(form) {
        form.addEventListener('submit', function (event) {
            if (!confirmAction(event)) {
                event.preventDefault();
                return;
            }

            if (event.submitter.name === 'update') {
                event.preventDefault();

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
    });
});
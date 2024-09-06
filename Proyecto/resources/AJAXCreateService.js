document.addEventListener('DOMContentLoaded', function () {
    //Create Roll
    document.getElementById('formCreate').addEventListener('submit', function (e) {
        e.preventDefault();
        // Validaciones
        const serviceName = document.getElementById('serviceName').value.trim();
        const images = document.getElementById('images').files;
    
        if (serviceName === '') {
            alert('Debe indicar un nombre del servicio');
            return;
        }
    
        // Datos a enviar   
        const formData = new FormData();
        formData.append('serviceName', serviceName);
/*         formData.append('images', images); */
        for (let i = 0; i < images.length; i++) {
            formData.append('images[]', images[i]);
        }
        formData.append('create', 'create');
        
        // revisando lo que se envia al servidor
/*         for (const value of formData.values()) {
            console.log(value);
        } */

        // Configuración AJAX
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/serviceAction.php', true);
    
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log(xhr.responseText);  // Verificar lo que devuelve el servidor
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.status === 'success') {
                            alert(response.message);
                            document.getElementById('formCreate').reset();
                            redirectToCleanURL();
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } else {
                        alert('HTTP Error: ' + xhr.status);
                    }
                } catch (e) {
                    console.error('Respuesta JSON inválida:', xhr.responseText);
                    alert('Error procesando la respuesta del servidor.');
                }
            }
        };
        xhr.send(formData);
    });
    
})

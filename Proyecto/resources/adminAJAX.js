document.addEventListener('DOMContentLoaded', function () {
    
    document.getElementById('formCreate').addEventListener('submit', function (e) {
        e.preventDefault();

        const touristName = document.getElementById('touristName').value.trim();
        const touristSurnames = document.getElementById('touristSurnames').value.trim();
        const touristLegalIdentification = document.getElementById('touristLegalIdentification').value.trim();
        const touristPhone = document.getElementById('touristPhone').value.trim();
        const touristEmail = document.getElementById('touristEmail').value.trim();
        const touristNickName = document.getElementById('touristNickName').value.trim();
        const touristPassword = document.getElementById('touristPassword').value.trim();
        const confirmTouristPassword = document.getElementById('confirmTouristPassword').value.trim();

        if (touristLegalIdentification === '') {
            alert('La identificación legal no puede estar vacía.');
            return;
        }
        
        if (touristEmail === '') {
            alert('El correo no puede estar vacío.');
            return;
        }

        if (touristNickName === '') {
            alert('El usuario no puede estar vacío.');
            return;
        }

        if (touristPassword === '') {
            alert('La contraseña no puede estar vacía.');
            return;
        }

        if (confirmTouristPassword === '') {
            alert('Confirmar contraseña no puede estar vacía.');
            return;
        }

        if (touristPassword !== confirmTouristPassword) {
            alert('Las contraseñas no coinciden.');
            return;
        }

        const formData = new FormData();
        formData.append('touristName', document.getElementById('touristName').value.trim());
        formData.append('touristSurnames', document.getElementById('touristSurnames').value.trim());
        formData.append('touristLegalIdentification', document.getElementById('touristLegalIdentification').value.trim());
        formData.append('touristPhone', document.getElementById('touristPhone').value.trim());
        formData.append('touristEmail', document.getElementById('touristEmail').value.trim());
        formData.append('touristNickName', document.getElementById('touristNickName').value.trim());
        formData.append('touristPassword', document.getElementById('touristPassword').value.trim());
        formData.append('confirmTouristPassword', document.getElementById('confirmTouristPassword').value.trim());
        formData.append('idType', document.getElementById('idType').value);
        formData.append('create', 'create');

       
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/adminAction.php', true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.status === 'success') {
                            alert(response.message);
                            document.getElementById('formCreate').reset();
                            location.reload();
                            redirectToCleanURL();

                        } else {
                            alert('Error: ' + response.message);
                        }
                    } else {
                        alert('HTTP Error: ' + xhr.status);
                    }
                } catch (e) {
                   // console.error('Invalid JSON response:', xhr.responseText);
                    alert('Error procesando la respuesta del servidor.');
                }
            }
        };

        xhr.send(formData);
    
    });
});

function redirectToCleanURL() {
    const cleanURL = window.location.origin + window.location.pathname;
    window.history.replaceState({}, document.title, cleanURL);
}

window.onload = function () {
    showAlertBasedOnURL();
    redirectToCleanURL();
};

function showAlertBasedOnURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const paramSuccess = urlParams.get('success');
    const paramError = urlParams.get('error');

    if (urlParams.has('success')) {
        if (paramSuccess && urlParams.get('success') === 'inserted') {
            alert('Se ha creado con éxito.');
        } 
    } else if(urlParams.has('error')) {
        if(paramError === 'invalidForeignId') { 
            alert('Error.\nEl formato de la cedula no es valida. \nFormato valido puede ser un número de 8 a 12 dígitos');
        
        }
    }
} 

window.onload = showAlertBasedOnURL;
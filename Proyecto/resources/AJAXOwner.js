
document.addEventListener('DOMContentLoaded', function () {
    
    document.getElementById('formCreate').addEventListener('submit', function (e) {
        e.preventDefault();

        const ownerName = document.getElementById('name').value.trim();
        const ownerSurnames = document.getElementById('surnames').value.trim();
        const ownerLegalIdentification = document.getElementById('legalIdentification').value.trim();
        const ownerPhone = document.getElementById('phone').value.trim();
        const ownerEmail = document.getElementById('email').value.trim();
        const imageFile = document.getElementById('imagen').files[0];

        if (ownerLegalIdentification === '') {
            alert('La identificación legal no puede estar vacía.');
            return;
        }
        
        if (ownerEmail === '') {
            alert('El correo no puede estar vacío.');
            return;
        }

        const formData = new FormData();
        formData.append('name', document.getElementById('name').value.trim());
        formData.append('surnames', document.getElementById('surnames').value.trim());
        formData.append('legalIdentification', document.getElementById('legalIdentification').value.trim());
        formData.append('phone', document.getElementById('phone').value.trim());
        formData.append('email', document.getElementById('email').value.trim());
        formData.append('direction', document.getElementById('direction').value.trim());
        formData.append('imagen', document.getElementById('imagen').files[0]);
        formData.append('idType', document.getElementById('idType').value);
        formData.append('create', 'create');

       
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/ownerAction.php', true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.status === 'success') {
                            alert(response.message);
                            document.getElementById('formCreate').reset();
                            window.location.href = '/index.php';  // Redirigir al index para que se loguee
                        }
                         else {
                            alert('Error: ' + response.message);
                        }
                    } else {
                        alert('HTTP Error: ' + xhr.status);
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', xhr.responseText);
                    alert('Error procesando la respuesta del servidor.');
                }
            }
        };

        xhr.send(formData);
    
    });
});

function confirmDelete(event) {
    if (event.submitter && event.submitter.name === 'delete') {
        return confirm('¿Estás seguro de que deseas eliminar este dueño?');
    }
    return true;
}

function showAlertBasedOnURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const paramSuccess = urlParams.get('success');
    const paramError = urlParams.get('error');

    if (urlParams.has('success')) {
        if(paramSuccess === 'updated') {
            alert('El dueño se ha actualizado con éxito.');
        } else if (paramSuccess && urlParams.get('success') === 'inserted') {
            alert('El dueño se ha creado con éxito.');
        } else if (paramSuccess && urlParams.get('success') === 'deleted') {
            alert('El dueño se ha eliminado con éxito.');
        }
    } else if(urlParams.has('error')) {
        if(paramError === 'alreadyexists') { 
            alert('Error.\nEl correo ya existe');
        
        }if (paramError === 'phonealreadyexists') {
            alert('Error.\nEl telefono ya existe');
        
        }if (paramError === 'invalidName') {
            alert('Error.\nEl nombre no puede contener números.');
        
        }if (paramError === 'invalidSurnames') {
            alert('Error.\nEl apellido no puede contener números.');
        
        }if (paramError === 'legalidalreadyexists') {
            alert('Error. La identificacion legal ya existe');
        
        }  if(paramError === 'invalidFileType') { 
            alert('Error.\nEl formato de la imagen no es valido');
        
        } if(paramError === 'uploadFailed') { 
            alert('Error.\nNo se subio la imagen');
        
        } if(paramError === 'invalidCostaRicaId') { 
            alert('Error.\nEl formato de la cedula no es valida. \nFormato valida 9 digitos: "123456789" Sin guiónes ni espacios');
        
        }if(paramError === 'invalidForeignId') { 
            alert('Error.\nEl formato de la cedula no es valida. \nFormato valido puede ser un número de 8 a 12 dígitos');
        
        }if(paramError === 'invalidPhone') { 
            alert('Error.\nEl formato del numero de telefono no es valido \nFormato valido 8 digitos: "12345678" Sin guiones ni espacios');
        
        }if(paramError === 'invalidEmailFormat') { 
            alert('Error.\nEl formato del Email no es valido \nNecesita llevar @ y minimo un número');
        
        }if(paramError === 'imageUploadFailed') { 
            alert('Error.\nError al subir la imagen');
        
        }
        if(paramError === 'numberFormat') { 
            alert('Error.\nNo se permiten números en el nombre, ni los apellidos');
        }else if(paramError === 'error'){
            alert('Error.\nPor favor notificar de este error');
        }
    }
} 

function redirectToCleanURL() {
    const cleanURL = window.location.origin + window.location.pathname;
    window.history.replaceState({}, document.title, cleanURL);
}

window.onload = function () {
    showAlertBasedOnURL();
    redirectToCleanURL();
};

window.onload = showAlertBasedOnURL;


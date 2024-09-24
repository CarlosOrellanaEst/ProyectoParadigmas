document.addEventListener('DOMContentLoaded', function () {
    
    document.getElementById('ownerForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const ownerName = document.getElementById('name').value.trim();
        const ownerSurnames = document.getElementById('surnames').value.trim();
        const ownerLegalIdentification = document.getElementById('legalIdentification').value.trim();
        const ownerPhone = document.getElementById('phone').value.trim();
        const ownerEmail = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('confirmPassword').value.trim();
        const imageFile = document.getElementById('imagen').files[0];
        const idType = document.getElementById('idType').value;

        // Validación de que las contraseñas coinciden
        if (password !== confirmPassword) {
            alert('Las contraseñas no coinciden.');
            return;
        }

        // Validación de identificación legal
        if (idType === 'CR' && !/^\d{9}$/.test(ownerLegalIdentification)) {
            alert('La identificación de Costa Rica debe contener exactamente 9 dígitos.');
            return;
        }

        if (idType === 'foreign' && !/^\d+$/.test(ownerLegalIdentification)) {
            alert('La identificación extranjera solo debe contener números.');
            return;
        }

        // Validación de teléfono
        if (!/^\d{8}$/.test(ownerPhone)) {
            alert('El número de teléfono debe contener exactamente 8 dígitos numéricos.');
            return;
        }

        // Validación de correo electrónico
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(ownerEmail)) {
            alert('El formato del correo electrónico no es válido.');
            return;
        }

        // Validaciones de campos vacíos
        if (ownerLegalIdentification === '') {
            alert('La identificación legal no puede estar vacía.');
            return;
        }

        if (ownerEmail === '') {
            alert('El correo no puede estar vacío.');
            return;
        }

        const formData = new FormData();
        formData.append('ownerName', ownerName);
        formData.append('ownerSurnames', ownerSurnames);
        formData.append('ownerLegalIdentification', ownerLegalIdentification);
        formData.append('ownerPhone', ownerPhone);
        formData.append('ownerEmail', ownerEmail);
        formData.append('password', password);  
        formData.append('ownerDirection', document.getElementById('direction').value.trim());
        formData.append('imagen', imageFile);
        formData.append('idType', idType);
        formData.append('create', 'create');

        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/ownerAction.php', true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);  
                    alert(response.message); 
                    if (xhr.status === 200 && response.status === 'success') {
                        document.getElementById('formCreate').reset();
                    }
                } catch (e) {
                    console.error('Error al parsear la respuesta JSON:', e);
                    alert('Error al procesar la respuesta del servidor.');
                }
            }
        };
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
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
        if (paramSuccess === 'updated') {
            alert('El dueño se ha actualizado con éxito.');
        } else if (paramSuccess && urlParams.get('success') === 'inserted') {
            alert('El dueño se ha creado con éxito.');
        } else if (paramSuccess && urlParams.get('success') === 'deleted') {
            alert('El dueño se ha eliminado con éxito.');
        }
    } else if (urlParams.has('error')) {
        if (paramError === 'alreadyexists') {
            alert('Error.\nEl correo ya existe');
        
        } else if (paramError === 'phonealreadyexists') {
            alert('Error.\nEl teléfono ya existe');
        
        } else if (paramError === 'invalidName') {
            alert('Error.\nEl nombre no puede contener números.');
        
        } else if (paramError === 'invalidSurnames') {
            alert('Error.\nEl apellido no puede contener números.');
        
        } else if (paramError === 'legalidalreadyexists') {
            alert('Error. La identificación legal ya existe');
        
        } else if (paramError === 'invalidFileType') {
            alert('Error.\nEl formato de la imagen no es válido');
        
        } else if (paramError === 'uploadFailed') {
            alert('Error.\nNo se subió la imagen');
        
        } else if (paramError === 'invalidCostaRicaId') {
            alert('Error.\nEl formato de la cédula no es válido. \nFormato válido: 9 dígitos "123456789" sin guiones ni espacios');
        
        } else if (paramError === 'invalidForeignId') {
            alert('Error.\nEl formato de la identificación no es válido. \nDebe ser un número de 8 a 12 dígitos');
        
        } else if (paramError === 'invalidPhone') {
            alert('Error.\nEl formato del número de teléfono no es válido. \nDebe ser de 8 dígitos "12345678" sin guiones ni espacios');
        
        } else if (paramError === 'invalidEmailFormat') {
            alert('Error.\nEl formato del Email no es válido \nDebe llevar @ y un dominio válido');
        
        } else if (paramError === 'imageUploadFailed') {
            alert('Error.\nError al subir la imagen');
        
        } else if (paramError === 'numberFormat') {
            alert('Error.\nNo se permiten números en el nombre ni en los apellidos');
        
        } else if (paramError === 'error') {
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

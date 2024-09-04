function confirmAction(event) {
    if (event.submitter && event.submitter.name === 'delete') {
        return confirm('¿Estás seguro de que deseas eliminar este registro?');
    } else if (event.submitter && event.submitter.name === 'update') {
        return confirm('¿Estás seguro de que deseas actualizar este registro?');
    } else if (event.submitter && event.submitter.name === 'create') {
        return confirm('¿Estás seguro de que deseas insertar este registro?');
    }
    return true;
};

function showAlertBasedOnURL() {
    const urlParams = new URLSearchParams(window.location.search);

    // Obtener parámetros de la URL
    // Remove unused variables
    // const paramSuccessInserted = urlParams.get('inserted');
    // const paramAlreadyExistsInserted = urlParams.get('alreadyexists');
    // const paramDbErrorInserted = urlParams.get('dbError');
    // const paramInvalidOwnerOrCompanyTypeInserted = urlParams.get('invalidOwnerOrCompanyType');
    // const paramNumberFormatInserted = urlParams.get('numberFormat');
    // const paramEmptyFieldInserted = urlParams.get('emptyField');
    // const paramErrorsInStatusInserted = urlParams.get('errorsInStatus');
    // const paramErrorCompanyTypeInserted = urlParams.get('companyType');
    // const paramErrorInOwnerInserted = urlParams.get('errorInOwner');
    // const paramErrorInMagicNameInserted = urlParams.get('errorInMagicName');
    // const paramErrorInLegalNameInserted = urlParams.get('errorInLegalName');

    // const paramSuccessUpdated = urlParams.get('updated');
    // const paramErrorUpdated = urlParams.get('error');
    // const paramInvalidOwnerOrCompanyTypeUpdated = urlParams.get('invalidOwnerOrCompanyType');
    // const paramNumberFormatUpdated = urlParams.get('numberFormat');
    // const paramEmptyFieldUpdated = urlParams.get('emptyField');
    // const paramMissingFieldsUpdated = urlParams.get('missingFields');

    // const paramDeleted = urlParams.get('deleted');
    // const paramDeleteFailed = urlParams.get('deleteFailed');
    // const paramInvalidId = urlParams.get('invalidId');
    // const paramMissingId = urlParams.get('missingId');

    // Mostrar alertas basadas en los parámetros de la URL
    if (urlParams.has('success')) {
        switch (urlParams.get('success')) {
            case 'updated':
                alert('Se ha actualizado correctamente.');
                break;
            case 'deleted':
                alert('Se ha eliminado correctamente.');
                break;
            case 'inserted':
                alert('Se ha ingresado correctamente.');
                break;
            default:
                break;
        }
    } else if (urlParams.has('error')) {
        switch (urlParams.get('error')) {
            case 'dbError':
                alert('Error del sistema al realizar la acción.');
                break;
            case 'emptyField':
                alert('Debes ingresar un nombre, el campo de texto no debe estar vacío.');
                break;
            case 'numberFormat':
                alert('No puedes ingresar solo números o seleccionar Nignguno en Estado.');
                break;
            case 'alreadyexists':
                alert('El nombre ya existe, ingrese otro.');
                break;
            case 'invalidOwnerOrCompanyType':
                alert('Tipo de propietario o empresa inválido.');
                break;
            case 'companyType':
                alert('Error en el tipo de empresa.');
                break;
            case 'errorInOwner':
                alert('Error en el propietario.');
                break;
            case 'errorInMagicName':
                alert('Error en el nombre mágico.');
                break;
            case 'errorInLegalName':
                alert('Error en el nombre legal.');
                break;
            case 'missingFields':
                alert('Faltan campos requeridos.');
                break;
            case 'invalidId':
                alert('ID inválido.');
                break;
            case 'missingId':
                alert('ID faltante.');
                break;
            case 'deleteFailed':
                alert('No se pudo eliminar.');
                break;
            default:
                break;
        }
    }
}


document.addEventListener('DOMContentLoaded', function () {
    // Función para mostrar mensajes de error
    function showError(message) {
        // Crea un elemento para el mensaje de error
        let errorContainer = document.createElement('div');
        errorContainer.classList.add('error-message');
        errorContainer.innerHTML = `<p>${message}</p>`;

        // Añade el mensaje al cuerpo del documento
        document.body.insertBefore(errorContainer, document.body.firstChild);

        // Opcional: Ocultar el mensaje después de unos segundos
        setTimeout(function () {
            errorContainer.remove();
        }, 5000);
    }

    // Obtener parámetros de la URL
    const params = new URLSearchParams(window.location.search);
    const error = params.get('error');

    // Mostrar el mensaje de error correspondiente
    if (error) {
        const errorMessages = {
            errorInLegalName: 'El nombre legal es obligatorio y no puede ser un número.',
            errorInMagicName: 'El nombre mágico es obligatorio y no puede ser un número.',
            errorInOwner: 'Debe seleccionar un dueño válido.',
            errorCompanyType: 'Debe seleccionar un tipo de empresa válido.',
            errorsInStatus: 'El estado es obligatorio.',
            emptyField: 'Todos los campos deben estar llenos.',
            numberFormat: 'Formato de número incorrecto.',
            invalidOwnerOrCompanyType: 'El dueño o el tipo de empresa no son válidos.',
            dbError: 'Error al guardar la empresa en la base de datos.',
            alreadyexists: 'La empresa ya existe.',
            updateFailed: 'Error al actualizar la empresa, YA EXISTE',
            deleteFailed: 'Error al eliminar la empresa.',
            missingFields: 'Faltan campos requeridos.',
            invalidId: 'ID inválido.',
            missingId: 'ID faltante.'
        };

        // Mostrar el mensaje de error si existe
        if (errorMessages[error]) {
            showError(errorMessages[error]);
        }
    }
});



//COSAS NUEVAS

function redirectToCleanURL() {
    const cleanURL = window.location.origin + window.location.pathname;
    window.history.replaceState({}, document.title, cleanURL);
}

window.onload = function () {
    showAlertBasedOnURL();
    redirectToCleanURL(); // Esto limpiará la URL después de mostrar los mensajes de alerta.
};


document.getElementById('formCreate').addEventListener('submit', function (e) {
    e.preventDefault();

    // Validaciones
    const magicName = document.getElementById('magicName').value.trim();
    const legalName = document.getElementById('legalName').value.trim();
    const owner = document.getElementById('ownerId').value;
    const companyType = document.getElementById('companyType').value;
    const images = document.getElementById('imagenes').files[0];
    const status = document.getElementById('status').value;

    if (magicName === '') {
        alert('El nombre mágico no puede estar vacío.');
        return;
    }
    if (legalName === '') {
        alert('El nombre legal no puede estar vacío.');
        return;
    }
    if (owner === '0') {
        alert('El propietario no puede ser ninguno.');
        return;
    }
    if (companyType === '0') {
        alert('El tipo de empresa no puede ser ninguno.');
        return;
    }

    // Datos a enviar
    const formData = new FormData();
    formData.append('magicName', magicName);
    formData.append('legalName', legalName);
    formData.append('ownerId', owner);
    formData.append('companyType', companyType);
    formData.append('imagenes', images);
    formData.append('status', status);
    formData.append('create', 'create');

    // Configuración AJAX
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '../business/touristCompanyAction.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            alert(xhr.readyState);
            console.log(xhr.responseText);  // Verificar lo que devuelve el servidor
            try {
                alert(xhr.responseText);
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


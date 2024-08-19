(function confirmAction(event) {
    if (event.submitter && event.submitter.name === 'delete') {
        return confirm('¿Estás seguro de que deseas eliminar este registro?');
    } else if (event.submitter && event.submitter.name === 'update') {
        return confirm('¿Estás seguro de que deseas actualizar este registro?');
    }
    return true;
})();

function showAlertBasedOnURL() {
    const urlParams = new URLSearchParams(window.location.search);

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
                alert('Deber ingresar un nombre, el campo de texto no debe estar vacío.');
                break;
            case 'numberFormat':
                alert('No puedes ingresar solo números en el campo de texto.');
                break;
            case 'alreadyexists':
                alert('El nombre ya existe, ingrese otro.');
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
            errorcompanyType: 'Debe seleccionar un tipo de empresa válido.',
            errorsInStatus: 'El estado es obligatorio.',
            emptyField: 'Todos los campos deben estar llenos.',
            numberFormat: 'Formato de número incorrecto.',
            invalidOwnerOrCompanyType: 'El dueño o el tipo de empresa no son válidos.',
            dbError: 'Error al guardar la empresa en la base de datos.',
            alreadyexists: 'La empresa ya existe.',
            updateFailed: 'Error al actualizar la empresa.',
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

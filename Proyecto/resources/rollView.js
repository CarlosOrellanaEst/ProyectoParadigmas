function confirmDelete(event) {
    if (event.submitter && event.submitter.name === 'delete') {
        return confirm('¿Estás seguro de que deseas eliminar este rol?');
    }
    return true;
}

function showAlertBasedOnURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const paramSuccess = urlParams.get('success');
    const paramError = urlParams.get('error');

    // Verificar y mostrar alertas de éxito
    if (paramSuccess) {
        if (paramSuccess === 'updated') {
            alert('El rol se ha actualizado con éxito.');
        } else if (paramSuccess === 'inserted') {
            alert('El rol se ha creado con éxito.');
        } else if (paramSuccess === 'deleted') {
            alert('El rol se ha eliminado con éxito.');
        }
    }

    // Verificar y mostrar alertas de error
    if (paramError) {
        if (paramError === 'alreadyExists') {
            alert('Error.\nYa existe dicho rol');
        } else if (paramError === 'numberFormat' || paramError === 'emptyField') {
            alert('Error.\nIngrese un nombre para el rol. No se permiten numeros');
        } else {
            alert('Error.\nPor favor notificar de este error');
        }
    }
}

window.onload = showAlertBasedOnURL;
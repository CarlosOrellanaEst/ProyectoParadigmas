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

    if (urlParams.has('success')) {
        if(paramSuccess === 'updated') {
            alert('El rol se ha actualizado con éxito.');
        } else if (paramSuccess && urlParams.get('success') === 'inserted') {
            alert('El rol se ha creado con éxito.');
        } else if (paramSuccess && urlParams.get('success') === 'deleted') {
            alert('El rol se ha eliminado con éxito.');
        }
    } else if(urlParams.has('error')) {
        if(paramError === 'alreadyexists') { 
            alert('Error.\nYa existe dicho rol');
        } else if (paramError === 'numberFormat' || paramError === 'emptyField') {
            alert('Error.\n Ingrese un nombre para el rol. No se permiten numeros');
        } else {
            alert('Error.\nPor favor notificar de este error');
        }
    }
} 

window.onload = showAlertBasedOnURL;
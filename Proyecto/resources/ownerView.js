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
            alert('Error.\nEl telefono o el correo ya existe');
        } else if (paramError === 'emptyField') {
            alert('Error.\nEl telefono o el correo ya existe');
        } else {
            alert('Error.\nPor favor notificar de este error');
        }
    }
} 


window.onload = showAlertBasedOnURL;
function confirmAction(event) {
    if (event.submitter && event.submitter.name === 'delete') {
        return confirm('¿Estás seguro de que deseas eliminar esta cuenta bancaria?');
    } else if (event.submitter && event.submitter.name === 'update'){
        return confirm('¿Estás seguro de que deseas actualizar esta cuenta bancaria?');
    }
    return true;
}

function showAlertBasedOnURL() {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('success')) {
        switch (urlParams.get('success')) {
            case 'updated':
                alert('La cuenta bancaria se ha actualizado correctamente.');
            break;
            case 'deleted':
                alert('La cuenta bancaria se ha eliminado correctamente.');
            break;
            case 'inserted':
                alert('La cuenta bancaria se ha ingresado correctamente.');
            break;
            default:
            break;
        }
    } else if (urlParams.has('error')){
        switch (urlParams.get('error')) {
            case 'dbError':
                alert('Error interno al realizar la acción.');
            break;
            case 'emptyField':
                alert('El campo de texto no debe estar vacío.');
            break;
            case 'numberFormat':
                alert('No puedes ingresar solo números en el nombre del banco.');
            break;
            case 'alreadyexists':
                alert('El número de cuenta ya existe, ingrese otro.');
            break;
            default:
            break;
        }
    }
}

window.onload = showAlertBasedOnURL;
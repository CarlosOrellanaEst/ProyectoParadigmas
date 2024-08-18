function confirmAction(event) {
    if (event.submitter && event.submitter.name === 'delete') {
        return confirm('¿Estás seguro de que deseas eliminar este registro?');
    } else if (event.submitter && event.submitter.name === 'update'){
        return confirm('¿Estás seguro de que deseas actualizar este registro?');
    }
    return true;
}

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
    } else if (urlParams.has('error')){
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
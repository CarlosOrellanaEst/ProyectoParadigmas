function confirmAction(event) {
    if (event.submitter && event.submitter.name === 'delete') {
        return confirm('¿Estás seguro de que deseas eliminar este registro?');
    } else if (event.submitter && event.submitter.name === 'update'){
        return confirm('¿Estás seguro de que deseas actualizar el tipo de pago?');
    }
    return true;
}

function showAlertBasedOnURL() {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('success')) {
        switch (urlParams.get('success')) {
            case 'updated':
                alert('El tipo de pago se ha actualizado correctamente.');
            break;
            case 'deleted':
                alert('El tipo de pago se ha eliminado correctamente.');
            break;
            case 'inserted':
                alert('El tipo de pago se ha ingresado correctamente.');
            break;
            default:
            break;
        }
    } else if (urlParams.has('error')){
        switch (urlParams.get('error')) {
            case 'dbError':
                alert('Error interno al realizar la acción.');
            break;
            case 'accountRequired':
                alert('El número de cuenta es obligatorio.');
            break;
            case 'invalidSinpe':
                alert('El número de SINPE debe ser numérico.');
            break;
            case 'invalidSinpeFormat':
                alert('El número debe tener 8 digitos.');
            break;
            case 'emptyField':
                alert('El campo de texto no debe estar vacío.');
            break;
            case 'numberFormat':
                alert('No puedes ingresar letras en el número de SINPE.');
            break;
            case 'numberFormatBAnkAccount':
                alert('La cuenta de banco no cumple con el formato correcto (Ejm: CR12345678901234567890)');
            break;
            case 'duplicateEntry':
                alert('Entrada duplicada.');
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
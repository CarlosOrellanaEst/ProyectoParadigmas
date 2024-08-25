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
        }else {
            alert('Error.\nPor favor notificar de este error');
        }
    }
} 


window.onload = showAlertBasedOnURL;
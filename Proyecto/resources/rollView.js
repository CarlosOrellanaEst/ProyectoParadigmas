function confirmDelete(event) {
    if (event.submitter && event.submitter.name === 'delete') {
        return confirm('¿Estás seguro de que deseas eliminar este rol?');
    }
    return true;
}

function showAlertBasedOnURL() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success') && urlParams.get('success') === 'updated') {
        alert('El rol se ha actualizado correctamente.');
    }
}

window.onload = showAlertBasedOnURL;
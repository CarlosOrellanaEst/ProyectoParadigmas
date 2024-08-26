document.addEventListener('DOMContentLoaded', function () {

    
    

    // Crear Empresa Turística
    
    document.getElementById('formCreate').addEventListener('submit', function (e) {
        
        e.preventDefault();

        // Validaciones
        const magicName = document.getElementById('magicName').value.trim();
        const legalName = document.getElementById('legalName').value.trim();
        const owner = document.getElementById('ownerId').value;
        const companyType = document.getElementById('companyType').value;
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
        const postData = new URLSearchParams({
            create: true,
            magicName: magicName,
            legalName: legalName,
            ownerId: owner,
            companyType: companyType,
            status: status
        });

        // Configuración AJAX
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/touristCompanyAction.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.status === 'success') {
                            alert(response.message);
                            document.getElementById('formCreate').reset();
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

        xhr.send(postData.toString());
    });

    /*
    
    //editar empresa turistica
    const forms = document.querySelectorAll('.formEdit');
    console.log('Forms found:', forms.length);
    document.querySelectorAll('.formEdit').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
    
            // Crear objeto de datos a enviar
            const formData = new FormData(form);
            formData.append('update', true);
    
            // Validaciones
            const legalName = formData.get('legalName').trim();
            const magicName = formData.get('magicName').trim();
            const ownerId = formData.get('ownerId');
            const companyType = formData.get('companyType');
    
            if (magicName === '') {
                alert('El nombre mágico no puede estar vacío.');
                return;
            }
            if (legalName === '') {
                alert('El nombre legal no puede estar vacío.');
                return;
            }
            if (ownerId === '0') {
                alert('El propietario no puede ser ninguno.');
                return;
            }
            if (companyType === '0') {
                alert('El tipo de empresa no puede ser ninguno.');
                return;
            }
    
            // Configuración AJAX
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '../business/touristCompanyAction.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    try {
                        let response = JSON.parse(xhr.responseText);
                        if (xhr.status === 200) {
                            if (response.status === 'success') {
                                alert(response.message);
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
    
            xhr.send(new URLSearchParams(formData).toString());
        });
    });
    */
 
    

    /*

    // Eliminar Empresa Turística
    
    document.addEventListener('DOMContentLoaded', function () {
        
        console.log('DOM completamente cargado y analizado');
        const deleteButtons = document.querySelectorAll('.btnDelete');
        console.log('Delete buttons found:', deleteButtons.length); // Esto debería mostrar la cantidad de botones encontrados
    
        deleteButtons.forEach(button => {
            if (button) {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
    
                    if (confirm('¿Estás seguro de que deseas eliminar esta empresa turística?')) {
                        const form = button.closest('form'); // Encuentra el formulario más cercano
                        const formData = new FormData(form);
                        formData.append('delete', true);
    
                        // Configuración AJAX
                        let xhr = new XMLHttpRequest();
                        xhr.open('POST', '../business/touristCompanyAction.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4) {
                                try {
                                    let response = JSON.parse(xhr.responseText);
                                    if (xhr.status === 200) {
                                        if (response.status === 'success') {
                                            alert(response.message);
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
    
                        xhr.send(new URLSearchParams(formData).toString());
                    }
                });
            } else {
                console.error('Botón de eliminación no encontrado.');
            }
        });
    });
    */

});

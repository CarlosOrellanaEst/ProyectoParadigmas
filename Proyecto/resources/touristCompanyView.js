document.addEventListener('DOMContentLoaded', function () {
    // Mostrar alertas basadas en los parámetros de la URL
    showAlertBasedOnURL();

    // Manejo de la selección de tipo de empresa
    const companyTypeSelect = document.getElementById('companyType');
    const customCompanyTypeField = document.getElementById('customCompanyType');
    const customCompanyTypeLabel = document.getElementById('customCompanyTypeName');

    // Mostrar u ocultar el campo personalizado según la selección del tipo de empresa
    companyTypeSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customCompanyTypeField.style.display = 'block';
            customCompanyTypeLabel.style.display = 'block';
        } else {
            customCompanyTypeField.style.display = 'none';
            customCompanyTypeLabel.style.display = 'none';
        }
    }); 

    let selectedCompanyTypes = [];
    document.getElementById('addBtn').addEventListener('click', function () {
        let companyTypeSelect = document.getElementById('companyType');
        let selectedValue = companyTypeSelect.value;
        let selectedText = companyTypeSelect.options[companyTypeSelect.selectedIndex].text;
    
        // Validación: Evitar agregar opciones con valor "0" o duplicadas
        if (selectedValue !== "0" && !selectedCompanyTypes.includes(selectedValue)) {
            selectedCompanyTypes.push(selectedValue);
    
            // Mostrar la selección en la lista
            let companyTypeList = document.getElementById('selectedCompanyTypesList');
            let companyTypeItem = document.createElement('div');
            companyTypeItem.textContent = selectedText;
            companyTypeList.appendChild(companyTypeItem);
        } else if (selectedValue === "0") {
            alert("Por favor, seleccione un tipo de empresa válido.");
        } else {
            alert("El tipo de empresa ya ha sido agregado.");
        }
    });

    
    // Manejo del envío de formulario
    document.getElementById('formCreate').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevenir el envío por defecto del formulario

        const magicName = document.getElementById('magicName').value.trim();
        const legalName = document.getElementById('legalName').value.trim();
        const owner = document.getElementById('ownerId').value;
        const companyType = document.getElementById('companyType').value;
        const images = document.getElementById('imagenes').files;
        const status = document.getElementById('status').value;
        const ownerError = document.getElementById('ownerError');
        const customCompanyType = document.getElementById('customCompanyType').value.trim();
        const customCompanyTypeError = document.getElementById('customCompanyTypeError');

        ownerError.style.display = 'none';
        customCompanyTypeError.style.display = 'none';

        // Validaciones de campos requeridos
        if (owner === '0') { 
            ownerError.style.display = 'inline'; 
            console.log("Error: Owner is required");
            return; 
        }

        if (companyType === 'custom' && customCompanyType === '') {
            customCompanyTypeError.style.display = 'inline';
            console.log("Error: Custom Company Type is required");
            return;
        }
        console.log("customCompanyType: ", customCompanyType);
        // Crear y enviar datos del formulario mediante AJAX
        const formData = new FormData();
        formData.append('magicName', magicName);
        formData.append('legalName', legalName);
        formData.append('ownerId', owner);
        formData.append('status', status);
        formData.append('create', 'create'); 

        //
        const selectedCompanyTypes = []; 
    const companyTypeList = document.getElementById('selectedCompanyTypesList').children;

    for (let item of companyTypeList) {
        selectedCompanyTypes.push(item.textContent);
    }

    selectedCompanyTypes.forEach((type) => {
        formData.append('selectedCompanyTypes[]', type); // Agrega cada tipo de empresa al FormData
    });
        //

        if (companyType === 'custom') {
            formData.append('customCompanyType', customCompanyType);
        } else {
            formData.append('companyType', companyType);
        }

        // Validar y agregar archivos seleccionados
        if (images.length > 0) {
            for (let i = 0; i < images.length; i++) {
                formData.append('imagenes[]', images[i]);
            }
        } else {
            console.log("No images selected");
        }

        console.log("Form data prepared:", formData);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/touristCompanyAction.php', true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            document.getElementById('formCreate').reset();
                            location.reload(); 
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } catch (e) {
                        console.error('Respuesta JSON inválida:', xhr.responseText);
                        alert('Error procesando la respuesta del servidor.');
                    }
                } else {
                    console.error('Error HTTP:', xhr.status, xhr.statusText);
                    alert('Error HTTP: ' + xhr.status + ' - ' + xhr.statusText);
                }
            }
        };

        xhr.send(formData);
    });
});


// Función para mostrar alertas basadas en los parámetros de la URL
function showAlertBasedOnURL() {
    const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('error')) {
        switch (urlParams.get('error')) {
            case 'uploadFailed':
                alert('Error al subir la imagen. Por favor, inténtelo de nuevo.');
                break;
            case 'invalidFileType':
                alert('Formato de imagen inválido. Solo se permiten JPG, PNG, JPEG y GIF.');
                break;
            case 'dbError':
                alert('Error en la base de datos al realizar la acción.');
                break;
            case 'emptyField':
                alert('El campo de texto no puede estar vacío.');
                break;
            case 'invalidOwnerOrCompanyType':
                alert('Propietario o tipo de empresa no válido. Por favor, revise los campos.');
                break;
            case 'invalidId':
                alert('ID inválido. No se pudo completar la acción.');
                break;
            case 'missingFields':
                alert('Faltan campos obligatorios. Por favor, complete todos los datos.');
                break;
            case 'deleteFailed':
                alert('Error al eliminar la empresa.');
                break;
            case 'updateFailed':
                alert('Error al actualizar la empresa.');
                break;
            case 'companyExists':  
                alert('Ya existe una empresa turística con el mismo nombre legal y está activa.');
                break;
            default:
                alert('Ocurrió un error inesperado.');
                break;
        }
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // Obtener los elementos del DOM
    const companyTypeSelect = document.getElementById("companyType");
    const customCompanyTypeField = document.getElementById("customCompanyType");
    const customCompanyTypeLabel = document.getElementById("customCompanyTypeName");
    const selectedCompanyTypesList = document.getElementById("selectedCompanyTypesList");

    // Mostrar u ocultar el campo personalizado según la selección del tipo de empresa
    companyTypeSelect.addEventListener("change", function () {
        if (this.value === "custom") {
            customCompanyTypeField.style.display = "block";
            customCompanyTypeLabel.style.display = "block";
        } else {
            customCompanyTypeField.style.display = "none";
            customCompanyTypeLabel.style.display = "none";
        }
    });

    let selectedCompanyTypes = [];

    // Evento de click para agregar un tipo de empresa a la lista
    document.getElementById("addBtn").addEventListener("click", function () {
        let selectedValue = companyTypeSelect.value;
        let selectedText = companyTypeSelect.options[companyTypeSelect.selectedIndex].text;

        // Si se selecciona un tipo válido (no "0" y no duplicado)
        if (selectedValue !== "0" && !selectedCompanyTypes.includes(selectedValue)) {
            selectedCompanyTypes.push(selectedValue);

            let companyTypeItem = document.createElement("div");
            companyTypeItem.textContent = selectedText;
            companyTypeItem.dataset.typeId = selectedValue;  // Agregar el data-type-id con el valor seleccionado
            companyTypeItem.classList.add("selected-type");  // Agregar una clase para facilitar su identificación

            // Crear un botón de eliminación para este tipo de empresa
            let removeBtn = document.createElement("button");
            removeBtn.textContent = "-";
            removeBtn.classList.add("remove-btn");

            // Evento para eliminar el tipo de empresa de la lista
            removeBtn.addEventListener("click", function () {
                selectedCompanyTypes = selectedCompanyTypes.filter(type => type !== selectedValue);
                selectedCompanyTypesList.removeChild(companyTypeItem);
            });

            companyTypeItem.appendChild(removeBtn);
            selectedCompanyTypesList.appendChild(companyTypeItem);
        } else if (selectedValue === "0") {
            alert("Por favor, seleccione un tipo de empresa válido.");
        } else {
            alert("El tipo de empresa ya ha sido agregado.");
        }
    });


    document.getElementById("formCreate").addEventListener("submit", function (e) {
        e.preventDefault();
    
        const actionType = document.getElementById("actionType").value;
        const magicName = document.getElementById("magicName").value.trim();
        const legalName = document.getElementById("legalName").value.trim();
        const owner = document.getElementById("ownerId").value;
        const companyType = document.getElementById("companyType").value;
        const images = document.getElementById("imagenes").files;
        const status = document.getElementById("status").value;
        const customCompanyType = document.getElementById("customCompanyType").value.trim();
    
        // Validaciones
        if (owner === "0") {
            alert("Error: se necesita seleccionar un propietario para registrar.");
            return;
        }
    
        if (companyType === "0" || (companyType === "custom" && customCompanyType === "")) {
            alert("Error: se necesita seleccionar un tipo de empresa.");
            return;
        }
    
        // Recoger los tipos de empresa seleccionados en el contenedor
        const selectedCompanyTypes = [];
        const selectedCompanyTypeElements = document.querySelectorAll("#selectedCompanyTypesList .selected-type");
    
        selectedCompanyTypeElements.forEach(function (element) {
            selectedCompanyTypes.push(element.dataset.typeId);  // Usamos data-type-id para obtener el valor del ID
        });
    
        console.log("Tipos de empresa seleccionados:", selectedCompanyTypes); // Mostrar en consola los tipos seleccionados
    
        // Concatenar los tipos de empresa con su ID
        let companyTypeData = '';
    
        // Agregar tipos seleccionados al string (sin duplicados)
        if (selectedCompanyTypes.length > 0) {
            companyTypeData += selectedCompanyTypes.join(',');  // Agregar los IDs de los tipos de empresa
        }
    
        // Si el tipo de empresa es 'custom', añadir customCompanyType (sin el '0')
        if (companyType === "custom" && customCompanyType) {
            if (companyTypeData) {
                companyTypeData += ',';  // Separador para los valores adicionales
            }
            companyTypeData += customCompanyType;  // Solo agregamos el nombre del tipo personalizado, sin el '0'
        }
    
        console.log("Datos a enviar en companyTypeData:", companyTypeData);  // Ver qué se enviará
    
        const formData = new FormData();
        formData.append("magicName", magicName);
        formData.append("legalName", legalName);
        formData.append("ownerId", owner);
        formData.append("status", status);
        formData.append("create", "create");
    
        formData.append("companyTypeData", companyTypeData);  // Enviar la variable concatenada
    
        // Agregar imágenes si las hay
        if (images.length > 0) {
            for (let i = 0; i < images.length; i++) {
                formData.append("imagenes[]", images[i]);
            }
        }
    
        // Verificar el contenido de formData
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
    
        sendAjaxRequest(formData, "Crear empresa");
    });



    // Captura el evento de confirmación de acciones en los formularios de actualización y eliminación
    document.querySelectorAll("form[onsubmit]").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            // Determina el tipo de acción (actualizar, eliminar o eliminar imagen)
            const actionType = e.submitter.name; // "update", "delete" o "deleteImage"
            if (actionType === "delete" && !confirm("¿Estás seguro de que deseas eliminar este registro?")) {
                return;
            } else if (actionType === "update" && !confirm("¿Estás seguro de que deseas actualizar este registro?")) {
                return;
            } else if (actionType === "deleteImage" && !confirm("¿Estás seguro de que deseas eliminar esta imagen?")) {
                return;
            }

            const formData = new FormData(form);
            formData.append(actionType, actionType); // Añade el tipo de acción al FormData

            sendAjaxRequest(formData, actionType);
        });
    });

    // Función para manejar la solicitud AJAX
    function sendAjaxRequest(formData, actionDescription) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../business/touristCompanyAction.php", true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log("Respuesta del servidor:", xhr.responseText);
                try {
                    let response = JSON.parse(xhr.responseText.trim());
                    if (xhr.status === 200 && response.status === "success") {
                        alert(response.message);
                        location.reload(); // Recargar la página para reflejar los cambios
                    } else {
                        handleErrorResponse(response);
                        location.reload();
                    }
                } catch (e) {
                    console.error("Error al procesar la respuesta JSON:", e);
                    alert("Error al procesar la respuesta del servidor.");
                }
            }
        };

        xhr.send(formData); // Envía los datos de formulario
    }

    // Manejo de errores del servidor
    function handleErrorResponse(response) {
        switch (response.error_code) {
            case "max_images_exceeded":
                alert("Error: Solo se permite subir un máximo de 5 imágenes.");
                break;
            case "file_move_failed":
                alert("Error: No se pudo mover la imagen al directorio.");
                break;
            case "invalid_file_type":
                alert("Error: Formato de imagen inválido. Solo se permiten JPG, PNG, JPEG y GIF.");
                break;
            case "custom_company_type_required":
                alert("Error: Debe especificar un tipo de empresa personalizado.");
                break;
            case "company_exists":
                alert("Error: La empresa ya existe.");
                break;
            case "database_error":
                alert("Error en la base de datos al realizar la acción.");
                break;
            case "invalid_owner_or_company_type":
                alert("Error: Propietario o tipo de compañía inválido.");
                break;
            case "owner_required":
                alert("Error: El campo propietario es obligatorio.");
                break;
            case "upload_failed":
                alert("Error al subir la imagen. Por favor, inténtelo de nuevo.");
                break;
            case "update_failed":
                alert("Error al actualizar la empresa.");
                break;
            case "delete_failed":
                alert("Error al eliminar la empresa.");
                break;
            case "image_not_found":
                alert("Error: Imagen no encontrada.");
                break;
            default:
                alert(response.message || "Ocurrió un error inesperado.");
                break;
        }
    }
});


/*let selectedCompanyTypes = [];
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

    } else if (selectedValue === "custom") {
        alert("Por favor, seleccione un tipo de empresa válido.");
    } else {
        alert("El tipo de empresa ya ha sido agregado.");
    }
});*/


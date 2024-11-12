document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('formCreate').addEventListener('submit', function (e) {
        e.preventDefault();

        const ownerName = document.getElementById('name').value.trim();
        const ownerSurnames = document.getElementById('surnames').value.trim();
        const nickName = document.getElementById('nickName').value.trim();
        const ownerLegalIdentification = document.getElementById('legalIdentification').value.trim();
        const ownerPhone = document.getElementById('phone').value.trim();
        const ownerEmail = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('confirmPassword').value.trim();
        const imageFile = document.getElementById('imagen').files[0];

        // Validación básica
        if (ownerLegalIdentification === '') {
            alert('La identificación legal no puede estar vacía.');
            return;
        }

        if(nickName === ''){
            alert('El campo "Nombre de usuario" es requerido.');
            return;
        }
        
        if (ownerEmail === '') {
            alert('El correo no puede estar vacío.');
            return;
        }

        // Validación de contraseñas
        if (password === '' || confirmPassword === '') {
            alert('Las contraseñas no pueden estar vacías.');
            return;
        }

        
        if (password !== confirmPassword) {
            alert('Las contraseñas no coinciden.');
            return;
        }
        
        const formData = new FormData();
        formData.append('ownerName', ownerName);
        formData.append('ownerSurnames', ownerSurnames);
        formData.append('nickName', nickName);
        formData.append('ownerLegalIdentification', ownerLegalIdentification);
        formData.append('ownerPhone', ownerPhone);
        formData.append('ownerEmail', ownerEmail);
        formData.append('ownerDirection', document.getElementById('direction').value.trim());
        formData.append('password', password); 
        formData.append('confirmPassword', confirmPassword);
        formData.append('imagen', imageFile);
        formData.append('idType', document.getElementById('idType').value);
        formData.append('create', 'create');

        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/ownerAction.php', true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.status === 'success') {
                            alert(response.message);
                            document.getElementById('formCreate').reset();
                            setTimeout(() => location.reload(), 500); 
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } else {
                        alert('HTTP Error: ' + xhr.status);
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', xhr.responseText);
                    alert('Error procesando la respuesta del servidor.');
                }
            }
        };

        xhr.send(formData);
    });

    document.querySelectorAll("form[onsubmit]").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault(); // Prevenir el envío directo del formulario

            // Determina el tipo de acción (actualizar o eliminar)
            const actionType = e.submitter.name; // "update" o "delete"
            if (actionType === "delete" && !confirm("¿Estás seguro de que deseas eliminar este propietario?")) {
                return;
            } else if (actionType === "update" && !confirm("¿Estás seguro de que deseas actualizar este propietario?")) {
                return;
            }

            const formData = new FormData(form);
            formData.append(actionType, actionType); // Añade el tipo de acción al FormData

            // Enviar la solicitud AJAX
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../business/ownerAction.php", true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    try {
                        console.log("Respuesta del servidor:", xhr.responseText); // Agrega esta línea para depurar
                        let response = JSON.parse(xhr.responseText);
                        if (xhr.status === 200 && response.status === "success") {
                            if (actionType === "delete") {
                                alert(response.message);
                                location.reload(); 
                            } else {
                                location.reload(); 
                            }

                            if(actionType === "update"){
                                alert(response.message);
                                location.reload(); 
                            }
                            
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
        });
    });

    // Manejo de errores del servidor
function handleErrorResponse(response) {
    switch (response.error_code) {
        case "password_mismatch":
            alert("Error: Las contraseñas no coinciden.");
            break;
        case "invalid_costa_rica_id":
            alert("Error: Identificación de Costa Rica inválida. Debe contener exactamente 9 dígitos.");
            break;
        case "invalid_foreign_id":
            alert("Error: Identificación extranjera inválida. Debe contener entre 6 y 12 caracteres alfanuméricos.");
            break;
        case "invalid_name":
            alert("Error: El nombre contiene caracteres inválidos.");
            break;
        case "invalid_surnames":
            alert("Error: Los apellidos contienen caracteres inválidos.");
            break;
        case "invalid_phone":
            alert("Error: Número de teléfono inválido. Debe contener exactamente 8 dígitos.");
            break;
        case "invalid_email":
            alert("Error: Formato de correo electrónico inválido.");
            break;
        case "duplicate_entry":
            alert("Error: Entrada duplicada en la base de datos.");
            break;
        case "db_error":
            alert("Error: Fallo en la base de datos al realizar la acción.");
            break;
        default:
            alert(response.message || "Error desconocido.");
            break;
    }
}
});
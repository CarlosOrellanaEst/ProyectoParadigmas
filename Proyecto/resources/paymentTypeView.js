document.addEventListener("DOMContentLoaded", function () {
    // Captura el evento de confirmación de acciones en los botones de actualizar y eliminar
    document.querySelectorAll("form[onsubmit]").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            // Determina el tipo de acción (actualizar o eliminar)
            const actionType = e.submitter.name; // "update" o "delete"
            if (actionType === "delete" && !confirm("¿Estás seguro de que deseas eliminar este registro?")) {
                return;
            } else if (actionType === "update" && !confirm("¿Estás seguro de que deseas actualizar el tipo de pago?")) {
                return;
            }

            const formData = new FormData(form);
            formData.append(actionType, actionType); // Añade el tipo de acción al FormData

            // Enviar la solicitud AJAX
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "../business/paymentTypeAction.php", true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    try {
                        let response = JSON.parse(xhr.responseText);
                        if (xhr.status === 200 && response.status === "success") {
                            alert(response.message);
                            location.reload(); // Recargar la página para reflejar los cambios
                        } else {
                            handleErrorResponse(response);
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
});

// Manejo de errores del servidor
function handleErrorResponse(response) {
    switch (response.error_code) {
        case "account_required":
            alert("Error: El número de cuenta es obligatorio.");
            break;
        case "invalid_sinpe_number":
            alert("Error: El número de SINPE debe ser numérico.");
            break;
        case "sinpe_format_invalid":
            alert("Error: El número de SINPE debe tener 8 dígitos.");
            break;
        case "duplicate_entry":
            alert("Error: Entrada duplicada en la base de datos.");
            break;
        case "db_error":
            alert("Error: Fallo en la base de datos al realizar la acción.");
            break;
        case "number_format_bank_account":
            alert("Error: La cuenta de banco no cumple con el formato correcto (Ejm: CR12345678901234567890).");
            break;
        default:
            alert(response.message || "Error desconocido.");
            break;
    }
}

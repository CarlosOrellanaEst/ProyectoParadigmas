document.addEventListener('DOMContentLoaded', function () { 
    document.getElementById('formCreate').addEventListener('submit', function (e) {
        e.preventDefault();  // Evita el envío directo del formulario
    
        const accountNumber = document.getElementById('accountNumber').value.trim();
        if (accountNumber === '') {
            alert('El número de cuenta no puede estar vacío.');
            return;
        }

        const postData = {
            ownerId: document.getElementById('ownerId').value,
            accountNumber: accountNumber,
            sinpeNumber: document.getElementById('sinpeNumber').value
        };
    
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/paymentTypeAction.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.status === 'success') {
                            alert(response.message);
                            document.getElementById('formCreate').reset();
                            location.reload();  // Recarga la página después de éxito
                        } else {
                            //handleErrorResponse(response);
                        }
                    } else {
                        alert('HTTP Error: ' + xhr.status);
                    }
                } catch (e) {
                    console.error('Error procesando la respuesta JSON:', xhr.responseText);
                    alert('Error procesando la respuesta del servidor.');
                }
            }
        };

        // Envío de datos en formato URL-encoded
        xhr.send('ownerId=' + encodeURIComponent(postData.ownerId) + '&accountNumber=' + encodeURIComponent(postData.accountNumber) + '&sinpeNumber=' + encodeURIComponent(postData.sinpeNumber));
    });
});

// Manejo de errores de respuesta
function handleErrorResponse(response) {
    switch (response.error_code) {
        case 'account_required':
            alert("Error: El número de cuenta es obligatorio.");
            break;
        case 'invalid_sinpe_number':
            alert("Error: El número de SINPE debe ser numérico.");
            break;
        case 'sinpe_format_invalid':
            alert("Error: El número de SINPE debe tener 8 dígitos.");
            break;
        case 'duplicate_entry':
            alert("Error: Entrada duplicada en la base de datos.");
            break;
        case 'db_error':
            alert("Error: Fallo en la base de datos al realizar la acción.");
            break;
        case 'number_format_bank_account':
            alert("Error: La cuenta de banco no cumple con el formato correcto (Ejm: CR12345678901234567890).");
            break;
        default:
            alert(response.message || "Error desconocido.");
            break;
    }
}

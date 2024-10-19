document.addEventListener('DOMContentLoaded', function () {
    // Create Booking
    document.getElementById('createBookingForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const numPersons = document.getElementById('numPersons').value;
        
        if (numPersons === '') {
            alert('El número de personas no puede estar vacío.');
            return;
        }

        const postData = {
            numPersons: numPersons
        };

        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/bookingAction.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.status === 'success') {
                            alert('Reserva creada exitosamente.');
                            location.reload();
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
        xhr.send('create=true&numPersons=' + encodeURIComponent(postData.numPersons));
    });
    
    
    // Delegación de Eventos para Actualización y Eliminación
    document.addEventListener('click', function (e) {
        // Actualizar Reserva con AJAX
        if (e.target && e.target.classList.contains('editBooking')) {
            e.preventDefault();

            const row = e.target.closest('tr');
            const idBooking = e.target.getAttribute('data-id');
            const numPeople = row.querySelector('.peopleBookingUpdate').value;
            const dateBooked = row.querySelector('.dateBookingUpdate').value;
            const confirmation = row.querySelector('.confirmationBookingUpdate').value;

            const postData = 'update=true&idBookingUpdate=' + encodeURIComponent(idBooking) +
                '&peopleBookingUpdate=' + encodeURIComponent(numPeople) +
                '&dateBookingUpdate=' + encodeURIComponent(dateBooked) +
                '&confirmationBookingUpdate=' + encodeURIComponent(confirmation);

            let xhr = new XMLHttpRequest();
            xhr.open('POST', '../business/bookingAction.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    try {
                        if (xhr.status === 200) {
                            alert('Reserva actualizada exitosamente.');
                            location.reload();
                        } else {
                            alert('HTTP Error: ' + xhr.status);
                        }
                    } catch (e) {
                        console.error('Invalid JSON response:', xhr.responseText);
                        alert('Error procesando la respuesta del servidor.');
                    }
                }
            };
            xhr.send(postData);
        }
    });

    // Delete Booking Confirmation
    document.querySelectorAll('.deleteBooking').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('entra');
            if (confirm('¿Estás seguro de que quieres eliminar esta reserva?')) {
                const bookingId = this.getAttribute('data-id');
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '../business/bookingAction.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        try {
                            let response = JSON.parse(xhr.responseText);
                            if (xhr.status === 200) {
                                if (response.status === 'success') {
                                    alert('Reserva eliminada exitosamente.');
                                    location.reload();
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
                xhr.send('delete=true&tbbookingid=' + encodeURIComponent(bookingId));
            }
        });
    });
});

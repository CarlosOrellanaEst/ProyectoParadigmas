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

            if (confirm('¿Estás seguro de que quieres actualizar esta reserva?')) {
                const row = e.target.closest('tr');
                console.log(row);
                const idBooking = e.target.getAttribute('data-id');
                console.log(idBooking);
                const numPeople = row.querySelector('.peopleBookingUpdate').value;
                console.log(numPeople);
                const dateBooked = row.querySelector('.dateBookingUpdate').value;
                console.log(dateBooked);
                const confirmation = row.querySelector('.confirmationBookingUpdate').value;
                console.log(confirmation);
                const idActivity = row.querySelector('.idActivityBookingUpdate').value;
                console.log(idActivity);
                const idUser = row.querySelector('.idUserBookingUpdate').value;
                console.log(idUser);
                const status = row.querySelector('.statusBookingUpdate').value;
                console.log(status);

                const postData = 'update=true&idBookingUpdate=' + encodeURIComponent(idBooking) +
                    '&peopleBookingUpdate=' + encodeURIComponent(numPeople) +
                    '&dateBookingUpdate=' + encodeURIComponent(dateBooked) +
                    '&confirmationBookingUpdate=' + encodeURIComponent(confirmation) +
                    '&idActivityBookingUpdate=' + encodeURIComponent(idActivity) +
                    '&idUserBookingUpdate=' + encodeURIComponent(idUser) +
                    '&statusBookingUpdate=' + encodeURIComponent(status);

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
        }

        // Eliminar Reserva con AJAX
        if (e.target && e.target.classList.contains('deleteBooking')) {
            e.preventDefault();

            if (confirm('¿Estás seguro de que quieres eliminar esta reserva?')) {
                const bookingId = e.target.getAttribute('data-id');
                const postData = 'delete=true&tbbookingid=' + encodeURIComponent(bookingId);

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
                xhr.send(postData);
            }
        }
    });
});

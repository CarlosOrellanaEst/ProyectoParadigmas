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
    
    // Update Booking

    document.querySelectorAll('.editBooking').forEach(function (form) {
        form.addEventListener('click', function (e) {
            console.log('entra');
            e.preventDefault();

            const tbbookingid = form.querySelector('#tbbookingid').value;
            const numPersons = form.querySelector('#numPersons').value;
            
            if (numPersons === '') {
                alert('El número de personas no puede estar vacío.');
                return;
            }

            const postData = {
                tbbookingid: tbbookingid,
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
                                alert('Reserva actualizada exitosamente.');
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
            xhr.send('update=true&tbbookingid=' + encodeURIComponent(postData.tbbookingid) + '&numPersons=' + encodeURIComponent(postData.numPersons));
        });
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

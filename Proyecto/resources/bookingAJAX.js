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
                             alert(response.message);
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
 });

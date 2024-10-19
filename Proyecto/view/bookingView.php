<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management with AJAX</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../resources/bookingAJAX.js"></script>
</head>
<body>
    <h1>Manejo de Reservas</h1>

    <?php 
        session_start();
       if (isset($_GET['idTBActivity'])) {
            // Obtener el idTBActivity que viene por parametro en la url y guardarlo en la sesión
            $_SESSION['idTBActivity'] = $_GET['idTBActivity'];
        }
    ?>

    <h2>Realizar Reserva</h2>
    <form id="createBookingForm">
        <label for="numPersons">Numero de Personas:</label>
        <input type="number" id="numPersons" name="numPersons" required>
        <br>
        <input type="radio" id="Confirmado" name="status" value="0" checked style="display:none;">

        <br>
        <button type="submit">Realizar Reserva</button>
    </form>

    <h2>Reservas</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Numero de Personas</th>
                <th>Fecha Realizada</th>
                <th>Confirmado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
                include '../business/bookingBusiness.php';
                $bookingBusiness = new bookingBusiness();
                $activityId = $_SESSION['idTBActivity'];
                $bookings = $bookingBusiness->getAllTbBookingsByActivity($activityId);
                
                foreach ($bookings as $booking) {
                    echo "<tr>";
                    echo "<td><input type='number' class='peopleBookingUpdate' value='" . $booking->getNumberPersonsTBBooking() . "'></td>";
                    echo "<td><input type='date' class='dateBookingUpdate' value='" . $booking->getBookingdate() . "' readonly></td>";
                    echo "<td><input type='text' class='confirmationBookingUpdate' value='" . $booking->getConfirmation() . "'></td>";
                    echo "<td>";
                    echo "<button type='button' class='editBooking' data-id='" . $booking->getIdTBBooking() . "'>Actualizar</button>";
                    echo "<button type='button' class='deleteBooking' data-id='" . $booking->getIdTBBooking() . "'>Eliminar</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>

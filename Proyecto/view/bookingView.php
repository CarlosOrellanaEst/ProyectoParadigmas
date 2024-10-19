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
                        
                        
                        echo "<form method='POST' action='../business/bookingAction.php'>";
        //              echo "<td><input type='hidden' name='idBookingUpdate' value='" . $booking->getIdTBBooking() . "' readonly></td>";
                        echo '<td><input type="hidden" name="idBookingUpdate" value="' . $booking->getIdTBBooking() . '" readonly></td>';
                        // echo '<td><span>' . $booking->__toString() . ' </span></td>';
                        echo "<td><input type='hidden' name='idActivityBookingUpdate' value='" . $booking->getIdTBActivity() . "'></td>";
                        echo "<td><input type='hidden' name='idUserBookingUpdate' value='" . $booking->getIdTBUser() . "'></td>";
                    echo "<tr>";
                        echo "<td><input type='number' name='peopleBookingUpdate' value='" . $booking->getNumberPersonsTBBooking() . "'></td>";
                        echo "<td><input type='date' name='dateBookingUpdate' value='" . $booking->getBookingdate() . "' readonly></td>";
                        echo "<td><input type='text' name='confirmationBookingUpdate' value='" . $booking->getConfirmation() . "'></td>";
                        echo "<td>";
                            echo "<input type='submit' name='update' class='editBooking' data-id='" . $booking->getIdTBBooking() . "' value='actualizar'>";
                            echo "<button class='deleteBooking' data-id='" . $booking->getIdTBBooking() . "'>Delete</button>";
                        echo "</td>";
                    echo "</tr>";
                }
                echo "</form>";
            ?>
        </tbody>
    </table>
</body>
</html>

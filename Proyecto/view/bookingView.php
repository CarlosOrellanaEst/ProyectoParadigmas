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
    <h1>Booking Management</h1>

    <?php 
        session_start();
       if (isset($_GET['idTBActivity'])) {
            // Obtener el idTBActivity y guardarlo en la sesión
            $_SESSION['idTBActivity'] = $_GET['idTBActivity'];
        } else {
            // Si no se pasa el id, puedes manejar el error o redirigir al usuario
        //    echo "Error: idTBActivity no proporcionado.";

            header("location: ./view/calendarView.php");
        } 
    ?>

    <h2>Create Booking</h2>
    <form id="createBookingForm">
        <label for="numPersons">Number of Persons:</label>
        <input type="number" id="numPersons" name="numPersons" required>
        <br>
        <input type="radio" id="Confirmado" name="status" value="0" checked style="display:none;">

        <br>
        <button type="submit">Create Booking</button>
    </form>

    <h2>Existing Bookings</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Activity ID</th>
                <th>User ID</th>
                <th>Number of Persons</th>
                <th>Status</th>
                <th>Booking Date</th>
                <th>Confirmation</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                include '../business/bookingBusiness.php';
                $bookingBusiness = new bookingBusiness();
                $bookings = $bookingBusiness->getAllTbBookings();
                foreach ($bookings as $booking) {
                    echo "<tr>";
                    echo "<td>" . $booking->getIdTBBooking() . "</td>";
                    echo "<td>" . $booking->getIdTBActivity() . "</td>";
                    echo "<td>" . $booking->getIdTBUser() . "</td>";
                    echo "<td>" . $booking->getNumberPersonsTBBooking() . "</td>";
                    echo "<td>" . ($booking->getStatusTBBooking() ? 'Active' : 'Inactive') . "</td>";
                    echo "<td>" . $booking->getBookingdate() . "</td>";
                    echo "<td>" . $booking->getConfirmation() . "</td>";
                    echo "<td>";
                    echo "<button class='editBooking' data-id='" . $booking->getIdTBBooking() . "'>Edit</button> ";
                    echo "<button class='deleteBooking' data-id='" . $booking->getIdTBBooking() . "'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>

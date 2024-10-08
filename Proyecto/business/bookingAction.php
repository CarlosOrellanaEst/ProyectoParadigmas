<?php
include '../business/bookingBusiness.php';
include_once '../domain/Booking.php'; 


// Comprobar si se está creando una reserva
if (isset($_POST['create'])) {
    $activityId = trim($_POST['activityId']) ;
    $numPersons = trim($_POST['numPersons']) ;
    session_start();
    $userId = $_SESSION['user']->getId();

    if ($activityId > 0 && $numPersons > 0) {
        $booking = new Booking(0, $activityId, 1, $numPersons, 1);
        $bookingBusiness = new bookingBusiness();
        $result = $bookingBusiness->insertTbBooking($booking);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Reserva creada exitosamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear la reserva.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
    }
}
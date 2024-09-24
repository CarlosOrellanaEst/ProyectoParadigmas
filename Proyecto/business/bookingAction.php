<?php
require '../business/bookingBusiness.php';
include_once '../domain/Booking.php'; 
if (isset($_POST['create'])) {
    // Recibir datos del formulario
    $activityId = isset($_POST['activityId']) ? intval($_POST['activityId']) : 0;
    $numPersons = isset($_POST['numPersons']) ? intval($_POST['numPersons']) : 0;
    $userId = $_SESSION['user']->getId(); // Obtener ID del usuario de la sesión

    // Validar que los datos sean correctos
    if ($activityId > 0 && $numPersons > 0) {
        // Crear una instancia de TbBooking
        $booking = new Booking(0, $activityId, $userId, $numPersons, 1); // 1 para indicar que la reserva está activa
        $bookingBusiness = new BookingBusiness();

        // Insertar la reserva
        $result = $bookingBusiness->insertTbBooking($booking);

        // Comprobar si la reserva se realizó con éxito
        if ($result) {
            header("location: ../view/PlannerView.php?success=inserted");
        } else {
            header("location: ../view/PlannerView.php?error=insertFailed");
        }
        exit();
    } else {
        header("location: ../view/PlannerView.php?error=noFile");
        exit();
    }
}

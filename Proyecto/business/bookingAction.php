<?php
include '../business/bookingBusiness.php';
include_once '../domain/Booking.php'; 


// Comprobar si se está creando una reserva
if (isset($_POST['create'])) {
    $activityId = trim($_POST['activityId']) ;
    $numPeople = trim($_POST['numPersons']) ;
    session_start();
    $userId = $_SESSION['user']->getId();

    if ($activityId > 0 && $numPersons > 0) { 
        $booking = new Booking(0, $activityId, $userId, $numPeople, 1, date('Y-m-d H:i:s'), 0); 
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

if (isset($_POST['update'])) {
    $activityId = trim($_POST['activityId']) ;
    $numPeople = trim($_POST['numPeople']) ;
    $dateBooked = trim($_POST['dateBooked']);
    session_start();
    $userId = $_SESSION['user']->getId();

    if ($activityId > 0 && $numPersons > 0) {
        $booking = new Booking(0, $activityId, $userId, $numPeople, 1, $dateBooked, 0); 
        $bookingBusiness = new bookingBusiness();
        $result = $bookingBusiness->updateTbBooking($booking);

        if ($result == 1) {
            header("location: ../view/bookingView.php?success=updated");
            exit();
        } else if ($result == null) {
            header("location: ../view/bookingView.php?error=alreadyexists");
            exit();
        } else {
            header("location: ../view/bookingView.php?error=dbError");
            exit();
        }
    }  else {
        header("location: ../view/bookingView.php?error=error");
        exit();
    }
}

if (isset($_POST['delete'])) { 
    if (isset($_POST['tbbookingid'])) {
        $id = $_POST['tbbookingid'];
        $bookingBusiness = new bookingBusiness();
        $result = $bookingBusiness ->deleteTbBooking($id);

        if ($result == 1) {
            header("location: ../view/touristCompanyTypeView.php?success=deleted");
        } else {
            header("location: ../view/touristCompanyTypeView.php?error=dbError");
        }
    } else {
        header("location: ../view/touristCompanyTypeView.php?error=emptyField");
    }
} 

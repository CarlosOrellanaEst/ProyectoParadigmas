<?php
include '../business/bookingBusiness.php';
include_once '../domain/Booking.php'; 
include_once '../domain/User.php'; 

session_start();

    if (isset($_POST['create'])) {
    // recibo el que venia por parametro en la url y lo leo de la sesion
    $activityId = $_SESSION['idTBActivity'];

    $numPeople = trim($_POST['numPersons']);
    $userLogged = $_SESSION['user'];
    $userId = $userLogged->getId();

    if ($activityId > 0 && $numPeople > 0) { 
        $booking = new Booking(0, $activityId, $userId, $numPeople, 1, date('Y-m-d H:i:s'), 0); 
        $bookingBusiness = new bookingBusiness(); 
        $result = $bookingBusiness->insertTbBooking($booking);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Reserva creada exitosamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear la reserva.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos invalidos.']);
    }
    }

if (isset($_POST['update'])) {
    $idBooking = trim($_POST['idBookingUpdate']);
    $activityId = trim($_POST['idActivityBookingUpdate']);
    $numPeople = trim($_POST['peopleBookingUpdate']);
    $dateBooked = trim($_POST['dateBookingUpdate']);
    $userId = trim($_POST['idUserBookingUpdate']);
    $confirmation = trim($_POST['confirmationBookingUpdate']);

    if ($idBooking > 0 && $activityId > 0 && $numPeople > 0 && $dateBooked != null && $userId > 0 && $confirmation != null) {
        $booking = new Booking($idBooking, $activityId, $userId, $numPeople, 1, $dateBooked, $confirmation); 
        $bookingBusiness = new bookingBusiness();
        $result = $bookingBusiness->updateTbBooking($booking);

        if ($result == 1) {
            echo json_encode(['status' => 'success', 'message' => 'Reserva actualizada exitosamente.']);
        } else if ($result == null) {
            echo json_encode(['status' => 'error', 'message' => 'La reserva ya existe.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
    }
}

    if (isset($_POST['delete'])) { 
        if (isset($_POST['tbbookingid'])) {
            $id = $_POST['tbbookingid'];
            $bookingBusiness = new bookingBusiness();
            $result = $bookingBusiness->deleteTbBooking($id);
    
            if ($result == 1) {
                echo json_encode(['status' => 'success', 'message' => 'Reserva eliminada exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la reserva.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Campo vacío.']);
        }
    }

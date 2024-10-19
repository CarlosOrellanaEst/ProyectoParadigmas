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
         $booking = new Booking($idBooking, $activityId, $userId, $numPeople, 1, $dateBooked,  $confirmation); 
        //  echo ($booking -> __toString());
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

// if (isset($_POST['delete'])) { 
//     if (isset($_POST['tbbookingid'])) {
//         $id = $_POST['tbbookingid'];
//         $bookingBusiness = new bookingBusiness();
//         $result = $bookingBusiness ->deleteTbBooking($id);

//         if ($result == 1) {
//             header("location: ../view/touristCompanyTypeView.php?success=deleted");
//         } else {
//             header("location: ../view/touristCompanyTypeView.php?error=dbError");
//         }
//     } else {
//         header("location: ../view/touristCompanyTypeView.php?error=emptyField");
//     }
// } 

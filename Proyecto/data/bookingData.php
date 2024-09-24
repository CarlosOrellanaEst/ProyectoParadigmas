<?php

include_once 'data.php';
include_once '../domain/Booking.php';

class bookingData extends Data {

    public function insertTbBooking($booking) {
        // Conexión a la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8mb4');
    
        // Obtener el último ID de la tabla tbbooking
        $queryGetLastId = "SELECT MAX(tbbookingid) AS tbbookingid FROM tbbooking";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }
    
        // Inserción en la tabla tbbooking
        $queryInsert = "INSERT INTO tbbooking (tbbookingid, tbactivityid, tbuserid, tbbookingnumberpersons, tbbookingstatus) 
                        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        // Preparación de parámetros para la inserción
        $tbbookingid = $nextId;
        $tbactivityid = $booking->getTbactivityid(); // Obtiene el ID de la actividad
        $tbuserid = $booking->getTbuserid(); // Obtiene el ID del usuario
        $tbbookingnumberpersons = $booking->getTbbookingNumberPersons(); // Número de personas en la reserva
        $tbbookingstatus = $booking->getTbbookingStatus(); // Estado de la reserva
    
        // Bindeo de parámetros e inserción
        $stmt->bind_param("iiiii", $tbbookingid, $tbactivityid, $tbuserid, $tbbookingnumberpersons, $tbbookingstatus);
        $result = $stmt->execute();
    
        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }
    
}

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
        $tbactivityid = $booking->getIdTBActivity(); // Obtiene el ID de la actividad
        $tbuserid = $booking->getIdTBUser(); // Obtiene el ID del usuario
        $tbbookingnumberpersons = $booking->getNumberPersonsTBBooking(); // Número de personas en la reserva
        $tbbookingstatus = true // Estado de la reserva (true=1, false=0)

        $stmt->bind_param("iiiii", $tbbookingid, $tbactivityid, $tbuserid, $tbbookingnumberpersons, $tbbookingstatus);
    
        // Ejecutar la consulta y verificar si tuvo éxito
        if (!$stmt->execute()) {
            echo "Error al insertar la reserva: " . $stmt->error;
            $stmt->close();
            mysqli_close($conn);
            return false;
        }
        
        $stmt->close();
        mysqli_close($conn);
    
        return true;
    }
}

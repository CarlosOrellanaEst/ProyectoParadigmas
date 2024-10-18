<?php

include_once 'data.php';
include_once '../domain/Booking.php';

class bookingData extends Data {

    public function insertTbBooking($booking): bool {
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
        $queryInsert = "INSERT INTO tbbooking (tbbookingid, tbactivityid, tbuserid, tbbookingnumberpersons, tbbookingstatus, tbbookingdate, tbbookingconfirmation) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        // Preparación de parámetros para la inserción
        $tbbookingid = $nextId;
        $tbactivityid = $booking->getIdTBActivity(); // Obtiene el ID de la actividad
        $tbuserid = $booking->getIdTBUser(); // Obtiene el ID del usuario
        $tbbookingnumberpersons = $booking->getNumberPersonsTBBooking(); // Número de personas en la reserva
        $tbbookingstatus = $booking->getStatusTBBooking(); // Estado de la reserva
        $tbbookingdate = $booking->getBookingdate(); // Fecha de la reserva
        $tbbookingconfirmation = $booking->getConfirmation(); // Confirmación de la reserva


        // Bindeo de parámetros e inserción
        $stmt->bind_param("iiiiisi", $tbbookingid, $tbactivityid, $tbuserid, $tbbookingnumberpersons, $tbbookingstatus, $tbbookingdate, $tbbookingconfirmation);
        $result = $stmt->execute();
    
        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }

    public function deleteTbBooking($bookingId): bool {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $conn->set_charset('utf8mb4');

        $queryDelete = "DELETE FROM tbbooking WHERE tbbookingid = ?";
        $stmt = $conn->prepare($queryDelete);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $bookingId);
        $result = $stmt->execute();

        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }

        $stmt->close();
        mysqli_close($conn);

        return $result;
    }

    public function updateTbBooking($booking): bool {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $conn->set_charset('utf8mb4');

        $queryUpdate = "UPDATE tbbooking SET tbactivityid = ?, tbuserid = ?, tbbookingnumberpersons = ?, tbbookingstatus = ?, tbbookingdate = ?, tbbookingconfirmation = ? WHERE tbbookingid = ?";
        $stmt = $conn->prepare($queryUpdate);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $tbactivityid = $booking->getIdTBActivity();
        $tbuserid = $booking->getIdTBUser();
        $tbbookingnumberpersons = $booking->getNumberPersonsTBBooking();
        $tbbookingstatus = $booking->getStatusTBBooking();
        $tbbookingdate = $booking->getBookingdate();
        $tbbookingconfirmation = $booking->getConfirmation();
        $tbbookingid = $booking->getIdTBBooking();

        $stmt->bind_param("iiiissi", $tbactivityid, $tbuserid, $tbbookingnumberpersons, $tbbookingstatus, $tbbookingdate, $tbbookingconfirmation, $tbbookingid);
        $result = $stmt->execute();

        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }

        $stmt->close();
        mysqli_close($conn);

        return $result;
    }

    public function getAllTbBookings(): array {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $conn->set_charset('utf8mb4');

        $querySelect = "SELECT * FROM tbbooking";
        $result = mysqli_query($conn, $querySelect);
        $bookings = array();

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $booking = new Booking(
                    $row['tbbookingid'],
                    $row['tbactivityid'],
                    $row['tbuserid'],
                    $row['tbbookingnumberpersons'],
                    $row['tbbookingstatus'],
                    $row['tbbookingdate'],
                    $row['tbbookingconfirmation']
                );
                array_push($bookings, $booking);
            }
        } else {
            echo "Query failed: " . mysqli_error($conn);
        }

        mysqli_close($conn);

        return $bookings;
    }

    public function getTbBookingById($bookingId): Booking {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $conn->set_charset('utf8mb4');

        $querySelect = "SELECT * FROM tbbooking WHERE tbbookingid = ?";
        $stmt = $conn->prepare($querySelect);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $bookingId);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking = null;

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $booking = new Booking(
                $row['tbbookingid'],
                $row['tbactivityid'],
                $row['tbuserid'],
                $row['tbbookingnumberpersons'],
                $row['tbbookingstatus'],
                $row['tbbookingdate'],
                $row['tbbookingconfirmation']
            );
        }

        $stmt->close();
        mysqli_close($conn);

        return $booking;
    }
    
}

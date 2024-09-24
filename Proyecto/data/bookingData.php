<?php

include_once 'data.php';
include_once '../domain/Booking.php';

class bookingData extends Data {

    // Método para insertar una nueva actividad
    public function insertTbBooking($booking) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8mb4');
    
        // Revisión de si ya existe una actividad con el mismo nombre y activa
        $tbactivityname = $activity->getNameTBActivity();
        $checkQuery = "SELECT COUNT(*) FROM tbactivity WHERE tbactivityname = ? AND tbactivitystatus = 1";
        $stmtCheck = $conn->prepare($checkQuery);
        if ($stmtCheck === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmtCheck->bind_param("s", $tbactivityname);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();
    
        if ($count > 0) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Ya existe una actividad con el mismo nombre y está activa.'];
        }
    
        // Obtener el último ID
        $queryGetLastId = "SELECT MAX(tbactivityid) AS tbactivityid FROM tbactivity";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }
  
        // Inserción de la actividad
        $queryInsert = "INSERT INTO tbactivity (tbactivityid, tbactivityname, tbactivityservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus, tbactivitydate) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        // Preparación de parámetros
        $tbactivityid = $nextId;
        $tbServicesid = $activity->getTbservicecompanyid();
        $tbactivityatributearray = implode(",", $activity->getAttributeTBActivityArray());
        $tbactivitydataarray = implode(",", $activity->getDataAttributeTBActivityArray());
        $imageUrls = is_array($activity->getTbactivityURL()) ? implode(',', $activity->getTbactivityURL()) : $activity->getTbactivityURL();
        $tbactivitystatus = $activity->getStatusTBActivity();
        $tbactivitydate = $activity->getActivityDate();
    
        // Bindeo de parámetros e inserción
        $stmt->bind_param("isisssis", $tbactivityid, $tbactivityname, $tbServicesid, $tbactivityatributearray, $tbactivitydataarray, $imageUrls, $tbactivitystatus, $tbactivitydate);
        $result = $stmt->execute();
    
        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }
}

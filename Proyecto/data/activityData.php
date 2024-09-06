<?php

include_once 'data.php';
include_once '../domain/activity.php';

class ActivityData extends Data {

    public function insertActivity($activity) {

        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8mb4');
    
        // Verificar si la actividad ya existe
        if ($this->getActivityByName($activity->getNameTBActivity())) {
            return null; // La actividad ya existe
        }
    
        // Obtener el próximo ID
        $queryGetLastId = "SELECT MAX(tbactivityid) AS tbactivityid FROM tbactivity";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }
    
        // Insertar la actividad
        $queryInsert = "INSERT INTO tbactivity (tbactivityid, tbactivityname, tbactivityatributearray, tbactivitydataarray, tbactivitystatus) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $tbactivityid = $nextId;
        $tbactivityname = $activity->getNameTBActivity();
        
        // Los valores deben estar separados por comas
        $tbactivityatributearray = implode(",", $activity->getAttributeTBActivityArray());
        $tbactivitydataarray = implode(",", $activity->getDataAttributeTBActivityArray());
        
        $tbactivitystatus = $activity->getStatusTBActivity();
    
        // Vinculación de parámetros
        $stmt->bind_param("isssi", $tbactivityid, $tbactivityname, $tbactivityatributearray, $tbactivitydataarray, $tbactivitystatus);
        $result = $stmt->execute();
    
        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }
    
    public function getAllActivities() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');
    
        $query = "SELECT * FROM tbactivity WHERE tbactivitystatus = 1;";
        $result = mysqli_query($conn, $query);
    
        $activities = array();
    
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            // Separar los atributos y datos por comas y convertirlos en arrays
            $attributeArray = explode(',', $row['tbactivityatributearray']);
            $dataArray = explode(',', $row['tbactivitydataarray']);
    
            // Verificar si ambos arrays tienen la misma longitud
            if (count($attributeArray) !== count($dataArray)) {
                // Manejar el error según sea necesario, por ejemplo, omitir la actividad
                continue;
            }
    
            // Crear la instancia de Activity
            $activity = new Activity(
                $row['tbactivityid'],
                $row['tbactivityname'],
                $attributeArray,  // Pasar los arrays de atributos
                $dataArray,       // Pasar los arrays de datos
                $row['tbactivitystatus']
            );
    
            $activities[] = $activity;
        }
    
        mysqli_close($conn);
    
        return $activities;
    }
    
    

    public function deleteActivity($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');

        $query = "UPDATE tbactivity SET tbactivitystatus=0 WHERE tbactivityid=?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $id);

        $result = $stmt->execute();

        $stmt->close();
        mysqli_close($conn);

        return $result;
    }

    public function updateActivity($activity) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');
    
        // Convertir arrays a cadenas separadas por comas
        $attributes = implode(',', $activity->getAttributeTBActivityArray());
        $dataAttributes = implode(',', $activity->getDataAttributeTBActivityArray());
    
        $query = "UPDATE tbactivity SET tbactivityname=?, tbactivityatributearray=?, tbactivitydataarray=?, tbactivitystatus=? WHERE tbactivityid=?";
    
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $tbactivityid = $activity->getIdTBActivity();
        $tbactivityname = $activity->getNameTBActivity();
        $tbactivityatributearray = $attributes;
        $tbactivitydataarray = $dataAttributes;
        $tbactivitystatus = $activity->getStatusTBActivity();
    
        $stmt->bind_param("sssii", $tbactivityname, $tbactivityatributearray, $tbactivitydataarray, $tbactivitystatus, $tbactivityid);
    
        $result = $stmt->execute();
        if ($result === false) {
            die("Execute failed: " . $stmt->error);
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }
    

    public function getActivityById($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');

        $query = "SELECT * FROM tbactivity WHERE tbactivityid=?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->bind_result($tbactivityid, $tbactivityname, $tbactivityatributearray, $tbactivitydataarray, $tbactivitystatus);

        $stmt->fetch();

        $activity = new Activity($tbactivityid, $tbactivityname, $tbactivityatributearray, $tbactivitydataarray, $tbactivitystatus);

        $stmt->close();
        mysqli_close($conn);

        return $activity;
    }

    public function getActivityByName($activityName) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');

        $query = "SELECT tbactivityid, tbactivityname, tbactivityatributearray, tbactivitydataarray, tbactivitystatus FROM tbactivity WHERE tbactivityname=?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $activityName);
        $stmt->execute();
        $stmt->bind_result($tbactivityid, $tbactivityname, $tbactivityatributearray, $tbactivitydataarray, $tbactivitystatus);

        $activity = null;
        if ($stmt->fetch()) {
            $activity = new Activity($tbactivityid, $tbactivityname, $tbactivityatributearray, $tbactivitydataarray, $tbactivitystatus);
        }

        $stmt->close();
        mysqli_close($conn);

        return $activity;
    }
}

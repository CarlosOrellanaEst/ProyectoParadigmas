<?php

include_once 'data.php';
include_once '../domain/Activity.php';

class activityData extends Data {

    // Método para insertar una nueva actividad
    public function insertActivity($activity) {
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
    
    // Método para obtener todas las actividades activas
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
            $attributeArray = explode(',', $row['tbactivityatributearray']);
            $dataArray = explode(',', $row['tbactivitydataarray']);
            $urlArray = explode(',', $row['tbactivityurl']);
    
            if (count($attributeArray) !== count($dataArray)) {
                continue;
            }

            $activity = new Activity(
                $row['tbactivityid'],
                $row['tbactivityname'],
                $row['tbactivityservicecompanyid'], 
                $attributeArray, 
                $dataArray,       
                $urlArray,
                $row['tbactivitystatus'],
                $row['tbactivitydate']
            );
    
            $activities[] = $activity;
        }
    
        mysqli_close($conn);
        return $activities;
    }

    // Método para eliminar una actividad (marcar como inactiva)
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

    // Método para actualizar una actividad
    public function updateActivity($activity) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8mb4');
    
        // Revisión de duplicados
        $tbactivityname = $activity->getNameTBActivity();
        $tbactivityid = $activity->getIdTBActivity();
    
        $checkQuery = "SELECT COUNT(*) FROM tbactivity WHERE tbactivityname = ? AND tbactivityid != ? AND tbactivitystatus = 1";
        $stmtCheck = $conn->prepare($checkQuery);
        if ($stmtCheck === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmtCheck->bind_param("si", $tbactivityname, $tbactivityid);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();
    
        if ($count > 0) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Ya existe una actividad con el mismo nombre y está activa.'];
        }
   
        // Actualización de la actividad
        $queryUpdate = "UPDATE tbactivity
                        SET tbactivityname = ?, tbactivityservicecompanyid = ?, tbactivityatributearray = ?, tbactivitydataarray = ?, tbactivityurl = ?, tbactivitystatus = ?, tbactivitydate = ?
                        WHERE tbactivityid = ?";
        $stmt = $conn->prepare($queryUpdate);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $tbServicesid = $activity->getTbservicecompanyid();
        $tbactivityatributearray = is_array($activity->getAttributeTBActivityArray()) ? implode(",", $activity->getAttributeTBActivityArray()) : '';
        $tbactivitydataarray = is_array($activity->getDataAttributeTBActivityArray()) ? implode(",", $activity->getDataAttributeTBActivityArray()) : '';
        $imageUrls = is_array($activity->getTbactivityURL()) ? implode(',', $activity->getTbactivityURL()) : $activity->getTbactivityURL();
        $tbactivitystatus = $activity->getStatusTBActivity();
        $tbactivitydate = $activity->getActivityDate();  // Asegurar de actualizar la fecha también
    
        $stmt->bind_param("sisssisi", $tbactivityname, $tbServicesid, $tbactivityatributearray, $tbactivitydataarray, $imageUrls, $tbactivitystatus, $tbactivitydate, $tbactivityid);
        $result = $stmt->execute();
    
        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }

    // Método para obtener una actividad por su ID
    public function getActivityById($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');
   
        $query = "SELECT tbactivityid, tbactivityname, tbactivityservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus, tbactivitydate 
        FROM tbactivity 
        WHERE tbactivityid = ? AND tbactivitystatus = 1";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        $stmt->bind_result($tbactivityid, $tbactivityname, $tbactivityservicecompanyid, $tbactivityatributearray, $tbactivitydataarray, $tbactivityurl, $tbactivitystatus, $tbactivitydate);
    
        $activity = null;
    
        if ($stmt->fetch()) {
            $attributeArray = explode(',', $tbactivityatributearray);
            $dataArray = explode(',', $tbactivitydataarray);
            $urlArray = explode(',', $tbactivityurl);
    
            if (count($attributeArray) === count($dataArray)) {
                $activity = new Activity(
                    $tbactivityid, 
                    $tbactivityname, 
                    $tbactivityservicecompanyid, 
                    $attributeArray, 
                    $dataArray, 
                    $urlArray, 
                    $tbactivitystatus,
                    $tbactivitydate // Incluir la fecha en el constructor
                );
            }
        }

        $stmt->close();
        mysqli_close($conn);
    
        return $activity;
    }

    // Método para obtener una actividad por su nombre
    public function getActivityByName($activityName) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');

        $query = "SELECT tbactivityid, tbactivityname, tbactivityservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus, tbactivitydate 
                  FROM tbactivity 
                  WHERE tbactivityname = ? AND tbactivitystatus = 1";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $activityName);
        $stmt->execute();
        $stmt->bind_result($tbactivityid, $tbactivityname, $tbactivityservicecompanyid, $tbactivityatributearray, $tbactivitydataarray, $tbactivityurl, $tbactivitystatus, $tbactivitydate);

        $activity = null;

        if ($stmt->fetch()) {
            $attributeArray = explode(',', $tbactivityatributearray);
            $dataArray = explode(',', $tbactivitydataarray);
            $urlArray = explode(',', $tbactivityurl);

            $activity = new Activity(
                $tbactivityid, 
                $tbactivityname, 
                $tbactivityservicecompanyid, 
                $attributeArray, 
                $dataArray, 
                $urlArray, 
                $tbactivitystatus,
                $tbactivitydate // Incluir la fecha en el constructor
            );
        }

        $stmt->close();
        mysqli_close($conn);

        return $activity;
    }

    // Método para eliminar una imagen de la actividad
    public function removeImageFromActivity($activityId, $newImageUrls) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');

        if (is_array($newImageUrls)) {
            $newImageUrlsString = implode(',', $newImageUrls);
        } else {
            $newImageUrlsString = $newImageUrls;
        }

        $query = "UPDATE tbactivity SET tbactivityurl = ? WHERE tbactivityid = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("si", $newImageUrlsString, $activityId);
        $result = $stmt->execute();

        $stmt->close();
        mysqli_close($conn);

        return $result;
    }

    // Método para verificar si una imagen está en uso
    public function isImageInUse($imageToDelete) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');

        $query = "SELECT COUNT(*) FROM tbactivity WHERE tbactivityurl LIKE ?";
        $imageToDeletePattern = '%' . $imageToDelete . '%'; // Agregar comodines para la búsqueda

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $imageToDeletePattern);
        $stmt->execute();

        $stmt->bind_result($count);
        $stmt->fetch();

        $stmt->close();
        mysqli_close($conn);

        return $count > 0;
    }

    // Método para obtener actividades por día
    public function getActivitiesByDay($date) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');

        $query = "SELECT * FROM tbactivity WHERE DATE(tbactivitydate) = ? AND tbactivitystatus = 1;";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $activities = array();

        while ($row = $result->fetch_assoc()) {
            $attributeArray = explode(',', $row['tbactivityatributearray']);
            $dataArray = explode(',', $row['tbactivitydataarray']);
            $urlArray = explode(',', $row['tbactivityurl']);

            if (count($attributeArray) !== count($dataArray)) {
                continue;
            }

            $activity = new Activity(
                $row['tbactivityid'],
                $row['tbactivityname'],
                $row['tbactivityservicecompanyid'],
                $attributeArray,
                $dataArray,
                $urlArray,
                $row['tbactivitystatus'],
                $row['tbactivitydate']
            );

            $activities[] = $activity;
        }

        mysqli_close($conn);
        return $activities;
    }

    // Método para obtener actividades por semana
    public function getActivitiesByWeek($date) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');

        $query = "SELECT * FROM tbactivity WHERE YEARWEEK(tbactivitydate, 1) = YEARWEEK(?, 1) AND tbactivitystatus = 1;";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $activities = array();

        while ($row = $result->fetch_assoc()) {
            $attributeArray = explode(',', $row['tbactivityatributearray']);
            $dataArray = explode(',', $row['tbactivitydataarray']);
            $urlArray = explode(',', $row['tbactivityurl']);

            if (count($attributeArray) !== count($dataArray)) {
                continue;
            }

            $activity = new Activity(
                $row['tbactivityid'],
                $row['tbactivityname'],
                $row['tbactivityservicecompanyid'],
                $attributeArray,
                $dataArray,
                $urlArray,
                $row['tbactivitystatus'],
                $row['tbactivitydate']
            );

            $activities[] = $activity;
        }

        mysqli_close($conn);
        return $activities;
    }

    // Método para obtener actividades por mes
    public function getActivitiesByMonth($date) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');

        $query = "SELECT * FROM tbactivity WHERE YEAR(tbactivitydate) = YEAR(?) AND MONTH(tbactivitydate) = MONTH(?) AND tbactivitystatus = 1;";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $date, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        $activities = array();

        while ($row = $result->fetch_assoc()) {
            $attributeArray = explode(',', $row['tbactivityatributearray']);
            $dataArray = explode(',', $row['tbactivitydataarray']);
            $urlArray = explode(',', $row['tbactivityurl']);

            if (count($attributeArray) !== count($dataArray)) {
                continue;
            }

            $activity = new Activity(
                $row['tbactivityid'],
                $row['tbactivityname'],
                $row['tbactivityservicecompanyid'],
                $attributeArray,
                $dataArray,
                $urlArray,
                $row['tbactivitystatus'],
                $row['tbactivitydate']
            );

            $activities[] = $activity;
        }

        mysqli_close($conn);
        return $activities;
    }
}



        
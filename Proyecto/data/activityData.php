<?php

include_once 'data.php';
include_once '../domain/Activity.php';

class activityData extends Data {

    public function insertActivity($activity) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8mb4');
    
        // Verificar si la actividad ya existe con estado 1
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
    
        // Si ya existe una actividad con el mismo nombre y estado 1, retornamos un mensaje de error
        if ($count > 0) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Ya existe una actividad con el mismo nombre y está activa.'];
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
        $queryInsert = "INSERT INTO tbactivity (tbactivityid, tbactivityname, tbservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $tbactivityid = $nextId;
        $tbServicesid = $activity->getTbservicecompanyid();
        $tbactivityatributearray = implode(",", $activity->getAttributeTBActivityArray());
        $tbactivitydataarray = implode(",", $activity->getDataAttributeTBActivityArray());
        $imageUrls = is_array($activity->getTbactivityURL()) ? implode(',', $activity->getTbactivityURL()) : $activity->getTbactivityURL();
        $tbactivitystatus = $activity->getStatusTBActivity();
    
        $stmt->bind_param("isisssi", $tbactivityid, $tbactivityname, $tbServicesid, $tbactivityatributearray, $tbactivitydataarray, $imageUrls, $tbactivitystatus);
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
                $row['tbactivityurl'],
                $row['tbactivitystatus']
            );
            $photoUrls = explode(',', $row['tbactivityurl']);
            $activity->setTbactivityURL(array_map('trim', $photoUrls)); 
    
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
    
        // Verificar si otra actividad con el mismo nombre ya existe y está activa
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
    
        // Si ya existe una actividad con el mismo nombre y estado 1, retornar un mensaje de error
        if ($count > 0) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Ya existe una actividad con el mismo nombre y está activa.'];
        }
    
        // Preparar la consulta de actualización
        $queryUpdate = "UPDATE tbactivity
                        SET tbactivityname = ?, tbservicecompanyid = ?, tbactivityatributearray = ?, tbactivitydataarray = ?, tbactivityurl = ?, tbactivitystatus = ?
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
    
        // Vinculación de parámetros
        $stmt->bind_param("sisssii", $tbactivityname, $tbServicesid, $tbactivityatributearray, $tbactivitydataarray, $imageUrls, $tbactivitystatus, $tbactivityid);
        $result = $stmt->execute();
    
        if (!$result) {
            echo "Execute failed: " . $stmt->error;
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
    
        // Consulta para seleccionar la actividad por ID
        $query = "SELECT tbactivityid, tbactivityname, tbservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus 
                  FROM tbactivity 
                  WHERE tbactivityid = ? AND tbactivitystatus = 1";
    
        // Preparar la consulta
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        // Vincular el parámetro
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        // Vincular los resultados
        $stmt->bind_result($tbactivityid, $tbactivityname, $tbservicecompanyid, $tbactivityatributearray, $tbactivitydataarray, $tbactivityurl, $tbactivitystatus);
    
        // Inicializar la variable $activity
        $activity = null;
    
        // Obtener los datos
        if ($stmt->fetch()) {
            // Si se encuentra la actividad, crear una instancia del objeto Activity
            $attributeArray = explode(',', $tbactivityatributearray);
            $dataArray = explode(',', $tbactivitydataarray);
            $urlArray = explode(',', $tbactivityurl);
    
            // Asegurarse de que los arrays de atributos y datos tengan la misma longitud
            if (count($attributeArray) === count($dataArray)) {
                $activity = new Activity(
                    $tbactivityid, 
                    $tbactivityname, 
                    $tbservicecompanyid, 
                    $attributeArray, 
                    $dataArray, 
                    $urlArray, 
                    $tbactivitystatus
                );
            }
        }
    
        // Cerrar el statement y la conexión
        $stmt->close();
        mysqli_close($conn);
    
        // Retornar la actividad o null si no se encuentra
        return $activity;
    }
    

public function getActivityByName($activityName) {
    // Establecer conexión a la base de datos
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn->set_charset('utf8mb4');

    // Consulta SQL que incluye los nuevos campos (tbservicecompanyid, tbactivityurl)
    $query = "SELECT tbactivityid, tbactivityname, tbservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus 
              FROM tbactivity 
              WHERE tbactivityname = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Vincular el parámetro
    $stmt->bind_param("s", $activityName);
    $stmt->execute();

    // Vincular los resultados
    $stmt->bind_result($tbactivityid, $tbactivityname, $tbservicecompanyid, $tbactivityatributearray, $tbactivitydataarray, $tbactivityurl, $tbactivitystatus);

    // Inicializar la variable $activity
    $activity = null;

    // Obtener los datos
    if ($stmt->fetch()) {
        // Si se encuentra la actividad, se crea una instancia del objeto Activity
        // Asegúrate de que el constructor de Activity acepte estos parámetros
        $activity = new Activity($tbactivityid, $tbactivityname, $tbservicecompanyid, $tbactivityatributearray, $tbactivitydataarray, $tbactivityurl, $tbactivitystatus);
    }

    // Cerrar el statement y la conexión
    $stmt->close();
    mysqli_close($conn);

    // Retornar la actividad o null si no se encuentra
    return $activity;
}

public function removeImageFromActivity($activityId, $newImageUrls){
    // Establecer conexión a la base de datos
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn->set_charset('utf8mb4');

    // Verificar si $newImageUrls es un array, si es así, convertirlo en cadena
    if (is_array($newImageUrls)) {
        $newImageUrlsString = implode(',', $newImageUrls);
    } else {
        // Si ya es una cadena, úsalo directamente
        $newImageUrlsString = $newImageUrls;
    }

    // Consulta SQL para actualizar la URL de la imagen
    $query = "UPDATE tbactivity SET tbactivityurl = ? WHERE tbactivityid = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("si", $newImageUrlsString, $activityId);
    $result = $stmt->execute();

    // Cerrar el statement y la conexión
    $stmt->close();
    mysqli_close($conn);

    // Retornar el resultado de la operación
    return $result;
}


public function isImageInUse($imageToDelete){
    // Establecer conexión a la base de datos
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn->set_charset('utf8mb4');

    // Consulta SQL para verificar si la imagen está en uso
    $query = "SELECT COUNT(*) FROM tbactivity WHERE tbactivityurl LIKE ?";

    // Preparar la consulta
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Vincular el parámetro
    $stmt->bind_param("s", $imageToDelete);
    $stmt->execute();

    // Vincular el resultado
    $stmt->bind_result($count);

    // Obtener el resultado
    $stmt->fetch();

    // Cerrar el statement y la conexión
    $stmt->close();
    mysqli_close($conn);

    // Retornar true si la imagen está en uso, false si no lo está
    return $count > 0;
}

}

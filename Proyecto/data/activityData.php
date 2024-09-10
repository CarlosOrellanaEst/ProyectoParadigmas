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
        $queryInsert = "INSERT INTO tbactivity (tbactivityid, tbactivityname, tbservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus) VALUES (?, ?, ?, ?, ?,?,?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $tbactivityid = $nextId;
        $tbactivityname = $activity->getNameTBActivity();
        
        $tbServicesid = $activity->getTbservicecompanyid();
        // Los valores deben estar separados por comas
        $tbactivityatributearray = implode(",", $activity->getAttributeTBActivityArray());
        $tbactivitydataarray = implode(",", $activity->getDataAttributeTBActivityArray());
        $imageUrls = $activity->getTbactivityURL();

    
        if (is_array($imageUrls)) {
            $imageUrlsString = implode(',', $imageUrls);
        } else {
            $imageUrlsString = $imageUrls;
        }
        
      
        $tbactivitystatus = $activity->getStatusTBActivity();
    
        // Vinculación de parámetros
        $stmt->bind_param("isisssi", $tbactivityid, $tbactivityname,$tbServicesid, $tbactivityatributearray, $tbactivitydataarray, $imageUrlsString, $tbactivitystatus);
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
    
        // Preparar la consulta de actualización
        $queryUpdate = "UPDATE tbactivity
                        SET tbactivityname = ?, tbservicecompanyid = ?, tbactivityatributearray = ?, tbactivitydataarray = ?, tbactivityurl = ?, tbactivitystatus = ?
                        WHERE tbactivityid = ?";
        $stmt = $conn->prepare($queryUpdate);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        // Obtener los valores del objeto $activity
        $tbactivityid = $activity->getIdTBActivity(); // ID para actualizar
        $tbactivityname = $activity->getNameTBActivity();
        $tbServicesid = $activity->getTbservicecompanyid();
    
        // Verificar si los atributos son arrays y convertirlos en cadenas separadas por comas
        $tbactivityatributearray = is_array($activity->getAttributeTBActivityArray()) ? implode(",", $activity->getAttributeTBActivityArray()) : '';
        $tbactivitydataarray = is_array($activity->getDataAttributeTBActivityArray()) ? implode(",", $activity->getDataAttributeTBActivityArray()) : '';
    
        // Verificar si las URLs son arrays y convertirlas en cadenas separadas por comas
        $imageUrls = $activity->getTbactivityURL();
        if (is_array($imageUrls)) {
            $imageUrlsString = implode(',', $imageUrls);
        } else {
            // Si $imageUrls ya es una cadena, úsalo tal como está
            $imageUrlsString = $imageUrls;
        }
    
        $tbactivitystatus = $activity->getStatusTBActivity();
    
        // Vinculación de parámetros
        // "sisssii" porque el nombre es cadena ("s"), el ID del servicio es entero ("i"), y las URLs, atributos y datos son cadenas ("s")
        $stmt->bind_param("sisssii", $tbactivityname, $tbServicesid, $tbactivityatributearray, $tbactivitydataarray, $imageUrlsString, $tbactivitystatus, $tbactivityid);
        
        $result = $stmt->execute();
    
        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }
    
    
    
    
/*
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
*/
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

}

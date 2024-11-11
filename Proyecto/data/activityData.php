<?php

include_once 'data.php';
include_once '../domain/Activity.php';

class activityData extends Data
{

    // Método para insertar una nueva actividad
    public function insertActivity($activity)
    {
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
        $queryInsert = "INSERT INTO tbactivity (tbactivityid, tbactivityname, tbactivityservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus, tbactivitydate, tbactivitylatitude	, tbactivitylongitude) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        // Preparación de parámetros
        $tbactivityid = $nextId;
        $tbServicesid = $activity->getTbservicecompanyid();
        
        // Conversión de los arrays a cadenas separadas por comas
        $tbactivityatributearray = is_array($activity->getAttributeTBActivityArray()) ? implode(",", $activity->getAttributeTBActivityArray()) : $activity->getAttributeTBActivityArray();
        $tbactivitydataarray = is_array($activity->getDataAttributeTBActivityArray()) ? implode(",", $activity->getDataAttributeTBActivityArray()) : $activity->getDataAttributeTBActivityArray();
        
        $imageUrls = is_array($activity->getTbactivityURL()) ? implode(',', $activity->getTbactivityURL()) : $activity->getTbactivityURL();
        $tbactivitystatus = $activity->getStatusTBActivity();
        $tbactivitydate = $activity->getActivityDate();
        $latitude = $activity->getLatitude();
        $longitude = $activity->getLongitude();
    
        // Bindeo de parámetros e inserción
        $stmt->bind_param("isisssssdd", $tbactivityid, $tbactivityname, $tbServicesid, $tbactivityatributearray, $tbactivitydataarray, $imageUrls, $tbactivitystatus, $tbactivitydate, $latitude, $longitude);
        $result = $stmt->execute();
    
        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }
    

    // Método para obtener todas las actividades activas
    public function getAllActivities()
    {
        // Conexión a la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $conn->set_charset('utf8mb4');

        // Consulta para obtener todas las actividades activas
        $query = "SELECT tbactivityid, tbactivityname, tbactivityservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus, tbactivitydate, tbactivitylatitude, tbactivitylongitude 
                  FROM tbactivity WHERE tbactivitystatus = 1";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            echo "Query failed: " . mysqli_error($conn);
            mysqli_close($conn);
            return null;
        }

        // Array para almacenar las actividades
        $activities = [];

        // Recorrer los resultados
        while ($row = mysqli_fetch_assoc($result)) {
            // Convertir los atributos de cadena a arrays
            $atributearray = explode(',', $row['tbactivityatributearray']);
            $dataarray = explode(',', $row['tbactivitydataarray']);
            $urls = explode(',', $row['tbactivityurl']);
            
            // Crear un objeto de actividad o array asociativo
            $activity = [
                'tbactivityid' => $row['tbactivityid'],
                'tbactivityname' => $row['tbactivityname'],
                'tbactivityservicecompanyid' => $row['tbactivityservicecompanyid'],
                'tbactivityatributearray' => $atributearray,
                'tbactivitydataarray' => $dataarray,
                'tbactivityurl' => $urls,
                'tbactivitystatus' => $row['tbactivitystatus'],
                'tbactivitydate' => $row['tbactivitydate'],
                'tbactivitylatitude' => $row['tbactivitylatitude'],
                'tbactivitylongitude' => $row['tbactivitylongitude']
            ];

            // Añadir la actividad al array
            $activities[] = $activity;
        }

        // Cerrar la conexión
        mysqli_free_result($result);
        mysqli_close($conn);

        return $activities;
    }

    public function getAllValuesPerAttribute($attribute)
    {
        // Conexión a la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8mb4');
    
        // Obtenemos todas las actividades activas
        $query = "SELECT tbactivityatributearray, tbactivitydataarray FROM tbactivity WHERE tbactivitystatus = 1";
        
        $result = mysqli_query($conn, $query);
    
        if (!$result) {
            echo "Query failed: " . mysqli_error($conn);
            mysqli_close($conn);
            return null;
        }
    
        // Array para almacenar los valores únicos
        $values = [];
    
        // Recorrer los resultados
        while ($row = mysqli_fetch_assoc($result)) {
            $atributearray = explode(',', $row['tbactivityatributearray']);
            $dataarray = explode(',', $row['tbactivitydataarray']);
            
            // Buscamos el índice del atributo
            $index = array_search(trim($attribute), array_map('trim', $atributearray));
            
            // Si encontramos el atributo, agregamos su valor correspondiente
            if ($index !== false && isset($dataarray[$index])) {
                $values[] = trim($dataarray[$index]);
            }
        }
    
        mysqli_free_result($result);
        mysqli_close($conn);
    
        return $values;
    }

    // Método para obtener todas las actividades recomendadas activas
    public function getAllActivitiesRecommended($attribute, $value)
    {
        // Conexión a la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8mb4');
    
        // Consulta para obtener todas las actividades activas
        $query = "SELECT tbactivityid, tbactivityname, tbactivityservicecompanyid, tbactivityatributearray, 
                         tbactivitydataarray, tbactivityurl, tbactivitystatus, tbactivitydate, tbactivitylatitude, 
                         tbactivitylongitude 
                  FROM tbactivity 
                  WHERE tbactivityatributearray LIKE '%" . mysqli_real_escape_string($conn, $attribute) . "%' 
                  AND tbactivitydataarray LIKE '%" . mysqli_real_escape_string($conn, $value) . "%' 
                  AND tbactivitystatus = 1";
        
        $result = mysqli_query($conn, $query);
    
        if (!$result) {
            echo "Query failed: " . mysqli_error($conn);
            mysqli_close($conn);
            return null;
        }
    
        // Array para almacenar las actividades
        $activities = [];
    
        // Recorrer los resultados
        while ($row = mysqli_fetch_assoc($result)) {
            // Convertir los atributos de cadena a arrays
            $atributearray = explode(',', $row['tbactivityatributearray']);
            $dataarray = explode(',', $row['tbactivitydataarray']);
            $urls = explode(',', $row['tbactivityurl']);
            
            // Crear un objeto de actividad o array asociativo
            $activity = [
                'tbactivityid' => $row['tbactivityid'],
                'tbactivityname' => $row['tbactivityname'],
                'tbactivityservicecompanyid' => $row['tbactivityservicecompanyid'],
                'tbactivityatributearray' => $atributearray,
                'tbactivitydataarray' => $dataarray,
                'tbactivityurl' => $urls,
                'tbactivitystatus' => $row['tbactivitystatus'],
                'tbactivitydate' => $row['tbactivitydate'],
                'tbactivitylatitude' => $row['tbactivitylatitude'],
                'tbactivitylongitude' => $row['tbactivitylongitude']
            ];
    
            // Añadir la actividad al array
            $activities[] = $activity;
        }
    
        // Liberar el resultado y cerrar la conexión
        mysqli_free_result($result);
        mysqli_close($conn);
    
        return $activities;
    }
    

    public function getAllActivitiesByOwner($ownerid)
    {
        // Conexión a la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $conn->set_charset('utf8mb4');

        // Consulta para obtener todas las actividades activas de un propietario
        $query = 
        "SELECT tbactivityid, tbactivityname, tbactivityservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus, tbactivitydate, tbactivitylatitude, tbactivitylongitude  
        FROM tbactivity act 
        INNER JOIN tbservicecompany servcompa ON act.tbactivityservicecompanyid = servcompa.tbservicecompanyid 
        -- ya con tbservicecompany tengo el tbtouristcompanyid 
        INNER JOIN tbtouristcompany tourcompany ON servcompa.tbtouristcompanyid = tourcompany.tbtouristcompanyid 
        WHERE tourcompany.tbtouristcompanyowner = " . $ownerid . " AND act.tbactivitystatus = 1;";

        $result = mysqli_query($conn, $query);

        if (!$result) {
            echo "Query failed: " . mysqli_error($conn);
            mysqli_close($conn);
            return null;
        }

        // Array para almacenar las actividades
        $activities = [];

        // Recorrer los resultados
        while ($row = mysqli_fetch_assoc($result)) {
            // Convertir los atributos de cadena a arrays
            $atributearray = explode(',', $row['tbactivityatributearray']);
            $dataarray = explode(',', $row['tbactivitydataarray']);
            $urls = explode(',', $row['tbactivityurl']);
            
            // Crear un objeto de actividad o array asociativo
            $activity = [
                'tbactivityid' => $row['tbactivityid'],
                'tbactivityname' => $row['tbactivityname'],
                'tbactivityservicecompanyid' => $row['tbactivityservicecompanyid'],
                'tbactivityatributearray' => $atributearray,
                'tbactivitydataarray' => $dataarray,
                'tbactivityurl' => $urls,
                'tbactivitystatus' => $row['tbactivitystatus'],
                'tbactivitydate' => $row['tbactivitydate'],
                'tbactivitylatitude' => $row['tbactivitylatitude'],
                'tbactivitylongitude' => $row['tbactivitylongitude']
            ];

            // Añadir la actividad al array
            $activities[] = $activity;
        }

        // Cerrar la conexión
        mysqli_free_result($result);
        mysqli_close($conn);

        return $activities;
    }




    // Método para eliminar una actividad (marcar como inactiva)
    public function deleteActivity($id)
    {
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
    public function updateActivity($activity)
{
    // Conexión a la base de datos
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        return ['status' => 'error', 'message' => 'Fallo en la conexión a la base de datos'];
    }

    $conn->set_charset('utf8mb4');

    // Revisión de duplicados
    $tbactivityname = $activity->getNameTBActivity();
    $tbactivityid = $activity->getIdTBActivity();

    $checkQuery = "SELECT COUNT(*) FROM tbactivity WHERE tbactivityname = ? AND tbactivityid != ? AND tbactivitystatus = 1";
    $stmtCheck = $conn->prepare($checkQuery);
    if ($stmtCheck === false) {
        mysqli_close($conn);
        return ['status' => 'error', 'message' => 'Error al preparar la consulta de verificación de duplicados'];
    }

    $stmtCheck->bind_param("si", $tbactivityname, $tbactivityid);
    $stmtCheck->execute();
    $stmtCheck->bind_result($count);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($count > 0) {
        mysqli_close($conn);
        return ['status' => 'error', 'message' => 'La actividad ya existe con el mismo nombre'];
    }

    // Actualización de la actividad con latitud y longitud
    $queryUpdate = "UPDATE tbactivity
                    SET tbactivityname = ?, tbactivityservicecompanyid = ?, tbactivityatributearray = ?, tbactivitydataarray = ?, tbactivityurl = ?, tbactivitystatus = ?, tbactivitydate = ?, tbactivitylatitude = ?, tbactivitylongitude = ?
                    WHERE tbactivityid = ?";
    $stmt = $conn->prepare($queryUpdate);
    if ($stmt === false) {
        mysqli_close($conn);
        return ['status' => 'error', 'message' => 'Error al preparar la consulta de actualización'];
    }

    // Enlazamos los parámetros correctamente
    $tbServicesid = $activity->getTbservicecompanyid();
    $tbactivityatributearray = is_array($activity->getAttributeTBActivityArray()) ? implode(",", $activity->getAttributeTBActivityArray()) : '';
    $tbactivitydataarray = is_array($activity->getDataAttributeTBActivityArray()) ? implode(",", $activity->getDataAttributeTBActivityArray()) : '';
    $imageUrls = is_array($activity->getTbactivityURL()) ? implode(',', $activity->getTbactivityURL()) : $activity->getTbactivityURL();
    $tbactivitystatus = $activity->getStatusTBActivity();
    $tbactivitydate = $activity->getActivityDate();
    $latitude = $activity->getLatitude();
    $longitude = $activity->getLongitude();

    // Orden correcto de los parámetros en bind_param
    $stmt->bind_param("sisssssddi", 
        $tbactivityname,          
        $tbServicesid,            
        $tbactivityatributearray, 
        $tbactivitydataarray,     
        $imageUrls,             
        $tbactivitystatus,        
        $tbactivitydate,        
        $latitude,                
        $longitude,             
        $tbactivityid             
    );

    $result = $stmt->execute();

    if (!$result) {
        $stmt->close();
        mysqli_close($conn);
        return ['status' => 'error', 'message' => 'Error al ejecutar la actualización'];
    }

    if ($stmt->affected_rows === 0) {
        $stmt->close();
        mysqli_close($conn);
        return ['status' => 'error', 'message' => 'No se ha actualizado ninguna fila'];
    }

    // Si todo fue exitoso
    $stmt->close();
    mysqli_close($conn);

    return ['status' => 'success', 'message' => 'Actividad actualizada exitosamente'];
}

    



    // Método para obtener una actividad por su ID
    public function getActivityById($id)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');

        // Consulta que ahora incluye latitud y longitud
        $query = "SELECT tbactivityid, tbactivityname, tbactivityservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus, tbactivitydate, tbactivitylatitude, tbactivitylongitude 
              FROM tbactivity 
              WHERE tbactivityid = ? AND tbactivitystatus = 1";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Ahora se agregan latitud y longitud
        $stmt->bind_result($tbactivityid, $tbactivityname, $tbactivityservicecompanyid, $tbactivityatributearray, $tbactivitydataarray, $tbactivityurl, $tbactivitystatus, $tbactivitydate, $latitude, $longitude);

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
                    $tbactivitydate, // Fecha
                    $latitude,       // Latitud
                    $longitude       // Longitud
                );
            }
        }

        $stmt->close();
        mysqli_close($conn);

        return $activity;
    }


    // Método para obtener una actividad por su nombre
    public function getActivityByName($activityName)
    {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');

        // Consulta que ahora incluye latitud y longitud
        $query = "SELECT tbactivityid, tbactivityname, tbactivityservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus, tbactivitydate, tbactivitylatitude, tbactivitylongitude 
              FROM tbactivity 
              WHERE tbactivityname = ? AND tbactivitystatus = 1";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $activityName);
        $stmt->execute();
        $stmt->bind_result($tbactivityid, $tbactivityname, $tbactivityservicecompanyid, $tbactivityatributearray, $tbactivitydataarray, $tbactivityurl, $tbactivitystatus, $tbactivitydate, $latitude, $longitude);

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
                $tbactivitydate, // Fecha
                $latitude,       // Latitud
                $longitude       // Longitud
            );
        }

        $stmt->close();
        mysqli_close($conn);

        return $activity;
    }


    // Método para eliminar una imagen de la actividad
    public function removeImageFromActivity($activityId, $newImageUrls)
    {
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
    public function isImageInUse($imageToDelete)
    {
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
    public function getActivitiesByDay($date)
    {
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
                $row['tbactivitydate'],
                $row['tbactivitylatitude'],  // Incluye latitud
                $row['tbactivitylongitude']  // Incluye longitud
            );

            $activities[] = $activity;
        }

        mysqli_close($conn);
        return $activities;
    }


    // Método para obtener actividades por semana
    public function getActivitiesByWeek($date)
    {
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
                $row['tbactivitydate'],
                $row['tbactivitylatitude'],  // Incluye latitud
                $row['tbactivitylongitude']  // Incluye longitud
            );

            $activities[] = $activity;
        }

        mysqli_close($conn);
        return $activities;
    }


    // Método para obtener actividades por mes
    public function getActivitiesByMonth($date)
    {
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
                $row['tbactivitydate'],
                $row['tbactivitylatitude'],  // Incluye latitud
                $row['tbactivitylongitude']  // Incluye longitud
            );

            $activities[] = $activity;
        }

        mysqli_close($conn);
        return $activities;
    }
}

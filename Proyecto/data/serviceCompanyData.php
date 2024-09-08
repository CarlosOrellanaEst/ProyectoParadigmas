<?php

include_once 'data.php';
include '../domain/ServiceCompany.php';
include '../domain/Service.php';

class serviceCompanyData extends Data {

 // Prepared Statement
    public function insertTBServiceCompany($service) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
        }
        $conn->set_charset('utf8');
        $queryGetLastId = "SELECT MAX(tbservicecompanyid) AS idTbservicecompany FROM tbservicecompany";
        $idCont = mysqli_query($conn, $queryGetLastId);
        if ($idCont === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Failed to get last ID: ' . $conn->error];
        }
        $nextId = 1;
        if($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }

        $touristCompanyId = $service->getTbtouristcompanyid();
        $idService = implode(",", $service->getTbserviceid());
        $photosurl = implode(",", $service->getTbservicecompanyURL());

        $status = true;

            $queryInsert = "INSERT INTO tbservicecompany (tbservicecompanyid, tbtouristcompanyid, tbserviceid, tbservicecompanyURL, tbservicetatus) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($queryInsert);
            if ($stmt === false) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
            }

            $stmt->bind_param("iiisi", $nextId, $touristCompanyId, $idService, $photosurl);
            $result = $stmt->execute();
            $stmt->close();
            mysqli_close($conn);

            if ($result) {
                return ['status' => 'success', 'message' => 'Servicio añadido correctamente'];
            } else {
                return ['status' => 'error', 'message' => 'Falló al agregar el servicio: ' . $conn->error];
            }
        
    }
    // lee todos
    public function getAllTBServicesCompany() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbservice WHERE tbservicestatus = 1;";
        $result = mysqli_query($conn, $query);
    
        $services = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $currentService = new Service($row['tbserviceid'], $row['tbservicename'], $row['tbphoto'], $row['tbservicestatus']);
            array_push($services, $currentService);
        }
    
        mysqli_close($conn);
        return $services;
    } 

    public function getTBRoll($idTBRoll) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbroll WHERE tbrollid = $idTBRoll";
        $result = mysqli_query($conn, $query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $rollReturn = new Roll($row['tbrollid'], $row['tbrollname'], $row['tbrolldescription'], $row['tbrollstatus']);
        } else {
            $rollReturn = null;
        }
    
        mysqli_close($conn);
        return $rollReturn;
    } 
    
    public function updateTBRoll($roll) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
        
        $id = $roll->getIdTBRoll();
        $var = $this->getTBRollIDByName($roll);
        $rollRegistered = $this->getTBRollObject($var);

        if ($rollRegistered->getNameTBRoll() == $roll->getNameTBRoll()) { 
            $result = null; 
        } else {
            $newName = mysqli_real_escape_string($conn,  $roll->getNameTBRoll());
            $newDescription = mysqli_real_escape_string($conn,  $roll->getDescriptionTBRoll());
        
            $query = "UPDATE tbroll SET tbrollname = '$newName', tbrolldescription = '$newDescription' WHERE tbrollid = $id";
            $result = mysqli_query($conn, $query);
        
            mysqli_close($conn);
        }
        return $result;
    }
    
    public function deleteTBRoll($idRoll) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbroll SET tbrollstatus = 0 where tbrollid=" . $idRoll . ";";
        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }    

    public function getTBRollIDByName($roll) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $rollName = mysqli_real_escape_string($conn, $roll->getNameTBRoll());
        $rollID = mysqli_real_escape_string($conn, $roll->getIdTBRoll());
    
        $query = "SELECT tbrollid FROM tbroll WHERE tbrollname = '$rollName' AND tbrollid != '$rollID'";
        $result = mysqli_query($conn, $query);
    
        $rollId = null;
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row != null && count($row) > 0) {
                $rollId = $row['tbrollid'];
            }
            else {
                $rollId = 0;
            }
        }
        mysqli_close($conn);
        return $rollId;
    }
    public function getTBServiceByName($service) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $serviceName = mysqli_real_escape_string($conn, $service->getNameTBService());
    
        $query = "SELECT tbserviceid FROM tbservice WHERE tbservicename = '$serviceName' AND tbservicestatus=1";
        $result = mysqli_query($conn, $query);
    
        $serviceId = null;
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row != null && count($row) > 0) {
                $serviceId = $row['tbserviceid'];
            }
            else {
                $serviceId = 0;
            }
        }
        mysqli_close($conn);
        return $serviceId;
    }

    public function getTBRollObject($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbroll WHERE tbrollstatus = 1 AND tbrollid = '$id';";
        $result = mysqli_query($conn, $query);
    
        $currentRoll = new Roll();
        while ($row = mysqli_fetch_assoc($result)) {
            $currentRoll = new Roll($row['tbrollid'], $row['tbrollname'], $row['tbrolldescription'], $row['tbrollstatus']);
        }
    
        mysqli_close($conn);
        return $currentRoll;
    } 

    
    public function getTBRollExistsIsActive($rollId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbroll WHERE tbrollstatus=1 AND tbrollid = $rollId";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $rollReturn = true : $rollReturn = false;
    
        mysqli_close($conn);
        return $rollReturn;
    } 

    public function getAllTBServices() {
        // Establecer conexión con la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Establecer el charset
        $conn->set_charset('utf8');
        
        // Consulta para seleccionar todos los servicios que están activos (tbservicetatus = 1)
        $query = "SELECT * FROM tbservice WHERE tbservicetatus = 1;";
        $result = mysqli_query($conn, $query);
        
        // Crear un array para almacenar los servicios
        $services = [];
        
        // Recorrer los resultados y crear objetos Service para cada fila
        while ($row = mysqli_fetch_assoc($result)) {
            $currentService = new Service($row['tbserviceid'], $row['tbservicename'], $row['tbservicedescription'], $row['tbservicetatus']);
            array_push($services, $currentService);
        }
        
        // Cerrar la conexión
        mysqli_close($conn);
        
        // Devolver la lista de servicios
        return $services;
    }
    
}

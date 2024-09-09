<?php

include_once 'data.php';
include '../domain/ServiceCompany.php';
include '../domain/Service.php';

class serviceCompanyData extends Data {

    public function insertTBServiceCompany($service) {
        // Conexión a la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
        }
        $conn->set_charset('utf8');
    
        // Obtener el último ID de la tabla tbservicecompany
        $queryGetLastId = "SELECT MAX(tbservicecompanyid) AS idTbservicecompany FROM tbservicecompany";
        $idCont = mysqli_query($conn, $queryGetLastId);
        if ($idCont === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Failed to get last ID: ' . $conn->error];
        }
    
        $nextId = 1;
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }
    
        
        // Insertar los datos en la tabla
        $queryInsert = "INSERT INTO tbservicecompany (tbservicecompanyid, tbtouristcompanyid, tbserviceid, tbservicecompanyURL, tbservicetatus) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        // Obtener valores del objeto $service
        $touristCompanyId = $service->getTbtouristcompanyid();
    
        // Obtener y procesar los IDs de los servicios
        $serviceIds = $service->getTbserviceid();
        $idService = is_array($serviceIds) ? implode(",", $serviceIds) : $serviceIds;
    
        // Obtener y procesar las URLs de las fotos agrupadas por servicios
        $photosUrlsByService = $service->getTbservicecompanyURL();
       
        if (is_array($photosUrlsByService)) {
            $imageUrlsString = implode(',', $photosUrlsByService);
        } else {
            $imageUrlsString = $photosUrlsByService;
        }
        $status = 1;
        $stmt->bind_param("iiisi",$nextId, $touristCompanyId, $idService,  $imageUrlsString, $status);
      
        $result = $stmt->execute();
    
        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }
    
        $stmt->close();
        mysqli_close($conn);

        if ($result) {
            return ['status' => 'success', 'message' => ' añadido correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Falló al agregar el Servicio: ' . $conn->error];
        }
    }
    

    public function getAllTBServiceCompanies() {
        // Establecer conexión con la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Establecer el charset
        $conn->set_charset('utf8');
        
        // Consulta para seleccionar todos los registros de tbservicecompany
        $query = "SELECT * FROM tbservicecompany WHERE tbservicetatus = 1;";
        $result = mysqli_query($conn, $query);
        
        // Crear un array para almacenar las compañías de servicios
        $serviceCompanies = [];
        
        // Recorrer los resultados y crear objetos ServiceCompany para cada fila
        while ($row = mysqli_fetch_assoc($result)) {
            $currentServiceCompany = new ServiceCompany(
                $row['tbservicecompanyid'], 
                $row['tbtouristcompanyid'], 
                $row['tbserviceid'], 
                $row['tbservicecompanyURL'],
                $row['tbservicetatus']
            );
            array_push($serviceCompanies, $currentServiceCompany);
        }
        
        // Cerrar la conexión
        mysqli_close($conn);
        
        // Devolver la lista de compañías de servicios
        return $serviceCompanies;
    }
    
    public function getServiceCompany($serviceCompanyId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');
    
        // Preparar la consulta con un parámetro para evitar SQL injection
        $query = "SELECT * FROM tbservicecompany WHERE tbservicecompanyid = ? AND tbservicetatus != 0";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        // Enlazar el parámetro
        $stmt->bind_param("i", $serviceCompanyId);
        $stmt->execute();
        
        // Obtener el resultado
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            // Construir un objeto ServiceCompany
            $serviceCompany = new ServiceCompany(
                $row['tbservicecompanyid'],
                $row['tbtouristcompanyid'],
                $row['tbserviceid'],
                $row['tbservicecompanyURL'],
                $row['tbservicetatus']
            );
        } else {
            $serviceCompany = null;
        }
        
        $stmt->close();
        mysqli_close($conn);
        
        return $serviceCompany;
    }
    
    public function deleteTBServiceCompany($idServiceCompany) {
        // Conectar a la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
    
        // Actualizar el campo 'tbservicetatus' a 0 (desactivar servicio)
        $queryUpdate = "UPDATE tbservicecompany SET tbservicetatus = 0 WHERE tbservicecompanyid = " . $idServiceCompany . ";";
        $result = mysqli_query($conn, $queryUpdate);
    
        // Cerrar la conexión
        mysqli_close($conn);
    
        // Retornar el resultado de la operación
        return $result;
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

    public function getTBService($idTBService) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
        
        $query = "SELECT * FROM tbservice WHERE tbserviceid = $idTBService";
        $result = mysqli_query($conn, $query);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $serviceReturn = new Service(
                $row['tbserviceid'], 
                $row['tbservicename'], 
                $row['tbservicedescription'], 
                $row['tbservicetatus']
            );
        } else {
            $serviceReturn = null;
        }
        
        mysqli_close($conn);
        return $serviceReturn;
    }
    
    public function removeImageFromServiceCompany($serviceCompanyId, $newImageUrls) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');
    
        // Actualizar la URL en la base de datos
        $query = "UPDATE tbservicecompany SET tbservicecompanyURL=? WHERE tbservicecompanyid=?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmt->bind_param("si", $newImageUrls, $serviceCompanyId);
        $result = $stmt->execute();
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }
    
}

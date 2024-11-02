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
        $stmt->bind_param("iissi",$nextId, $touristCompanyId, $idService,  $imageUrlsString, $status);
      
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

    public function getServicesIDsByCompanyID($companyid) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
        
        $query = "SELECT tbserviceid FROM tbservicecompany WHERE tbservicetatus = 1 AND tbtouristcompanyid = ?";
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, 'i', $companyid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        $services = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $services = $row['tbserviceid'];
        }
    
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    
        return $services;
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
        // Validar y escapar el parámetro $idTBService para prevenir inyecciones SQL
        $idTBService = intval($idTBService);
    
        // Establecer conexión
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
        
        // Preparar la consulta SQL
        $query = "SELECT * FROM tbservice WHERE tbserviceid = $idTBService";
        $result = mysqli_query($conn, $query);
        
        if ($result === false) {
            // Manejo de errores en la consulta
            die("Error en la consulta: " . mysqli_error($conn));
        }
        
        $serviceReturn = null;
        if ($row = mysqli_fetch_assoc($result)) {
            // Crear el objeto Service si se obtienen resultados
            $serviceReturn = new Service(
                $row['tbserviceid'], 
                $row['tbservicename'], 
                $row['tbservicedescription'], 
                $row['tbservicetatus']
            );
        }
        
        // Cerrar la conexión
        mysqli_close($conn);
        
        return $serviceReturn;
    }

    public function getTBServices($idsTBService) {
        // Escapar y validar los ids para prevenir inyecciones SQL
        $idsArray = explode(',', $idsTBService); // Convertir la cadena de ids en un array
        $idsArray = array_map('intval', $idsArray); // Asegurarse de que todos los valores sean enteros
    
        if (empty($idsArray)) {
            return null; // Retornar null si no hay ids
        }
    
        // Convertir el array a una cadena separada por comas para la consulta SQL
        $placeholders = implode(',', array_fill(0, count($idsArray), '?'));
    
        // Establecer conexión
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            throw new Exception("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        // Preparar la consulta SQL usando la cláusula IN con placeholders
        $query = "SELECT * FROM tbservice WHERE tbserviceid IN ($placeholders)";
        $stmt = mysqli_prepare($conn, $query);
        
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . mysqli_error($conn));
        }
    
        // Enlazar los parámetros para la consulta
        $types = str_repeat('i', count($idsArray)); // Usamos 'i' para enteros
        mysqli_stmt_bind_param($stmt, $types, ...$idsArray);
    
        // Ejecutar la consulta
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if ($result === false) {
            throw new Exception("Error en la consulta: " . mysqli_error($conn));
        }
    
        $servicesReturn = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // Crear objetos Service y agregarlos al array de retorno
            $servicesReturn[] = new Service(
                $row['tbserviceid'], 
                $row['tbservicename'], 
                $row['tbservicedescription'], 
                $row['tbservicestatus']
            );
        }   
        
        // Cerrar la conexión
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    
        return $servicesReturn; // Retornar la lista de servicios
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

    public function updateTBServiceCompany($service) {
        // Conexión a la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
        }
        $conn->set_charset('utf8');
    
        // Obtener valores del objeto $service
        $serviceCompanyId = $service->getTbservicecompanyid();
        $touristCompanyId = $service->getTbtouristcompanyid();
        $serviceIds = $service->getTbserviceid(); // Suponiendo que este campo ya es un string con múltiples IDs separados por comas
        $imageUrlsString = is_array($service->getTbservicecompanyURL()) ? implode(',', $service->getTbservicecompanyURL()) : $service->getTbservicecompanyURL();
        $status = $service->getTbservicetatus();
    
        // Consulta para actualizar el registro
        $queryUpdate = "UPDATE tbservicecompany 
                        SET tbtouristcompanyid = ?, tbserviceid = ?, tbservicecompanyURL = ?, tbservicetatus = ? 
                        WHERE tbservicecompanyid = ?";
        $stmt = $conn->prepare($queryUpdate);
        if ($stmt === false) {
            return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
        }
    
        $stmt->bind_param("issii", $touristCompanyId, $serviceIds, $imageUrlsString, $status, $serviceCompanyId);
        $result = $stmt->execute();
    
        if (!$result) {
            $stmt->close();
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Execute failed: ' . $stmt->error];
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return ['status' => 'success', 'message' => 'Actualizado correctamente.'];
    }

    public function removeServiceFromServiceCompany($serviceCompanyId, $serviceIdToRemove) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');
    
        // Actualizar la URL en la base de datos
        $query = "UPDATE tbservicecompany SET tbserviceid=? WHERE tbservicecompanyid=?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmt->bind_param("si", $serviceIdToRemove, $serviceCompanyId);
        $result = $stmt->execute();
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    
}
}

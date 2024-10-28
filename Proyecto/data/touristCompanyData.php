<?php

include_once 'data.php';
include_once '../domain/TouristCompany.php'; 
include_once '../domain/Photo.php'; 

class TouristCompanyData extends Data{  
    public function insertTouristCompany($touristCompany) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Fallo en la conexión: ' . mysqli_connect_error()];
        }

        $conn->set_charset('utf8');

        $tbtouristcompanylegalname = $touristCompany->getTbtouristcompanylegalname();
        $queryCheck = "SELECT COUNT(*) FROM tbtouristcompany WHERE tbtouristcompanylegalname = ? AND tbtouristcompanystatus = 1";
        $stmtCheck = $conn->prepare($queryCheck);
        if ($stmtCheck === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Prepare fallido para verificación: ' . $conn->error];
        }

        $stmtCheck->bind_param("s", $tbtouristcompanylegalname);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if($tbtouristcompanylegalname!=null){
            if ($this->getTouristCompanyByName($tbtouristcompanylegalname)!=null) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'La compañía turística ya está registrada'];
            }
        }
        
        
        

        $queryGetLastId = "SELECT MAX(tbtouristcompanyid) AS tbtouristcompanyid FROM tbtouristcompany";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }

        $imageUrls = $touristCompany->getTbtouristcompanyurl();
        $imageUrlsString = is_array($imageUrls) ? implode(',', $imageUrls) : $imageUrls;

        $queryInsert = "INSERT INTO tbtouristcompany (tbtouristcompanyid, tbtouristcompanylegalname, tbtouristcompanymagicname, tbtouristcompanyowner, tbtouristcompanycompanyType, tbtouristcompanyurl, tbtouristcompanystatus) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Prepare fallido: ' . $conn->error];
        }

        $tbtouristcompanymagicname = $touristCompany->getTbtouristcompanymagicname();
        $tbtouristcompanyowner = $touristCompany->getTbtouristcompanyowner();
        $tbtouristcompanystatus = $touristCompany->getTbtouristcompanystatus();
        $tbtouristcompanycompanyType = $touristCompany->getTbtouristcompanycompanyType();
        

        if ($tbtouristcompanycompanyType->getId == 0) { // Si otro
            $customizedInsertResult = $this->insertCustomizedtouristcompanytype($touristCompany);

            if ($customizedInsertResult['status'] === 'error') {
                mysqli_close($conn);
                return $customizedInsertResult;
            }
        }else{

        }

        $stmt->bind_param("issiisi", $nextId, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $imageUrlsString, $tbtouristcompanystatus);
        $result = $stmt->execute();

        if ($result) {
            $stmt->close();
            mysqli_close($conn);
            return ['status' => 'success', 'message' => 'Compañía turística añadida correctamente.'];
        } else {
            $errorMessage = $conn->error;
            $stmt->close();
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Falló al agregar la compañía turística: ' . $errorMessage];
        }
    }
    
    public function insertCustomizedtouristcompanytype($touristCompany) {
        print "Este es un mensaje desde PHP en la terminal\n";
        // Conexión a la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Fallo en la conexión: ' . mysqli_connect_error()];
        }
        $conn->set_charset('utf8');
       
        // Obtener el último ID en la tabla para asignar el siguiente
        $queryGetLastId = "SELECT MAX(tbtouristcompanytypeid) AS tbtouristcompanytypeid FROM tbtouristcompanytype";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1; // Valor predeterminado en caso de que no haya registros
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }
        
        // Preparar la consulta de inserción
        $queryInsert = "INSERT INTO tbtouristcompanytype (tbtouristcompanytypeid, tbtouristcompanytypename, tbtouristcompanytypedescription, tbtouristcompanytypeisactive) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Prepare fallido: ' . $conn->error];
        }
        var_dump($touristCompany->getTbtouristcompanycustomcompanytype());    
        // Obtener los valores del objeto $touristCompany
        $tbtouristcompanytypename = $touristCompany->getTbtouristcompanycustomcompanytype(); // Nombre del tipo de empresa

        $tbtouristcompanytypedescription = "";
        $tbtouristcompanytypeisactive = 1;
        
        //var_dump($tbtouristcompanytypename);
        //echo "Descripción: $tbtouristcompanytypedescription\n";
        //echo "Estado activo: $tbtouristcompanytypeisactive\n";
        // Asociar los parámetros y ejecutar la consulta
        $stmt->bind_param("issi", $nextId, $tbtouristcompanytypename, $tbtouristcompanytypedescription, $tbtouristcompanytypeisactive);
        $result = $stmt->execute();
    
        if ($result) {
            $stmt->close();
            mysqli_close($conn);
            return ['status' => 'success', 'message' => 'Tipo de empresa turística añadido correctamente.'];
        } else {
            $errorMessage = $conn->error;
            $stmt->close();
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Falló al agregar el tipo de empresa turística: ' . $errorMessage];
        }
    }
    
    
    

public function getAllTouristCompanies() {
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn->set_charset('utf8');

   
    $query = "SELECT * FROM tbtouristcompany WHERE tbtouristcompanystatus = 1;";
    $result = mysqli_query($conn, $query);

    $touristCompanies = [];
    while ($row = mysqli_fetch_assoc($result)) {
      
        $company = new TouristCompany(
            $row['tbtouristcompanyid'],
            $row['tbtouristcompanylegalname'],
            $row['tbtouristcompanymagicname'],
            $row['tbtouristcompanyowner'],
            $row['tbtouristcompanycompanyType'],
            $row['tbtouristcompanyurl'], 
            $row['tbtouristcompanystatus']
        );

        $photoUrls = explode(',', $row['tbtouristcompanyurl']);
        $company->setTbtouristcompanyurl(array_map('trim', $photoUrls)); 

        $touristCompanies[] = $company;
    }

    mysqli_close($conn);
    return $touristCompanies;
}

public function getAllTouristCompaniesByOwnerId($ownerId) {
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn->set_charset('utf8');

    $ownerId = mysqli_real_escape_string($conn, $ownerId);
    
    $query = "SELECT * FROM tbtouristcompany WHERE tbtouristcompanystatus = 1 AND tbtouristcompanyowner = '$ownerId'";
    $result = mysqli_query($conn, $query);

    $touristCompanies = [];
    while ($row = mysqli_fetch_assoc($result)) {
      
        $company = new TouristCompany(
            $row['tbtouristcompanyid'],
            $row['tbtouristcompanylegalname'],
            $row['tbtouristcompanymagicname'],
            $row['tbtouristcompanyowner'],
            $row['tbtouristcompanycompanyType'],
            $row['tbtouristcompanyurl'], 
            $row['tbtouristcompanystatus']
        );

        $photoUrls = explode(',', $row['tbtouristcompanyurl']);
        $company->setTbtouristcompanyurl(array_map('trim', $photoUrls)); 

        $touristCompanies[] = $company;
    }

    mysqli_close($conn);
    return $touristCompanies;
}


    public function deleteTouristCompany($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');

        $query = "UPDATE tbtouristcompany SET tbtouristcompanystatus=0 WHERE tbtouristcompanyid=?";

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

    public function updateTouristCompany($touristCompany) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    

        $tbtouristcompanylegalname = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanylegalname());
        $tbtouristcompanyid = $touristCompany->getTbtouristcompanyid();
    
        $queryCheck = "SELECT COUNT(*) FROM tbtouristcompany WHERE tbtouristcompanylegalname = ? AND tbtouristcompanyid != ? AND tbtouristcompanystatus = 1";
        $stmtCheck = $conn->prepare($queryCheck);
        if ($stmtCheck === false) {
            mysqli_close($conn);
            die("Prepare failed for verification: " . $conn->error);
        }
    
        $stmtCheck->bind_param("si", $tbtouristcompanylegalname, $tbtouristcompanyid);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();
    
      
        if ($count > 0) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Ya existe una compañía turística con el mismo nombre legal y está activa.'];
        }
    
       
        $currentUrlQuery = "SELECT tbtouristcompanyurl FROM tbtouristcompany WHERE tbtouristcompanyid = ?";
        $stmt = $conn->prepare($currentUrlQuery);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmt->bind_param("i", $tbtouristcompanyid);
        $stmt->execute();
        $stmt->bind_result($currentUrl);
        $stmt->fetch();
        $stmt->close();
    
       
        $tbtouristcompanyurl = $touristCompany->getTbtouristcompanyurl();
        if ($tbtouristcompanyurl === null || empty($tbtouristcompanyurl)) {
            $tbtouristcompanyurl = $currentUrl; 
        } else {
            $tbtouristcompanyurl = implode(',', $tbtouristcompanyurl);  
        }
    
        
        $query = "UPDATE tbtouristcompany SET tbtouristcompanylegalname=?, tbtouristcompanymagicname=?, tbtouristcompanyowner=?, tbtouristcompanycompanytype=?, tbtouristcompanyurl=?, tbtouristcompanystatus=? WHERE tbtouristcompanyid=?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $tbtouristcompanymagicname = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanymagicname());
        $tbtouristcompanyowner = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanyowner());
        $tbtouristcompanycompanytype = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanycompanytype());
        $tbtouristcompanystatus = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanystatus());
    
        
        $stmt->bind_param("ssiisii", $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanytype, $tbtouristcompanyurl, $tbtouristcompanystatus, $tbtouristcompanyid);
        $result = $stmt->execute();
    
        $stmt->close();
        mysqli_close($conn);
    
        if ($result) {
            return ['status' => 'success', 'message' => 'Compañía turística actualizada correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Error al actualizar la compañía turística.'];
        }
    }
    
    
    

    public function getTouristCompany($idTBTouristCompany) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbtouristcompany WHERE tbtouristcompanyid = $idTBTouristCompany";
        $result = mysqli_query($conn, $query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            
            $company = new TouristCompany(
                $row['tbtouristcompanyid'],
                $row['tbtouristcompanylegalname'],
                $row['tbtouristcompanymagicname'],
                $row['tbtouristcompanyowner'],
                $row['tbtouristcompanycompanyType'],
                $row['tbtouristcompanyurl'], 
                $row['tbtouristcompanystatus']
            );
    
            
            $photoUrls = explode(',', $row['tbtouristcompanyurl']);
            $company->setTbtouristcompanyurl(array_map('trim', $photoUrls));
            
            $companyReturn = $company;
        } else {
            $companyReturn = null;
        }
    
        mysqli_close($conn);
        return $companyReturn;
    }
    

    public function getTouristCompanyByName($touristCompanyName) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT tbtouristcompanyid, tbtouristcompanylegalname, tbtouristcompanymagicname, tbtouristcompanyowner, tbtouristcompanycompanyType, tbtouristcompanyurl, tbtouristcompanystatus FROM tbtouristcompany WHERE tbtouristcompanylegalname=? AND tbtouristcompanystatus=1";
        
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmt->bind_param("s", $touristCompanyName);
        $stmt->execute();
        $stmt->bind_result($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbactivityurl, $tbtouristcompanystatus);
        
        $touristCompany = null; 
        if ($stmt->fetch()) {
            $touristCompany = new TouristCompany($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbactivityurl, $tbtouristcompanystatus);
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return $touristCompany;
    }

    public function removeImageFromCompany($companyId, $newImageUrls) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
        
       
        $query = "SELECT tbtouristcompanyurl FROM tbtouristcompany WHERE tbtouristcompanyid=?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmt->bind_param("i", $companyId);
        $stmt->execute();
        $stmt->bind_result($currentImageUrl);
        $stmt->fetch();
        $stmt->close();
    
        
        $query = "SELECT COUNT(*) FROM tbtouristcompany WHERE tbtouristcompanyurl=?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmt->bind_param("s", $currentImageUrl);
        $stmt->execute();
        $stmt->bind_result($imageCount);
        $stmt->fetch();
        $stmt->close();
    

        if ($imageCount == 1 && $currentImageUrl != $newImageUrls && !empty($currentImageUrl)) {
            $imagePath = '/path/to/images/' . $currentImageUrl;
            if (file_exists($imagePath)) {
                unlink($imagePath); 
            }
        }
    
   
        $query = "UPDATE tbtouristcompany SET tbtouristcompanyurl=? WHERE tbtouristcompanyid=?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmt->bind_param("si", $newImageUrls, $companyId);
        $result = $stmt->execute();
        $stmt->close();
    
        mysqli_close($conn);
    
        return $result;
    }
    public function isImageInUse($imageUrl) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
        
   
        $query = "SELECT COUNT(*) FROM tbtouristcompany WHERE FIND_IN_SET(?, tbtouristcompanyurl)";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmt->bind_param("s", $imageUrl);
        $stmt->execute();
        $stmt->bind_result($imageCount);
        $stmt->fetch();
        $stmt->close();
        mysqli_close($conn);
        
        return $imageCount > 0;
    }
    
    
    
    
    
   


}

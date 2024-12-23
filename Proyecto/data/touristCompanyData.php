<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'data.php';
include_once '../domain/TouristCompany.php'; 
include_once '../domain/Photo.php'; 

class TouristCompanyData extends Data{  
    public function insertTouristCompany($touristCompany) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
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
    
        if($tbtouristcompanylegalname != ""){
            if ($this->getTouristCompanyByName($touristCompany->getTbtouristcompanylegalname()) != null) {
                return ['status' => 'error', 'message' => 'La compañía turística ya está registrada'];
            }
        }
    
        // Obtener el siguiente ID
        $queryGetLastId = "SELECT MAX(tbtouristcompanyid) AS tbtouristcompanyid FROM tbtouristcompany";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }
    
        // Obtener las URLs de imágenes y asegurarse de que sean una cadena separada por comas
        $imageUrls = $touristCompany->getTbtouristcompanyurl();
        if($imageUrls != ""){
            $imageUrlsString = is_array($imageUrls) ? implode(',', $imageUrls) : $imageUrls;
        } else {
            $imageUrlsString = "";
        }
    
        // Obtener los tipos de empresa y asegurarse de que sean una cadena separada por comas
        $companyTypes = $touristCompany->getTbtouristcompanycompanyType();
        if($companyTypes != ""){
            $companyTypesString = is_array($companyTypes) ? implode(',', $companyTypes) : $companyTypes;
        } else {
            $companyTypesString = "";
        }
    
        // Insertar en tbtouristcompany
        $queryInsert = "INSERT INTO tbtouristcompany (tbtouristcompanyid, tbtouristcompanylegalname, tbtouristcompanymagicname, tbtouristcompanyowner, tbtouristcompanycompanyType, tbtouristcompanyurl, tbtouristcompanystatus) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Prepare fallido: ' . $conn->error];
        }
    
        $tbtouristcompanymagicname = $touristCompany->getTbtouristcompanymagicname();
        $tbtouristcompanyowner = $touristCompany->getTbtouristcompanyowner();
        $tbtouristcompanystatus = $touristCompany->getTbtouristcompanystatus();
    
        $stmt->bind_param("ississi", $nextId, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $companyTypesString, $imageUrlsString, $tbtouristcompanystatus);
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
    


    public function insertCustomizedtouristcompanytype($ownerId, $customCompanyType) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Fallo en la conexión: ' . mysqli_connect_error()];
        }
        $conn->set_charset('utf8');

        $queryGetLastId = "SELECT MAX(tbcustomizedtouristcompanytypeid) AS idtbcustomizedtouristcompanytype FROM tbcustomizedtouristcompanytype";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;
        $active = 1;

        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }
        $queryGetLastTouristCompanyId = "SELECT MAX(tbtouristcompanyid) AS tbtouristcompanyid FROM tbtouristcompany";
        $touristCompanyResult = mysqli_query($conn, $queryGetLastTouristCompanyId);
        $touristCompanyId = 0; // Valor por defecto si no se encuentra
    
        if ($touristCompanyRow = mysqli_fetch_row($touristCompanyResult)) {
            $touristCompanyId = $touristCompanyRow[0] !== null ? (int)trim($touristCompanyRow[0]) : 0;
        }

        $queryInsert = "INSERT INTO tbcustomizedtouristcompanytype (tbcustomizedtouristcompanytypeid, tbownerid, tbtouristcompanyid, tbcustomizedtouristcompanytypename, tbcustomizedtouristcompanytypestatus) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Prepare fallido: ' . $conn->error];
        }
        

        $stmt->bind_param("iiisi", $nextId, $ownerId, $touristCompanyId, $customCompanyType, $active);
        $result = $stmt->execute();

        if ($result) {
            $stmt->close();
            mysqli_close($conn);
            return true;
            //return ['status' => 'success', 'message' => 'Tipo de empresa turística turistica añadida correctamente.'];
        } else {
            $errorMessage = $conn->error;
            $stmt->close();
            mysqli_close($conn);
            error_log('Error en la inserción: ' . $stmt->error); // Log de error en la inserción
            //return ['status' => 'error', 'message' => 'Falló al agregar el tipo de empresa personalizada: ' . $conn->error];

            return false;
            
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

        $result2 = $this->deleteTbtouristcompanytouristcompanytype($id);

        $result3 = $this->deleteCustomizedTouristCompanyType($id);

        if ($result2 && $result3){
            $stmt->close();
            mysqli_close($conn);
            return $result;
        } else {
            return false;
        }   
    }

    public function deleteTbtouristcompanytouristcompanytype ($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        // Modificamos la consulta para actualizar la tabla tbtouristcompanytouristcompanytype
        $query = "UPDATE tbtouristcompanytouristcompanytype  SET tbtouristcompanytouristcompanytypestatus=0 WHERE tbtouristcompany=?";
    
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        // Cambiamos el tipo de parámetro a "i" para un entero
        $stmt->bind_param("i", $id);
    
        $result = $stmt->execute();
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }

    public function deleteCustomizedTouristCompanyType($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        // Modificamos la consulta para actualizar la tabla tbcustomizedtouristcompanytype
        $query = "UPDATE tbcustomizedtouristcompanytype SET tbcustomizedtouristcompanytypestatus=0 WHERE tbcustomizedtouristcompanytypeid=?";
    
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        // Cambiamos el tipo de parámetro a "i" para un entero
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
    
        // Escapar los datos de la compañía
        $tbtouristcompanylegalname = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanylegalname());
        $tbtouristcompanyid = $touristCompany->getTbtouristcompanyid();
    
        // Verificar si el nombre legal ya existe en una compañía activa
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
    
        // Si ya existe una compañía con el mismo nombre legal, retornar error
        if ($count > 0) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Ya existe una compañía turística con el mismo nombre legal y está activa.'];
        }
    
        // Obtener la URL actual de la compañía
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
    
        // Obtener la URL nueva o mantener la actual si no se proporciona una nueva
        $tbtouristcompanyurl = $touristCompany->getTbtouristcompanyurl();
        if ($tbtouristcompanyurl === null || empty($tbtouristcompanyurl)) {
            $tbtouristcompanyurl = $currentUrl;
        } else {
            $tbtouristcompanyurl = implode(',', $tbtouristcompanyurl); // Convertir en cadena separada por comas
        }
    
        // Obtener y procesar los tipos de empresa (convertir a cadena separada por comas)
        $tbtouristcompanycompanytype = $touristCompany->getTbtouristcompanycompanytype();
        if (is_array($tbtouristcompanycompanytype)) {
            $tbtouristcompanycompanytype = implode(',', $tbtouristcompanycompanytype); // Convertir array en cadena
        }
    
        // Escapar los otros datos
        $tbtouristcompanymagicname = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanymagicname());
        $tbtouristcompanyowner = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanyowner());
        $tbtouristcompanystatus = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanystatus());
    
        // Consulta para actualizar la compañía
        $query = "UPDATE tbtouristcompany SET tbtouristcompanylegalname=?, tbtouristcompanymagicname=?, tbtouristcompanyowner=?, tbtouristcompanycompanytype=?, tbtouristcompanyurl=?, tbtouristcompanystatus=? WHERE tbtouristcompanyid=?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        // Vincular los parámetros a la consulta
        $stmt->bind_param("ssissii", $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanytype, $tbtouristcompanyurl, $tbtouristcompanystatus, $tbtouristcompanyid);
        $result = $stmt->execute();
    
        // Cerrar la conexión
        $stmt->close();
        mysqli_close($conn);
    
        // Retornar el resultado
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
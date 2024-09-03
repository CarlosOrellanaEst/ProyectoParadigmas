<?php

include_once 'data.php';
include_once '../domain/TouristCompany.php'; // Ajusta la ruta según tu estructura
include_once '../domain/Photo.php'; 

class TouristCompanyData extends Data{

    // Método de inserción en la clase TouristCompanyBusiness
public function insertTouristCompany($touristCompany) {
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        return ['status' => 'error', 'message' => 'Fallo en la conexión: ' . mysqli_connect_error()];
    }

    $conn->set_charset('utf8');

    // Obtener el próximo ID
    $queryGetLastId = "SELECT MAX(tbtouristcompanyid) AS tbtouristcompanyid FROM tbtouristcompany";
    $idCont = mysqli_query($conn, $queryGetLastId);
    $nextId = 1;
    if ($row = mysqli_fetch_row($idCont)) {
        $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
        $nextId = $lastId + 1;
    }

    // Preparar la URL de las imágenes
    $imageUrls = $touristCompany->getTbtouristcompanyurl();

    // Verificar si $imageUrls es un array o una cadena
    if (is_array($imageUrls)) {
        $imageUrlsString = implode(',', $imageUrls);
    } else {
        $imageUrlsString = $imageUrls;
    }

    $queryInsert = "INSERT INTO tbtouristcompany (tbtouristcompanyid, tbtouristcompanylegalname, tbtouristcompanymagicname, tbtouristcompanyowner, tbtouristcompanycompanyType, tbtouristcompanyurl, tbtouristcompanystatus) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($queryInsert);
    if ($stmt === false) {
        mysqli_close($conn);
        return ['status' => 'error', 'message' => 'Prepare fallido: ' . $conn->error];
    }

    // Usar getters para obtener los valores
    $tbtouristcompanyid = $nextId;
    $tbtouristcompanylegalname = $touristCompany->getTbtouristcompanylegalname();
    $tbtouristcompanymagicname = $touristCompany->getTbtouristcompanymagicname();
    $tbtouristcompanyowner = $touristCompany->getTbtouristcompanyowner();
    $tbtouristcompanycompanyType = $touristCompany->getTbtouristcompanycompanyType();
    $tbtouristcompanyurl = $imageUrlsString;
    $tbtouristcompanystatus = $touristCompany->getTbtouristcompanystatus();

    $stmt->bind_param("issiisi", $tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbtouristcompanyurl, $tbtouristcompanystatus);
    $result = $stmt->execute();

    if ($result) {
        $stmt->close();
        mysqli_close($conn);
        return ['status' => 'success', 'message' => 'Compañía turística añadida correctamente.'];
    } else {
        $errorMessage = $conn->error;  // Capturar el mensaje de error de la base de datos
        $stmt->close();
        mysqli_close($conn);
        return ['status' => 'error', 'message' => 'Falló al agregar la compañía turística: ' . $errorMessage];
    }
}

    

public function getAllTouristCompanies() {
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn->set_charset('utf8');

    // Consulta para obtener todas las empresas turísticas activas
    $query = "SELECT * FROM tbtouristcompany WHERE tbtouristcompanystatus = 1;";
    $result = mysqli_query($conn, $query);

    $touristCompanies = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Crear una instancia de TouristCompany
        $company = new TouristCompany(
            $row['tbtouristcompanyid'],
            $row['tbtouristcompanylegalname'],
            $row['tbtouristcompanymagicname'],
            $row['tbtouristcompanyowner'],
            $row['tbtouristcompanycompanyType'],
            $row['tbtouristcompanyurl'], // Aquí están las URLs separadas por comas
            $row['tbtouristcompanystatus']
        );

        // Separar las URLs si están separadas por comas
        $photoUrls = explode(',', $row['tbtouristcompanyurl']);
        $company->setTbtouristcompanyurl(array_map('trim', $photoUrls)); // Limpiar espacios y establecer URLs

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

        $query = "UPDATE tbtouristcompany SET tbtouristcompanylegalname=?, tbtouristcompanymagicname=?, tbtouristcompanyowner=?, tbtouristcompanycompanytype=?, tbtouristcompanystatus=? WHERE tbtouristcompanyid=?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        if($this->getTouristCompanyByName($touristCompany->getLegalName())->getLegalName()=== $touristCompany->getLegalName()){            
            $tbtouristcompanyid = $touristCompany->getId();
            $tbtouristcompanylegalname = $touristCompany->getLegalName();
            $tbtouristcompanymagicname = $touristCompany->getMagicName();
            $tbtouristcompanyowner = $touristCompany->getOwner();
            $tbtouristcompanycompanytype = $touristCompany->getCompanyType();
            $tbtouristcompanystatus = $touristCompany->getStatus()
            ;

            $stmt->bind_param("ssiiii", $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanytype, $tbtouristcompanystatus, $tbtouristcompanyid);

            $result = $stmt->execute();
        }
        else {
            return $result = null;
        }
        $stmt->close();

        mysqli_close($conn);

        return $result;
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
            // Crear una instancia de TouristCompany
            $company = new TouristCompany(
                $row['tbtouristcompanyid'],
                $row['tbtouristcompanylegalname'],
                $row['tbtouristcompanymagicname'],
                $row['tbtouristcompanyowner'],
                $row['tbtouristcompanycompanyType'],
                $row['tbtouristcompanyurl'], // Aquí están las URLs separadas por comas
                $row['tbtouristcompanystatus']
            );
    
            // Separar las URLs si están separadas por comas
            $photoUrls = explode(',', $row['tbtouristcompanyurl']);
            $company->setPhotoUrls(array_map('trim', $photoUrls)); // Limpiar espacios y establecer URLs
            
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
    
        $query = "SELECT tbtouristcompanyid, tbtouristcompanylegalname, tbtouristcompanymagicname, tbtouristcompanyowner, tbtouristcompanycompanyType, tbphotoid, tbtouristcompanystatus FROM tbtouristcompany WHERE tbtouristcompanylegalname=?";
        
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmt->bind_param("s", $touristCompanyName);
        $stmt->execute();
        $stmt->bind_result($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbphotoid, $tbtouristcompanystatus);
        
        $touristCompany = null; // Initialize to null in case no record is found
        if ($stmt->fetch()) {
            $touristCompany = new TouristCompany($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbphotoid, $tbtouristcompanystatus);
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return $touristCompany;
    }
    
   


}

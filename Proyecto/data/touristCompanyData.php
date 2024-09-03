<?php

include_once 'data.php';
include_once '../domain/TouristCompany.php'; // Ajusta la ruta según tu estructura
include_once '../domain/Photo.php'; 

class TouristCompanyData extends Data{

    public function insertTouristCompany($touristCompany) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
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
            $imageUrlsString = implode(',', $imageUrls); // Convertir el array en una cadena separada por comas
        } else {
            $imageUrlsString = $imageUrls; // Asumir que ya es una cadena
        }
    
        // Insertar la empresa turística
        $queryInsert = "INSERT INTO tbtouristcompany (tbtouristcompanyid, tbtouristcompanylegalname, tbtouristcompanymagicname, tbtouristcompanyowner, tbtouristcompanycompanyType, tbtouristcompanyurl, tbtouristcompanystatus) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
        }
    
        // Usar getters para obtener los valores
        $tbtouristcompanyid = $nextId;
        $tbtouristcompanylegalname = $touristCompany->getTbtouristcompanylegalname();
        $tbtouristcompanymagicname = $touristCompany->getTbtouristcompanymagicname();
        $tbtouristcompanyowner = $touristCompany->getTbtouristcompanyowner();
        $tbtouristcompanycompanyType = $touristCompany->getTbtouristcompanycompanyType();
        $tbtouristcompanyurl = $imageUrlsString; // Guardar como una cadena separada por comas
        $tbtouristcompanystatus = $touristCompany->getTbtouristcompanystatus();
    
        $stmt->bind_param("issiisi", $tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbtouristcompanyurl, $tbtouristcompanystatus);
        $result = $stmt->execute();
    
        $stmt->close();
        mysqli_close($conn);
    
        if ($result) {
            return ['status' => 'success', 'message' => 'Compañía turística añadida correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Falló al agregar la compañía turística: ' . $conn->error];
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

    $touristCompanies = array();

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        // Crear una instancia de TouristCompany
        $company = new TouristCompany(
            $row['tbtouristcompanyid'],
            $row['tbtouristcompanylegalname'],
            $row['tbtouristcompanymagicname'],
            $row['tbtouristcompanyowner'],
            $row['tbtouristcompanycompanyType'],
            $row['tbphotoid'],
            $row['tbtouristcompanystatus']
        );

        // Consultar las fotos asociadas a la empresa
        $photoQuery = "SELECT * FROM tbphoto WHERE tbphotoid = ?";
        $photoStmt = $conn->prepare($photoQuery);
        if ($photoStmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $photoStmt->bind_param("i", $row['tbphotoid']);
        $photoStmt->execute();
        $photoResult = $photoStmt->get_result();

        while ($photoRow = $photoResult->fetch_assoc()) {
            // Separar las URLs si están separadas por comas
            $photoUrls = explode(',', $photoRow['tbphotourl']);

            foreach ($photoUrls as $photoUrl) {
                $photoUrl = trim($photoUrl); // Eliminar espacios en blanco

                // Verificar si el URL contiene un "5"
                if (strpos($photoUrl, '5') === false) {
                    $photo = new Photo(
                        $photoRow['tbphotoid'],
                        $photoUrl, // Usar el URL individual
                        $photoRow['tbphotoindex'],
                        $photoRow['tbphotostatus']
                    );
                    $company->addPhoto($photo);
                }
            }
        }

        $photoStmt->close();
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

    public function getTouristById($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');

        $query = "SELECT * FROM tbtouristcompany WHERE tbtouristcompanyid=?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->bind_result($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanytype, $tbtouristcompanystatus);

        $stmt->fetch();

        $touristCompany = new TouristCompany($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanytype, $tbtouristcompanystatus);

        $stmt->close();

        mysqli_close($conn);

        return $touristCompany;
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

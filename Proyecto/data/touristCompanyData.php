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

    
    $queryGetLastId = "SELECT MAX(tbtouristcompanyid) AS tbtouristcompanyid FROM tbtouristcompany";
    $idCont = mysqli_query($conn, $queryGetLastId);
    $nextId = 1;
    if ($row = mysqli_fetch_row($idCont)) {
        $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
        $nextId = $lastId + 1;
    }

   
    $imageUrls = $touristCompany->getTbtouristcompanyurl();

    
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
        $errorMessage = $conn->error; 
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
    
        
        $currentUrlQuery = "SELECT tbtouristcompanyurl FROM tbtouristcompany WHERE tbtouristcompanyid = ?";
        $stmt = $conn->prepare($currentUrlQuery);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $tbtouristcompanyid = $touristCompany->getTbtouristcompanyid();
        $stmt->bind_param("i", $tbtouristcompanyid);
        $stmt->execute();
        $stmt->bind_result($currentUrl);
        $stmt->fetch();
        $stmt->close();
    
      
        $tbtouristcompanylegalname = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanylegalname());
        $tbtouristcompanymagicname = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanymagicname());
        $tbtouristcompanyowner = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanyowner());
        $tbtouristcompanycompanytype = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanycompanytype());
        $tbtouristcompanystatus = mysqli_real_escape_string($conn, $touristCompany->getTbtouristcompanystatus());
     
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
    
       
        $stmt->bind_param("ssiisii", $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanytype, $tbtouristcompanyurl, $tbtouristcompanystatus, $tbtouristcompanyid);
    
     
        $result = $stmt->execute();
    
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
    
        $query = "SELECT tbtouristcompanyid, tbtouristcompanylegalname, tbtouristcompanymagicname, tbtouristcompanyowner, tbtouristcompanycompanyType, tbphotoid, tbtouristcompanystatus FROM tbtouristcompany WHERE tbtouristcompanylegalname=?";
        
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmt->bind_param("s", $touristCompanyName);
        $stmt->execute();
        $stmt->bind_result($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbphotoid, $tbtouristcompanystatus);
        
        $touristCompany = null; 
        if ($stmt->fetch()) {
            $touristCompany = new TouristCompany($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbphotoid, $tbtouristcompanystatus);
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

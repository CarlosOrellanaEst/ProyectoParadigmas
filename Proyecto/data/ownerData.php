<?php

include_once 'data.php';
include_once '../domain/Owner.php';

class ownerData extends Data {
   
    public function insertTBOwner($owner) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'error_code' => 'db_connection_failed', 'message' => 'Connection failed: ' . mysqli_connect_error()];
        }
        $conn->set_charset('utf8');
    
        // Obtener el último ID de tbowner
        $queryGetLastId = "SELECT MAX(tbownerid) AS idtbowner FROM tbowner";
        $idCont = mysqli_query($conn, $queryGetLastId);
        if ($idCont === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'error_code' => 'db_error', 'message' => 'Failed to get last owner ID: ' . $conn->error];
        }
        $nextIdOwner = 1;
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextIdOwner = $lastId + 1;
        }
    
        // Obtener el último ID de tbuser
        $queryGetLastUserId = "SELECT MAX(tbuserid) AS idtbuser FROM tbuser";
        $idCont = mysqli_query($conn, $queryGetLastUserId);
        if ($idCont === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'error_code' => 'db_error', 'message' => 'Failed to get last user ID: ' . $conn->error];
        }
        $nextIdUser = 1;
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextIdUser = $lastId + 1;
        }
    
        // Obtener los datos del objeto Owner
        $name = $owner->getName();
        $surnames = $owner->getSurnames();
        $legalIdentification = $owner->getLegalIdentification();
        $phone = $owner->getPhone();
        $email = $owner->getEmail();
        $nickname = $owner->getNickname();
        $password = $owner->getPassword();
        $direction = $owner->getDirectionTBOwner();
        $photoUrl = $owner->getPhotoURLTBOwner();
        $tbrollid = 3;
        $tbuserstatus = true;
    
        // Comprobación de duplicados
        if ($this->getTBOwnerByEmail($email)) {
            mysqli_close($conn);
            return ['status' => 'error', 'error_code' => 'email_already_registered', 'message' => 'Ya existe un propietario con este correo electrónico.'];
        }
        if (!empty($phone) && $this->getTBOwnerByPhone($phone)) {
            mysqli_close($conn);
            return ['status' => 'error', 'error_code' => 'phone_already_registered', 'message' => 'El número de teléfono ya está registrado.'];
        }
        if ($this->getTBOwnerByLegalId($legalIdentification)) {
            mysqli_close($conn);
            return ['status' => 'error', 'error_code' => 'legal_id_already_registered', 'message' => 'La identificación legal ya está registrada.'];
        }
        if($this->getOwnerByNickName($nickname)) {
            mysqli_close($conn);
            return ['status' => 'error', 'error_code' => 'nickname_already_registered', 'message' => 'El nombre de usuario ya está registrado.'];
        }
    
        // Inserción de usuario en tbuser
        $queryInsertUsers = "INSERT INTO tbuser (tbuserid, tbusername, tbusersurnames, tbuserlegalidentification, tbuserphone, tbuseremail, tbusernickname, tbuserpassword, tbrollid, tbuserstatus) VALUES (?,?,?,?,?,?,?,?,?,?)";    
        $stmt = $conn->prepare($queryInsertUsers);
        if ($stmt === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'error_code' => 'db_error', 'message' => 'Failed to prepare user insert statement: ' . $conn->error];
        }
        $stmt->bind_param("isssssssii", $nextIdUser, $name, $surnames, $legalIdentification, $phone, $email, $nickname, $password, $tbrollid, $tbuserstatus);
        $result = $stmt->execute();
        if (!$result) {
            $stmt->close();
            mysqli_close($conn);
            return ['status' => 'error', 'error_code' => 'db_error', 'message' => 'Failed to insert user: ' . $stmt->error];
        }
        $stmt->close();
    
        // Inserción del propietario en tbowner
        $queryInsert = "INSERT INTO tbowner (tbownerid, tbuserid, tbownerdirection, tbownerphotourl, tbownerstatus) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'error_code' => 'db_error', 'message' => 'Failed to prepare owner insert statement: ' . $conn->error];
        }
        $stmt->bind_param("iissi", $nextIdOwner, $nextIdUser, $direction, $photoUrl, $tbuserstatus);
        $result = $stmt->execute();
        if (!$result) {
            $stmt->close();
            mysqli_close($conn);
            return ['status' => 'error', 'error_code' => 'db_error', 'message' => 'Failed to insert owner: ' . $stmt->error];
        }
        $stmt->close();
        mysqli_close($conn);
    
        return ['status' => 'success', 'message' => 'Propietario añadido correctamente.'];
    }
    
    
    
    
    
    public function getAllTBOwner() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');

        $query = "SELECT * FROM tbowner INNER JOIN tbuser ON tbowner.tbuserid = tbuser.tbuserid WHERE tbownerstatus=1 AND tbuserstatus=1;"; 
        
        $result = mysqli_query($conn, $query);
    
        $owners = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $currentOwner = new Owner( 
                $row['tbownerid'], $row['tbownerdirection'], $row['tbownerphotourl'], $row['tbownerstatus'], 
                $row['tbuserid'], $row['tbusernickname'], $row['tbuserpassword'], $row['tbuserstatus'], "Propietario", $row['tbusername'], $row['tbusersurnames'], $row['tbuserlegalidentification'], $row['tbuserphone'], $row['tbuseremail']  
            );
            array_push($owners, $currentOwner);
        }
        
        mysqli_close($conn);
        return $owners;
    } 

    public function getTBOwner($idTBOwner) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');  
        $query = "SELECT * FROM tbowner INNER JOIN tbuser ON tbowner.tbuserid = tbuser.tbuserid WHERE tbownerid = $idTBOwner"; 
    
        $result = mysqli_query($conn, $query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $ownerReturn = new Owner( 
                $row['tbownerid'], $row['tbownerdirection'], $row['tbownerphotourl'], $row['tbownerstatus'], 
                $row['tbuserid'], $row['tbusernickname'], $row['tbuserpassword'], $row['tbuserstatus'], "Propietario", $row['tbusername'], $row['tbusersurnames'], $row['tbuserlegalidentification'], $row['tbuserphone'], $row['tbuseremail']  
            );
      //      echo ("\n\n " . $ownerReturn);
        } else {
            $ownerReturn = null;
        }
    
        mysqli_close($conn);
        return $ownerReturn;
    } 

    public function updateTBOwner($owner) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
        
        $idUser = $owner->getId();
        $idOwner = $owner->getIdTBOwner();
        $newName = mysqli_real_escape_string($conn, $owner->getName());
        $newSurnames = mysqli_real_escape_string($conn, $owner->getSurnames());
        $newLegalIdentification = mysqli_real_escape_string($conn, $owner->getLegalIdentification());
        $newPhone = mysqli_real_escape_string($conn, $owner->getPhone());
        $newEmail = mysqli_real_escape_string($conn, $owner->getEmail());
        $newDirection = mysqli_real_escape_string($conn, $owner->getDirectionTBOwner());
        $newURL = mysqli_real_escape_string($conn, $owner->getPhotoURLTBOwner());

        $emailQuery = "SELECT * FROM tbuser WHERE tbuseremail = '$newEmail' AND tbuserid != $idUser AND tbuserstatus = 1";
        $emailResult = mysqli_query($conn, $emailQuery);
        $phoneQuery = "SELECT * FROM tbuser WHERE tbuserphone = '$newPhone' AND tbuserid != $idUser AND tbuserstatus = 1";
        $phoneResult = mysqli_query($conn, $phoneQuery);
        $legalIdQuery = "SELECT * FROM tbuser WHERE tbuserlegalidentification = '$newLegalIdentification' AND tbuserid != $idUser AND tbuserstatus = 1";
        $legalIdResult = mysqli_query($conn, $legalIdQuery);
    
        $legalIdResult = mysqli_query($conn, $legalIdQuery);
       
        if (mysqli_num_rows($emailResult) > 0) {
            $result = "Email";
        } else if (mysqli_num_rows($phoneResult) > 0) {
            $result = "Phone";
        } else if (mysqli_num_rows($legalIdResult) > 0) {
            $result = "LegalId";
        } else {
            $query = "UPDATE tbuser SET tbusername = '$newName', tbusersurnames = '$newSurnames', tbuserlegalidentification = '$newLegalIdentification', tbuserphone = '$newPhone', tbuseremail = '$newEmail' WHERE tbuserid = $idUser";
           
            $result = mysqli_query($conn, $query) ? 1 : "dbError";
            if ($result==1) {
                $query = "UPDATE tbowner SET tbownerdirection = '$newDirection' WHERE tbuserid= $idUser";
                $result = mysqli_query($conn, $query) ? 1 : "dbError";
               
            } 
        }
    
        mysqli_close($conn);
        return $result;
    }
    
    public function deleteTBOwner($idOwner, $idUser) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        echo($idOwner.$idUser);

        $queryUpdateUser = "UPDATE tbuser SET tbuserstatus = 0 WHERE tbuserid=" . $idUser . ";";
        $result = mysqli_query($conn, $queryUpdateUser);
        $varReturn = false;
        if ($result) {
            $varReturn = true;
            $queryUpdateOwner = "UPDATE tbowner SET tbownerstatus = 0 WHERE tbownerid=" . $idOwner . ";";
            $result = false;
            $result = mysqli_query($conn, $queryUpdateOwner);
            mysqli_close($conn);
            if ($result) {
                // return ['status' => 'success', 'message' => 'Propietario eliminado.'];
                $varReturn = true;
            } else {
                // return ['status' => 'error', 'message' => 'Falló al eliminar el propietario: ' . $conn->error];
            }
        } 
        else {
            // return ['status' => 'error', 'message' => 'Falló al eliminar el propietario: ' . $conn->error];
        }
        return $varReturn;
    } 

    public function getTBOwnerByEmail($ownerEmail) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbuser WHERE tbuseremail= '$ownerEmail'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $ownerReturn = true : $ownerReturn = false;
    
        mysqli_close($conn);
        return $ownerReturn;
    } 

    public function getTBOwnerByPhone($ownerPhone) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbuser WHERE tbuserphone= '$ownerPhone'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $ownerReturn = true : $ownerReturn = false;
    
        mysqli_close($conn);
        return $ownerReturn;
    } 

    public function getTBOwnerByLegalId($LegalId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbuser WHERE tbuserlegalidentification= '$LegalId'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $ownerReturn = true : $ownerReturn = false;
    
        mysqli_close($conn);
        return $ownerReturn;
    } 

    public function getTBOwnerExistsIsActive($ownerId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbowner WHERE tbownerstatus=1 AND tbownerid = $ownerId";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $ownerReturn = true : $ownerReturn = false;
    
        mysqli_close($conn);
        return $ownerReturn;
    } 

    public function getTBOwnerByUserId($tbuserId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbowner WHERE tbuserid = $tbuserId";
        $result = mysqli_query($conn, $query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $ownerReturn = new Owner($row['tbownerid'], /* $row['tbuserid'], */ $row['tbownerdirection'], $row['tbownerphotourl'], $row['tbownerstatus']);
        } else {
            $ownerReturn = null;
        }
        mysqli_close($conn);
        return $ownerReturn;
    }

    public function getOwnerByNickName($nickName) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbuser WHERE tbusernickname = '$nickName'";
        $result = mysqli_query($conn, $query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $ownerReturn = new Owner($row['tbuserid'], $row['tbusernickname'], $row['tbuserpassword'], $row['tbuserstatus']);
        } else {
            $ownerReturn = null;
        }
        mysqli_close($conn);
        return $ownerReturn;
    }
}

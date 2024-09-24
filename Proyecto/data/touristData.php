<?php

include_once 'data.php';
include_once '../domain/User.php';

class touristData extends Data {
   
    public function insertTBUser($user) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
        }
        $conn->set_charset('utf8');
    
        $queryGetLastId = "SELECT MAX(tbuserid) AS idtbuser FROM tbuser";
        $idCont = mysqli_query($conn, $queryGetLastId);
        if ($idCont === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Failed to get last ID: ' . $conn->error];
        }
        $nextIdUser = 1;
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextIdUser = $lastId + 1;
        }
        
        $name = $user->getName();
        $surnames = $user->getSurnames();
        $legalIdentification = $user->getLegalIdentification();
        $phone = $user->getPhone();
        $email = $user->getEmail();
        $nickname = $user->getNickname();
        $password = $user->getPassword();
        $tbrollid = 2;
        $tbuserstatus = true; 
        
        $existsEmail = $this->getTBUserByEmail($email);
        $existsPhone = $phone ? $this->getTBUserByPhone($phone) : false;
        $existsLegalId = $this->getTBUserByLegalId($legalIdentification);
        $existsNickname = $this->getTBUserIdByNickname($nickname);
    
        if ($existsEmail) {
            if ($this->getTBUserExistsIsActive($existsEmail)) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'Ya existe un usuario con ese email.'];
            }
        } else if ($existsPhone) {
            if ($this->getTBUserExistsIsActive($existsPhone)) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'El teléfono ya existe.'];
            } 
        } else if ($existsLegalId) {
            if ($this->getTBUserExistsIsActive($existsLegalId)) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'La cédula ya existe.'];
            } 
        } else if ($existsNickname) {
            if ($this->getTBUserExistsIsActive($existsNickname)) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'El nombre de usuario ya existe.'];
            } 
        }else {
            $queryInsertUsers = "INSERT INTO tbuser (tbuserid, tbusername, tbusersurnames, tbuserlegalidentification, tbuserphone, tbuseremail, tbusernickname, tbuserpassword, tbrollid, tbuserstatus) VALUES (?,?,?,?,?,?,?,?,?,?)";	

            $stmt = $conn->prepare($queryInsertUsers);
            if ($stmt === false) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
            }
            
            $stmt->bind_param("isssssssii", $nextIdUser, $name, $surnames, $legalIdentification, $phone, $email, $nickname, $password, $tbrollid, $tbuserstatus);
            $result = $stmt->execute();
            $stmt->close();
            mysqli_close($conn);

            if ($result) {
                return ['status' => 'success', 'message' => 'Usuario añadido correctamente.'];
            } else {
                return ['status' => 'error', 'message' => 'Falló al agregar el usuario: ' . $conn->error];
            }
        
        }
    }
    /*
    //Email
    public function getTBUserByEmail($userEmail) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbuser WHERE tbuseremail= '$userEmail'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $userReturn = true : $userReturn = false;
    
        mysqli_close($conn);
        return $userReturn;
    } 

    //Telefono
    public function getTBUserByPhone($userPhone) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbuser WHERE tbuserphone= '$userPhone'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $userReturn = true : $userReturn = false;
    
        mysqli_close($conn);
        return $userReturn;
    } 

    //Identificacion
    public function getTBUserByLegalId($LegalId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbuser WHERE tbuserlegalidentification= '$LegalId'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $userReturn = true : $userReturn = false;
    
        mysqli_close($conn);
        return $userReturn;
    } 

    public function getTBUserExistsIsActive($userId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbuser WHERE tbuserstatus=1 AND tbuserid = $userId";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $userReturn = true : $userReturn = false;
    
        mysqli_close($conn);
        return $userReturn;
    }
    */
    public function getTBUserByEmail($userEmail) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT tbuserid FROM tbuser WHERE tbuseremail= ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            mysqli_close($conn);
            return false;
        }
    
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $userReturn = false;
        if ($row = $result->fetch_assoc()) {
            $userReturn = $row['tbuserid']; // Devuelve el ID del usuario encontrado
        }
    
        $stmt->close();
        mysqli_close($conn);
        return $userReturn;
    }

    public function getTBUserByPhone($userPhone) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT tbuserid FROM tbuser WHERE tbuserphone= ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            mysqli_close($conn);
            return false;
        }
    
        $stmt->bind_param("s", $userPhone);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $userReturn = false;
        if ($row = $result->fetch_assoc()) {
            $userReturn = $row['tbuserid']; // Devuelve el ID del usuario encontrado
        }
    
        $stmt->close();
        mysqli_close($conn);
        return $userReturn;
    }

    public function getTBUserByLegalId($legalId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT tbuserid FROM tbuser WHERE tbuserlegalidentification= ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            mysqli_close($conn);
            return false;
        }
    
        $stmt->bind_param("s", $legalId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $userReturn = false;
        if ($row = $result->fetch_assoc()) {
            $userReturn = $row['tbuserid']; // Devuelve el ID del usuario encontrado
        }
    
        $stmt->close();
        mysqli_close($conn);
        return $userReturn;
    }

    public function getTBUserIdByNickname($nickname) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT tbuserid FROM tbuser WHERE tbusernickname= ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            mysqli_close($conn);
            return false;
        }
    
        $stmt->bind_param("s", $nickname);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $userReturn = false;
        if ($row = $result->fetch_assoc()) {
            $userReturn = $row['tbuserid']; // Devuelve el ID del usuario encontrado
        }
    
        $stmt->close();
        mysqli_close($conn);
        return $userReturn;
    }
    
    public function getTBUserExistsIsActive($userId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbuser WHERE tbuserstatus=1 AND tbuserid = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            mysqli_close($conn);
            return false;
        }
    
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $userReturn = false;
        if ($row = $result->fetch_assoc()) {
            $userReturn = true; // Devuelve true si el usuario está activo
        }
    
        $stmt->close();
        mysqli_close($conn);
        return $userReturn;
    }
    
}
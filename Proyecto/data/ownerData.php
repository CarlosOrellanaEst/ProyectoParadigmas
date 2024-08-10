<?php

include_once 'data.php';
include '../domain/Owner.php';

class ownerData extends Data {

    // Prepared Statement
       public function insertTBOwner($owner) {
           $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
           if (!$conn) {
               die("Connection failed: " . mysqli_connect_error());
           }
   
           $conn->set_charset('utf8');
   
           // Obtiene el último id
           $queryGetLastId = "SELECT MAX(tbownerid) AS idtbowner FROM tbowner";
           $idCont = mysqli_query($conn, $queryGetLastId);
           $nextId = 1;
   
           if ($row = mysqli_fetch_row($idCont)) {
               $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
               $nextId = $lastId + 1;
           }
           if ($this->getTBOwnerByEmail($owner->getEmail())) {
               $result = null;
           } else {
 
               $queryInsert = "INSERT INTO tbowner (tbownerid, tbownername, tbownersurnames, tbownerlegalidentification, tbownerphone, tbowneremail, tbownerdirection, tbownerstatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
               $stmt = $conn->prepare($queryInsert); // el prepared statement de java
               if ($stmt === false) {
                   die("Prepare failed: " . $conn->error);
               }
               
               $name = $owner->getName();
               $surnames = $owner->getSurnames();
               $legalIdentification = $owner->getLegalIdentification();
               $phone= $owner->getPhone();
               $email=$owner->getEmail();
               $direction=$owner->getDirectionTBOwner();
               $statusDelete=true;
               // Vincula los parámetros del statement
               $stmt->bind_param("issssssi", $nextId, $name, $surnames, $legalIdentification, $phone, $email,$direction, $statusDelete); // "issi": cada letra es el tipo de dato de los parametros
           
               // Ejecuta la declaración
               $result = $stmt->execute();
           
               // Cierra la declaración y la conexión
               $stmt->close();
               mysqli_close($conn);
           }
   
           return $result;
       }

       // lee todos
    public function getAllTBOwner() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbowner WHERE tbownerstatus = 1;";
        $result = mysqli_query($conn, $query);
    
        $owners = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $currentOwner = new Owner($row['tbownerid'], $row['tbownerphone'], $row['tbownername'], $row['tbownersurnames'], $row['tbownerlegalidentification'], $row['tbowneremail'], $row['tbownerdirection'], $row['tbownerstatus']);
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
    
        $query = "SELECT * FROM tbowner WHERE tbownerid = $idTBOwner";
        $result = mysqli_query($conn, $query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $ownerReturn = new Owner($row['tbownerid'], $row['tbownername'], $row['tbownersurnames'], $row['tbownerlegalidentification'], $row['tbownerphone'], $row['tbowneremail'], $row['tbownerdirection'], $row['tbownerstatus']);
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
    
        $id = $owner->getIdTBOwner();
        $newName = mysqli_real_escape_string($conn,  $owner->getName());
        $newSurnames = mysqli_real_escape_string($conn,  $owner->getSurnames());
        $newLegalIdentification = mysqli_real_escape_string($conn,  $owner->getLegalIdentification());
        $newPhone = mysqli_real_escape_string($conn,  $owner->getPhone());
        $newEmail = mysqli_real_escape_string($conn,  $owner->getEmail());
        $newDirection = mysqli_real_escape_string($conn,  $owner->getDirectionTBOwner());
    
        $query = "UPDATE tbowner SET tbownername = '$newName', tbownersurnames = '$newSurnames', tbownerlegalidentification = '$newLegalIdentification', tbownerphone = '$newPhone', tbowneremail = '$newEmail', tbownerdirection = '$newDirection' WHERE tbownerid = $id";
        $result = mysqli_query($conn, $query);
    
        mysqli_close($conn);
        return $result;
    }

    public function deleteTBOwner($idOwner) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbowner SET tbownerstatus = 0 where tbownerid=" . $idOwner . ";";
        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    } 

       public function getTBOwnerByEmail($ownerEmail) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbowner WHERE tbowneremail= '$ownerEmail'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $ownerReturn = true : $ownerReturn = false;
    
        mysqli_close($conn);
        return $ownerReturn;
    } 
    }
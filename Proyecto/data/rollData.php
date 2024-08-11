<?php

include_once 'data.php';
include '../domain/Roll.php';

class RollData extends Data {

 // Prepared Statement
    public function insertTBRoll($roll) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $conn->set_charset('utf8');

        // Obtiene el último id
        $queryGetLastId = "SELECT MAX(tbrollid) AS idtbroll FROM tbroll";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;

        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }
        if ($this->getTBRollByName($roll->getNameTBRoll())) {
            $result = null;
        } else {
            $queryInsert = "INSERT INTO tbroll (tbrollid, tbrollname, tbrolldescription, tbrollstatus) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($queryInsert); // el prepared statement de java
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
            
            $name = $roll->getNameTBRoll();
            $description = $roll->getDescriptionTBRoll();
            $statusDelete = true;
        
            // Vincula los parámetros del statement
            $stmt->bind_param("issi", $nextId, $name, $description, $statusDelete); // "issi": cada letra es el tipo de dato de los parametros
        
            // Ejecuta la declaración
            $result = $stmt->execute();
        
            // Cierra la declaración y la conexión
            $stmt->close();
            mysqli_close($conn);
        }
        return $result;
    }
    // lee todos
    public function getAllTBRolls() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbroll WHERE tbrollstatus = 1;";
        $result = mysqli_query($conn, $query);
    
        $rolls = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $currentRoll = new Roll($row['tbrollid'], $row['tbrollname'], $row['tbrolldescription'], $row['tbrollstatus']);
            array_push($rolls, $currentRoll);
        }
    
        mysqli_close($conn);
        return $rolls;
    } 

    public function getTBRoll($idTBRoll) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbroll WHERE tbrollid = $idTBRoll";
        $result = mysqli_query($conn, $query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $rollReturn = new Roll($row['tbrollid'], $row['tbrollname'], $row['tbrolldescription'], $row['tbrollstatus']);
        } else {
            $rollReturn = null;
        }
    
        mysqli_close($conn);
        return $rollReturn;
    } 
    
    public function updateTBRoll($roll) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $id = $roll->getIdTBRoll();

        if ($this->getTBRollByName($roll->getNameTBRoll())) {
            $result = null; 
        } else {
            $newName = mysqli_real_escape_string($conn,  $roll->getNameTBRoll());
            $newDescription = mysqli_real_escape_string($conn,  $roll->getDescriptionTBRoll());
        
            $query = "UPDATE tbroll SET tbrollname = '$newName', tbrolldescription = '$newDescription' WHERE tbrollid = $id";
            $result = mysqli_query($conn, $query);
        
            mysqli_close($conn);
        }
        return $result;
    }
    
    public function deleteTBRoll($idRoll) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbroll SET tbrollstatus = 0 where tbrollid=" . $idRoll . ";";
        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }    
    
    public function getTBRollByName($rollName) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbroll WHERE tbrollname= '$rollName'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $rollReturn = true : $rollReturn = false;
    
        mysqli_close($conn);
        return $rollReturn;
    } 
} 

<?php

include_once 'data.php';
include '../domain/Roll.php';


class RollData extends Data {

 // Prepared Statement
 public function insertTBRoll($roll) {
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
    }

    $conn->set_charset('utf8');

    $queryGetLastId = "SELECT MAX(tbrollid) AS idtbroll FROM tbroll";
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

    $name = $roll->getNameTBRoll();
    $description = $roll->getDescriptionTBRoll();
    $status = 1;

    $exists = $this->getTBRollByName($roll->getNameTBRoll());
    if ($exists > 0) {
        if ($this->getTBRollExistsIsActive($exists)) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'El nombre del roll ya existe.'];
        } else {
            $queryUpdate = "UPDATE tbroll SET tbrolldescription = ?, tbrollstatus = 1 WHERE tbrollid = ?";
            $stmt = $conn->prepare($queryUpdate);
            if ($stmt === false) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
            }
            $stmt->bind_param("si", $description, $exists);
            $result = $stmt->execute();
            $stmt->close();
            mysqli_close($conn);

            if ($result) {
                return ['status' => 'success', 'message' => 'Roll añadido correctamenteee'];
            } else {
                return ['status' => 'error', 'message' => 'Falló al agregar el roll: ' . $conn->error];
            }
        }
    } else {
        $queryInsert = "INSERT INTO tbroll (tbrollid, tbrollname, tbrolldescription, tbrollstatus) VALUES (?, ?, ?, 1)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
        }

        $stmt->bind_param("iss", $nextId, $name, $description);
        $result = $stmt->execute();
        $stmt->close();
        mysqli_close($conn);

        if ($result) {
            return ['status' => 'success', 'message' => 'Roll añadido correctamente brrr'];
        } else {
            return ['status' => 'error', 'message' => 'Falló al agregar el roll: ' . $conn->error];
        }
    }
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
        $newName = mysqli_real_escape_string($conn,  $roll->getNameTBRoll());
        $newDescription = mysqli_real_escape_string($conn,  $roll->getDescriptionTBRoll());
    
        $query = "UPDATE tbroll SET tbrollname = '$newName', tbrolldescription = '$newDescription' WHERE tbrollid = $id";
        $result = mysqli_query($conn, $query);
    
        mysqli_close($conn);
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
    
        // Asegúrate de escapar adecuadamente el valor para evitar SQL Injection
        $rollName = mysqli_real_escape_string($conn, $rollName);
    
        $query = "SELECT tbrollid FROM tbroll WHERE tbrollname = '$rollName'";
        $result = mysqli_query($conn, $query);
    
        $rollId = null;
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row != null && count($row) > 0) {
                $rollId = $row['tbrollid']; // Asumiendo que 'id' es el nombre de la columna que contiene el ID
            }
            else {
                $rollId = 0;
            }
        }
    
        mysqli_close($conn);
        return $rollId;
    }
    
    public function getTBRollExistsIsActive($rollId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbroll WHERE tbrollstatus=1 AND tbrollid = $rollId";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $rollReturn = true : $rollReturn = false;
    
        mysqli_close($conn);
        return $rollReturn;
    } 
}

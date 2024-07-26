<?php

include_once 'data.php';
include '../domain/Roll.php';

class RollData extends Data {


    public function insertTBRoll($roll) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');

        //Get the last id
        $queryGetLastId = "SELECT MAX(idtbroll) AS idtbroll  FROM tbroll";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;

        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }

        // Escapa las cadenas para evitar inyección SQL (Sin esto no podia usar el query directo sino que solo con la sentencia preparada)
        $name = mysqli_real_escape_string($conn, $roll->getNameTBRoll());
        $description = mysqli_real_escape_string($conn, $roll->getDescriptionTBRoll());

        // Construye la consulta SQL directamente
        $queryInsert = "INSERT INTO tbroll (idtbroll, nametbroll, descriptiontbroll) VALUES (" . $nextId . ", '" . $name . "', '" . $description . "')";

  //      $queryInsert = "INSERT INTO tbroll (idtbroll, nametbroll, descriptiontbroll) VALUES (" . $nextId . "," . $roll->getNameTBRoll() . "," . $roll->getDescriptionTBRoll() . ");"; 
        $result = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);
        return $result;
    }

    // lee todos
    public function getAllTBRolls() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbroll";
        $result = mysqli_query($conn, $query);
    
        $rolls = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $currentRoll = new Roll($row['idtbroll'], $row['nametbroll'], $row['descriptiontbroll']);
            array_push($rolls, $currentRoll);
        }
    
        mysqli_close($conn);
        return $rolls;
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
    
        $query = "UPDATE tbroll SET nametbroll = '$newName', descriptiontbroll = '$newDescription' WHERE idtbroll = $id";
        $result = mysqli_query($conn, $query);
    
        mysqli_close($conn);
        return $result;
    }
    
    public function deleteTBRoll($idRoll) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "DELETE from tbroll where idtbroll=" . $idRoll . ";";
        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }    
    
}
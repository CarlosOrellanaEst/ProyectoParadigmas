<?php

include_once 'data.php';
include '../domain/BankAccount.php';

class bankAccountData extends Data {
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

}
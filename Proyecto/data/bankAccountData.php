<?php

include_once 'data.php';
include_once '../domain/BankAccount.php';

class BankAccountData extends Data {
    public function insertTbBankAccount($bankAccount) {
        error_log($this->server);
        error_log($this->user);
        error_log($this->password);
        error_log($this->db);

        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $conn->set_charset('utf8');

        // Obtiene el último id
        $queryGetLastId = "SELECT MAX(tbbankAccountId) AS idtbbankAccount FROM tbbankaccount";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;

        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }

        $queryInsert = "INSERT INTO tbbankaccount (tbbankAccountId, tbbankAccountOwnerId, tbbankAccountNumber, tbbankAccountBankName, tbbankAccountStatus) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $tbbankAccountId = $nextId;
        $tbbankAccountOwnerId = $bankAccount->getOwnerId();
        $tbbankAccountNumber = $bankAccount->getAccountNumber();
        $tbbankAccountBankName = $bankAccount->getBank();
        $tbbankAccountStatus = $bankAccount->getStatus();

        // Vincula los parámetros del statement
        $stmt->bind_param("iissi", $tbbankAccountId, $tbbankAccountOwnerId, $tbbankAccountNumber, $tbbankAccountBankName, $tbbankAccountStatus);

        // Ejecuta la declaración
        $result = $stmt->execute();
    
        // Cierra la declaración y la conexión
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }

    public function getAllTbBankAccount() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');

        $query = "SELECT * FROM tbbankaccount WHERE tbbankAccountIsActive=1;";
        $result = mysqli_query($conn, $query);

        $bankA = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $currentBankAccount = new BankAccount($row['tbbankAccountId'], $row['tbbankAccountOwnerId'], $row['tbbankAccountNumber'],
            $row['tbbankAccountBankName'], $row['tbbankAccountStatus']);
            array_push($bankA, $currentBankAccount);
        }

        mysqli_close($conn);
        return $bankA;
    }

    public function deleteTbBankAccount($idBankAccount) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbbankaccount SET tbbankAccountIsActive=0 where tbbankAccountId=" . $idBankAccount . ";";
        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function updateTbBankAccount($bankAccount) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $id = $bankAccount->getTbBankAccountId();
        $newAccountNumber = mysqli_real_escape_string($conn,  $bankAccount->getAccountNumber());
        $newBankName = mysqli_real_escape_string($conn,  $bankAccount->getBank());
        $newStatus = mysqli_real_escape_string($conn,  $bankAccount->getStatus());
    
        $query = "UPDATE tbbankaccount SET tbbankAccountNumber = '$newAccountNumber', tbbankAccountBankName = '$newBankName', tbbankAccountStatus = '$newStatus' WHERE tbbankAccountId = $id";
        $result = mysqli_query($conn, $query);
    
        mysqli_close($conn);
        return $result;
    }

    public function getTbBankAccount($idBankAccount) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbbankaccount WHERE tbbankAccountId = $idBankAccount";
        $result = mysqli_query($conn, $query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $bankAccountReturn = new BankAccount($row['tbbankAccountId'], $row['tbbankAccountOwnerId'], $row['tbbankAccountNumber'],
            $row['tbbankAccountBankName'], $row['tbbankAccountStatus']);
        } else {
            $bankAccountReturn = null;
        }
    
        mysqli_close($conn);
        return $bankAccountReturn;
    } 
}
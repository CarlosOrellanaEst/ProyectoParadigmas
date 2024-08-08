<?php

include_once 'data.php';
include_once '../domain/BankAccount.php';
include_once '../domain/Owner.php';

class BankAccountData extends Data {
    public function insertTbBankAccount($bankAccount) {
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
        $owner = $bankAccount->getOwner();
        $tbbankAccountOwnerId = $owner->getId();
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
}

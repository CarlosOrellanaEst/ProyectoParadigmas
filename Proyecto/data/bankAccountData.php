<?php

include_once 'data.php';
include '../domain/BankAccount.php';

class bankAccountData extends Data {
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
        $stmt = $conn->prepare($queryInsert); // el prepared statement de java
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $tbBankAccountId = $nextId;
        $ownerId = $bankAccount->getOwnerId();
        $accountNumber = $bankAccount->getAccountNumber();
        $bank = $bankAccount->getBank();
        $status = $bankAccount->getStatus();
    
        // Vincula los parámetros del statement
        $stmt->bind_param("iissi", $tbBankAccountId, $ownerId, $accountNumber, $bank, $status); // "iissi": cada letra es el tipo de dato de los parámetros
    
        // Ejecuta la declaración
        $result = $stmt->execute();
    
        // Cierra la declaración y la conexión
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }
}
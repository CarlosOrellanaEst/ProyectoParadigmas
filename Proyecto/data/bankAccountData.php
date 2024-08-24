<?php

include_once 'data.php';
include_once '../domain/BankAccount.php';

class BankAccountData extends Data {
    public function insertTbBankAccount($bankAccount) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
        }

        $conn->set_charset('utf8');

        $queryGetLastId = "SELECT MAX(tbbankaccountid) AS idtbbankAccount FROM tbbankaccount";
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

        $tbbankAccountOwnerId = $bankAccount->getOwnerId();
        $tbbankAccountNumber = $bankAccount->getAccountNumber();
        $tbbankAccountBankName = $bankAccount->getBank();
        $tbbankAccountStatus = $bankAccount->getStatus();
        $tbbankAccountId = $bankAccount->getTbBankAccountId();

        $exists = $this->getTbBankAccountByAccountNumber($bankAccount->getAccountNumber());
        if ($exists > 0) {
            if ($this->getTbBanKAccountExistsIsActive($exists)) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'El número de cuenta ya existe.'];
            } else {
                $queryInsert = "UPDATE tbbankaccount SET tbbankaccountownerid = ?, tbbankaccountbankname = ?, tbbankaccountstatus = 1 WHERE tbbankaccountid = ?";
                $stmt = $conn->prepare($queryInsert);
                
                if ($stmt === false) {
                    mysqli_close($conn);
                    return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
                }

                $stmt->bind_param("si", $description, $exists);
                $result = $stmt->execute();
                $stmt->close();
                mysqli_close($conn);

                if ($result) {
                    return ['status' => 'success', 'message' => 'Cuenta de banco registrada correctamenteee.'];
                } else {
                    return ['status' => 'error', 'message' => 'Falló al agregar ela cuenta bancaria: ' . $conn->error];
                }
            }
        } else {
            $queryInsert = "INSERT INTO tbbankaccount (tbbankaccountid, tbbankaccountownerid, tbbankaccountnumber, tbbankaccountbankname, tbbankaccountstatus) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($queryInsert);

            if ($stmt === false) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
            }

            $stmt->bind_param("iissi", $nextId, $tbbankAccountOwnerId, $tbbankAccountNumber, $tbbankAccountBankName, $tbbankAccountStatus);
            $result = $stmt->execute();
            $stmt->close();
            mysqli_close($conn);

            if ($result) {
                return ['status' => 'success', 'message' => 'Cuenta de banco añadida correctamente brrr'];
            } else {
                return ['status' => 'error', 'message' => 'Falló al agregar la cuenta de banco: ' . $conn->error];
            }
        }
    }

    /*class BankAccountData extends Data {
    public function insertTbBankAccount($bankAccount) {
        error_log($this->server);
        error_log($this->user);
        error_log($this->password);
        error_log($this->db);

        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
        }

        $conn->set_charset('utf8');

        // Obtiene el último id
        $queryGetLastId = "SELECT MAX(tbbankaccountid) AS idtbbankAccount FROM tbbankaccount";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;

        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }

        if ($this->getTbBankAccountByAccountNumber($bankAccount->getAccountNumber())) {
            $result = null;
        } else {
            $queryInsert = "INSERT INTO tbbankaccount (tbbankaccountid, tbbankaccountownerid, tbbankaccountnumber, tbbankaccountbankname, tbbankaccountstatus) VALUES (?, ?, ?, ?, ?)";
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
        }
        return $result;
    }*/ 

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
            $currentBankAccount = new BankAccount($row['tbbankaccountid'], $row['tbbankaccountownerid'], $row['tbbankaccountnumber'],
            $row['tbbankaccountbankname'], $row['tbbankaccountstatus']);
            array_push($bankA, $currentBankAccount);
        }

        mysqli_close($conn);
        return $bankA;
    }

    public function deleteTbBankAccount($idBankAccount) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbbankaccount SET tbbankAccountIsActive=0 where tbbankaccountid=" . $idBankAccount . ";";
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
    
        $newAccountNumber = mysqli_real_escape_string($conn, $bankAccount->getAccountNumber());
        $newBankName = mysqli_real_escape_string($conn, $bankAccount->getBank());
        $newStatus = mysqli_real_escape_string($conn, $bankAccount->getStatus());
    
        $currentAccount = $this->getTbBankAccountById($id);
    
        if ($currentAccount && $currentAccount->getAccountNumber() !== $newAccountNumber) {
            if ($this->getTbBankAccountByAccountNumber($newAccountNumber)) {
                mysqli_close($conn);
                return null;
            }
        }
    
        $query = "UPDATE tbbankaccount 
                  SET tbbankaccountnumber = '$newAccountNumber', tbbankaccountbankname = '$newBankName', tbbankaccountstatus = '$newStatus' 
                  WHERE tbbankaccountid = $id";
        $result = mysqli_query($conn, $query);
    
        mysqli_close($conn);
        return $result;
    }

    public function getTbBankAccountById($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbbankaccount WHERE tbbankaccountid = '$id'";
        $result = mysqli_query($conn, $query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $bankAccount = new BankAccount(
                $row['tbbankaccountid'],
                $row['ownerid'],
                $row['tbbankaccountnumber'],
                $row['tbbankaccountbankname'],
                $row['tbbankaccountstatus']
            );
            mysqli_close($conn);
            return $bankAccount;
        }
    
        mysqli_close($conn);
        return null;
    }
    

    /*public function updateTbBankAccount($bankAccount) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        if ($this->getTbBankAccountByAccountNumber($bankAccount->getAccountNumber())) {
            $result = null;
        } else {
            $id = $bankAccount->getTbBankAccountId();
            $newAccountNumber = mysqli_real_escape_string($conn,  $bankAccount->getAccountNumber());
            $newBankName = mysqli_real_escape_string($conn,  $bankAccount->getBank());
            $newStatus = mysqli_real_escape_string($conn,  $bankAccount->getStatus());
        
            $query = "UPDATE tbbankaccount SET tbbankaccountnumber = '$newAccountNumber', tbbankaccountbankname = '$newBankName', tbbankaccountstatus = '$newStatus' WHERE tbbankaccountid = $id";
            $result = mysqli_query($conn, $query);
    
            mysqli_close($conn);
        }
        return $result;
    } */

    public function getTbBankAccountByAccountNumber($accountNumber) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbbankaccount WHERE tbbankaccountnumber= '$accountNumber'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $rollReturn = true : $rollReturn = false;
    
        mysqli_close($conn);
        return $rollReturn;
    }

    public function getTbBanKAccountExistsIsActive($Id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbbankaccount WHERE tbbankaccountisactive=1 AND tbbankaccountid = $Id";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $rollReturn = true : $rollReturn = false;
    
        mysqli_close($conn);
        return $rollReturn;
    }
}
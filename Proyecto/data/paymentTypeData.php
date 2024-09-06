<?php

include_once 'data.php';
include_once '../domain/PaymentType.php';

class paymentTypeData extends Data {
    public function insertTbPaymentType($paymentType) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
        }

        $conn->set_charset('utf8');

        $queryGetLastId = "SELECT MAX(tbpaymenttypeid) AS idtbpaymenttype FROM tbpaymenttype";
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

        $tbOwnerId = $paymentType->getOwnerId();
        $accountNumber = $paymentType->getAccountNumber();
        $SinpeNumber = $paymentType->getSinpeNumber();
        $PaymentTypeStatus = $paymentType->getStatus();
        $PaymentTypeId = $paymentType->getTbPaymentTypeId();

        $exists = $this->getTbPaymentTypeByAccountNumber($paymentType->getAccountNumber());
        if ($exists > 0) {
            if ($this->getTbPaymentTypeExistsIsActive($exists)) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'El número de cuenta ya existe.'];
            } else {
                $queryInsert = "UPDATE tbpaymenttype SET tbownerid = ?, tbpaymenttypesinpenumber = ?, tbpaymenttypestatus = 1 WHERE tbpaymenttypeid = ?";
                $stmt = $conn->prepare($queryInsert);
                
                if ($stmt === false) {
                    mysqli_close($conn);
                    return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
                }

                $stmt->bind_param("si", $SinpeNumber, $exists);
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
            $queryInsert = "INSERT INTO tbpaymenttype (tbpaymenttypeid, tbownerid, tbpaymenttypenumber, tbpaymenttypesinpenumber) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($queryInsert);

            if ($stmt === false) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
            }

            $stmt->bind_param("iissi", $nextId, $tbOwnerId, $accountNumber, $SinpeNumber, $PaymentTypeStatus);
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

    public function getAllTbPaymentType() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');

        $query = "SELECT * FROM tbpaymenttype WHERE tbpaymenttypeisactive=1;";
        $result = mysqli_query($conn, $query);

        $bankA = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $currentPaymentType= new PaymentType($row['tbpaymenttypeid'], $row['tbownerid'], $row['tbpaymenttypenumber'],
            $row['tbpaymenttypesinpenumber'], $row['tbpaymenttypestatus']);
            array_push($bankA, $currentPaymentType);
        }

        mysqli_close($conn);
        return $bankA;
    }

    public function deleteTbPaymentTYpe($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbpaymenttype SET tbpaymenttypeisactive=0 where tbpaymenttypeid=" . $id . ";";
        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function updateTbPaymentType($paymentType) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8');
    
        $id = $paymentType->getTbPaymentTypeId();
    
        $newAccountNumber = mysqli_real_escape_string($conn, $paymentType->getAccountNumber());
        $newSinpeNumber = mysqli_real_escape_string($conn, $paymentType->getSinpeNumber());
        $newStatus = mysqli_real_escape_string($conn, $paymentType->getStatus());
    
        $currentAccount = $this->getTbPaymentTypeById($id);
    
        if ($currentAccount && $currentAccount->getAccountNumber() !== $newAccountNumber) {
            if ($this->getTbPaymentTypeByAccountNumber($newAccountNumber)) {
                mysqli_close($conn);
                return null;
            }
        }
    
        $query = "UPDATE tbpaymenttype 
                  SET tbpaymenttypenumber = '$newAccountNumber', tbpaymenttypesinpenumber = '$newSinpeNumber', tbpaymenttypestatus = '$newStatus' 
                  WHERE tbpaymenttypeid = $id";
        $result = mysqli_query($conn, $query);
    
        mysqli_close($conn);
        return $result;
    }

    public function getTbPaymentTypeById($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbpaymenttype WHERE tbpaymenttypeid = '$id'";
        $result = mysqli_query($conn, $query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $paymentType = new PaymentType(
                $row['tbpaymenttypeid'],
                $row['tbownerid'],
                $row['tbpaymenttypenumber'],
                $row['tbpaymenttypesinpenumber'],
                $row['tbpaymenttypestatus']
            );
            mysqli_close($conn);
            return $paymentType;
        }
    
        mysqli_close($conn);
        return null;
    }
    
    public function getTbPaymentTypeByAccountNumber($paymentTypeNumber) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbpaymenttype WHERE tbpaymenttypenumber= '$paymentTypeNumber'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $rollReturn = true : $rollReturn = false;
    
        mysqli_close($conn);
        return $rollReturn;
    }

    public function getTbPaymentTypeExistsIsActive($Id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbpaymenttype WHERE tbpaymenttypeisactive=1 AND tbpaymenttypeid = $Id";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $rollReturn = true : $rollReturn = false;
    
        mysqli_close($conn);
        return $rollReturn;
    }
}
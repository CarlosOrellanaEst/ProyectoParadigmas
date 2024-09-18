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

        $accountNumber = $paymentType->getAccountNumber();
        $SinpeNumber = $paymentType->getSinpeNumber();
    
        $queryCheckAccount = "SELECT tbpaymenttypeisactive FROM tbpaymenttype WHERE tbpaymenttypenumber = ? AND tbpaymenttypeisactive = 1";
        $stmtCheckAccount = $conn->prepare($queryCheckAccount);
        if ($stmtCheckAccount === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
        }
    
        $stmtCheckAccount->bind_param("s", $accountNumber);
        $stmtCheckAccount->execute();
        $stmtCheckAccount->bind_result($activeStatus);
        $stmtCheckAccount->fetch();
        $stmtCheckAccount->close();
    
        if ($activeStatus == 1) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'El número de cuenta ya está registrado y activo.'];
        }
    
        if (!empty($SinpeNumber)) {
            $queryCheckSinpe = "SELECT tbpaymenttypeisactive FROM tbpaymenttype WHERE tbpaymenttypesinpenumber = ? AND tbpaymenttypeisactive = 1";
            $stmtCheckSinpe = $conn->prepare($queryCheckSinpe);
            if ($stmtCheckSinpe === false) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
            }
    
            $stmtCheckSinpe->bind_param("s", $SinpeNumber);
            $stmtCheckSinpe->execute();
            $stmtCheckSinpe->bind_result($activeStatusSinpe);
            $stmtCheckSinpe->fetch();
            $stmtCheckSinpe->close();
    
            if ($activeStatusSinpe == 1) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'El número de SINPE ya está registrado y activo.'];
            }
        }
    
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
    
        $queryInsert = "INSERT INTO tbpaymenttype (tbpaymenttypeid, tbownerid, tbpaymenttypenumber, tbpaymenttypesinpenumber) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
    
        if ($stmt === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
        }
    
        $stmt->bind_param("iiss", $nextId, $tbOwnerId, $accountNumber, $SinpeNumber);
        $result = $stmt->execute();
        $stmt->close();
        mysqli_close($conn);
    
        if ($result) {
            return ['status' => 'success', 'message' => 'Cuenta de banco añadida correctamente.'];
        } else {
            return ['status' => 'error', 'message' => 'Falló al agregar la cuenta de banco: ' . $conn->error];
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
    
        $queryCheckAccount = "SELECT tbpaymenttypeid FROM tbpaymenttype 
                              WHERE tbpaymenttypenumber = ? AND tbpaymenttypeid != ? AND tbpaymenttypeisactive = 1";
        $stmtCheckAccount = $conn->prepare($queryCheckAccount);
        $stmtCheckAccount->bind_param("si", $newAccountNumber, $id);
        $stmtCheckAccount->execute();
        $stmtCheckAccount->bind_result($existingAccountId);
        $stmtCheckAccount->fetch();
        $stmtCheckAccount->close();
    
        if ($existingAccountId) {
            mysqli_close($conn);
            return null; 
        }
    
        if (!empty($newSinpeNumber)) {
            $queryCheckSinpe = "SELECT tbpaymenttypeid FROM tbpaymenttype 
                                WHERE tbpaymenttypesinpenumber = ? AND tbpaymenttypeid != ? AND tbpaymenttypeisactive = 1";
            $stmtCheckSinpe = $conn->prepare($queryCheckSinpe);
            $stmtCheckSinpe->bind_param("si", $newSinpeNumber, $id);
            $stmtCheckSinpe->execute();
            $stmtCheckSinpe->bind_result($existingSinpeId);
            $stmtCheckSinpe->fetch();
            $stmtCheckSinpe->close();
    
            if ($existingSinpeId) {
                mysqli_close($conn);
                return null; 
            }
        }
    
        $query = "UPDATE tbpaymenttype 
                  SET tbpaymenttypenumber = ?, 
                      tbpaymenttypesinpenumber = " . (!empty($newSinpeNumber) ? "'$newSinpeNumber'" : "NULL") . ", 
                      tbpaymenttypestatus = ? 
                  WHERE tbpaymenttypeid = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $newAccountNumber, $newStatus, $id);
        $result = $stmt->execute();
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }

    public function getTbPaymentTypeBySinpeNumber($sinpeNumber) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8');
    
        $sinpeNumber = mysqli_real_escape_string($conn, $sinpeNumber);
    
        $query = "SELECT * FROM tbpaymenttype WHERE tbpaymenttypesinpenumber = '$sinpeNumber' LIMIT 1";
        $result = mysqli_query($conn, $query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $paymentType = new PaymentType($row['tbpaymenttypeid'], 0, $row['tbpaymenttypenumber'], $row['tbpaymenttypesinpenumber'], $row['tbpaymenttypestatus']);
            mysqli_close($conn);
            return $paymentType;
        }
    
        mysqli_close($conn);
        return null;
    }

    public function getTbPaymentTypeByOwnerID($ownerId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8');
    
        $ownerId = mysqli_real_escape_string($conn, $ownerId);
    
        $query = "SELECT * FROM tbpaymenttype WHERE tbownerid = '$ownerId'";
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
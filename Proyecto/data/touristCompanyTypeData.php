<?php

include_once 'data.php';
include_once '../domain/TouristCompanyType.php';

class touristCompanyTypeData extends Data {
    public function insertTbTouristCompanyType($companyType) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
        }
    
        $conn->set_charset('utf8');

        $queryGetLastId = "SELECT MAX(tbtouristcompanytypeid) AS idtbtouristcompanytype FROM tbtouristcompanytype";
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
    
        $tbtouristcompanytypename = $companyType->getName();
        $tbtouristcompanytypedescription = $companyType->getDescription();
    
        $queryCheckAccount = "SELECT tbtouristcompanytypeisactive FROM tbtouristcompanytype WHERE tbtouristcompanytypename = ? AND tbtouristcompanytypeisactive = 1";
        $stmtCheckAccount = $conn->prepare($queryCheckAccount);
        if ($stmtCheckAccount === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
        }

        $stmtCheckAccount->bind_param("s", $tbtouristcompanytypename);
        $stmtCheckAccount->execute();
        $stmtCheckAccount->bind_result($activeStatus);
        $stmtCheckAccount->fetch();
        $stmtCheckAccount->close();

        if ($activeStatus == 1) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'El nombre ya está registrado y activo.'];
        }

        $queryInsert = "INSERT INTO tbtouristcompanytype (tbtouristcompanytypeid, tbtouristcompanytypename, tbtouristcompanytypedescription) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);

        if ($stmt === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
        }

        $stmt->bind_param("iss", $nextId, $tbtouristcompanytypename, $tbtouristcompanytypedescription);
        $result = $stmt->execute();
        $stmt->close();
        mysqli_close($conn);

        if ($result) {
            // Si la actualización es exitosa, devolver un mensaje de éxito
            return ['status' => 'success', 'message' => 'Tipo de empresa turística registrada correctamente.'];
        } else {
            // Si la actualización falla, devolver un mensaje de error
            return ['status' => 'error', 'message' => 'Falló al agregar el tipo de empresa turística: ' . $conn->error];
        }

    }
    
    /*public function insertTbTouristCompanyType($companyType) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
        }

        $conn->set_charset('utf8');

        $queryGetLastId = "SELECT MAX(tbtouristcompanytypeid) AS idtbtouristcompanytype FROM tbtouristcompanytype";
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

        $tbtouristcompanytypename = $companyType->getName();
        $tbtouristcompanytypedescription = $companyType->getDescription();

        $exists = $this->getTbTouristCompanyTypeByName($companyType->getName());
        
        if ($exists > 0) {
            if ($this->getTbTouristCompanyTypeExistsIsActive($exists)) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'El nombre de la actividad ya existe.'];
            } else {
                $queryInsert = "UPDATE tbtouristcompanytype SET tbtouristcompanytypename = ?, tbtouristcompanytypedescription = ?, tbtouristcompanytypeisactive = 1 WHERE tbtouristcompanytypeid = ?";
                $stmt = $conn->prepare($queryInsert);
                
                if ($stmt === false) {
                    mysqli_close($conn);
                    return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
                }

                $stmt->bind_param("ssi", $tbtouristcompanytypename, $tbtouristcompanytypedescription, $exists);
                $result = $stmt->execute();
                $stmt->close();
                mysqli_close($conn);

                if ($result) {
                    return ['status' => 'success', 'message' => 'Tipo de empresa turística registrada correctamenteee.'];
                } else {
                    return ['status' => 'error', 'message' => 'Falló al agregar el tipo de empresa turística: ' . $conn->error];
                }
            }
        } else {
            $queryInsert = "INSERT INTO tbtouristcompanytype (tbtouristcompanytypeid, tbtouristcompanytypename, tbtouristcompanytypedescription, tbtouristcompanytypeisactive) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($queryInsert);

            if ($stmt === false) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
            }
            $isActive = 1;
            $stmt->bind_param("issi", $nextId, $tbtouristcompanytypename, $tbtouristcompanytypedescription, $isActive);
            $result = $stmt->execute();
            $stmt->close();
            mysqli_close($conn);

            if ($result) {
                return ['status' => 'success', 'message' => 'Tipo de empresa turística añadida correctamente brrr'];
            } else {
                return ['status' => 'error', 'message' => 'Falló al agregar el tipo de empresa turística: ' . $conn->error];
            }
        }
    } */
    
   public function getTbTouristCompanyTypeExistsIsActive($Id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbtouristcompanytype WHERE tbtouristcompanytypeisactive=1 AND tbtouristcompanytypeid = $Id";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $rollReturn = true : $rollReturn = false;
    
        mysqli_close($conn);
        return $rollReturn;
    }
    /*public function insertTbTouristCompanyType($companyType) {
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
        $queryGetLastId = "SELECT MAX(tbtouristcompanytypeid) AS idtbtouristcompanytype FROM tbtouristcompanytype";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;

        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }

        if ($this->getTbTouristCompanyTypeByName($companyType->getName())) {
            $result = null;
        } else {
            $queryInsert = "INSERT INTO tbtouristcompanytype (tbtouristcompanytypeid, tbtouristcompanytypename, tbtouristcompanytypedescription) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($queryInsert);
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
        
            $tbtouristcompanytypeid = $nextId;
            $tbtouristcompanytypename = $companyType->getName();
            $tbtouristcompanytypedescription = $companyType->getDescription();

            // Vincula los parámetros del statement
            $stmt->bind_param("iss", $tbtouristcompanytypeid, $tbtouristcompanytypename, $tbtouristcompanytypedescription);

            // Ejecuta la declaración
            $result = $stmt->execute();
        
            // Cierra la declaración y la conexión
            $stmt->close();
            mysqli_close($conn);
        }
        return $result;
    } */

    public function getAllTbTouristCompanyType() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');

        $query = "SELECT * FROM tbtouristcompanytype WHERE tbtouristcompanytypeisactive=1;";
        $result = mysqli_query($conn, $query);

        $companyTypeList = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $companyType = new touristCompanyType($row['tbtouristcompanytypeid'], $row['tbtouristcompanytypename'], $row['tbtouristcompanytypedescription']);
            array_push($companyTypeList, $companyType);
        }

        mysqli_close($conn);
        return $companyTypeList;
    }

    public function deleteTbTouristCompanyType($idTouristCompanyType) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbtouristcompanytype SET tbtouristcompanytypeisactive=0 where tbtouristcompanytypeid=" . $idTouristCompanyType . ";";
        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function updateTbTouristCompanyType($TouristCompanyType) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $id = (int)$TouristCompanyType->getId();
        $newName = $TouristCompanyType->getName();
        $newDescription = $TouristCompanyType->getDescription();
        
        $queryCheckAccount = "SELECT tbtouristcompanytypeid FROM tbtouristcompanytype 
                              WHERE tbtouristcompanytypename = ? AND tbtouristcompanytypeid != ? AND tbtouristcompanytypeisactive = 1";
        $stmtCheckAccount = $conn->prepare($queryCheckAccount);
        $stmtCheckAccount->bind_param("si", $newName, $id);
        $stmtCheckAccount->execute();
        $stmtCheckAccount->bind_result($existingAccountId);
        $stmtCheckAccount->fetch();
        $stmtCheckAccount->close();

        if ($existingAccountId) {
            mysqli_close($conn);
            return null; // El número de cuenta ya está registrado y activo en otro registro
        }
        
        $query = "UPDATE tbtouristcompanytype 
                  SET tbtouristcompanytypename = ?, 
                      tbtouristcompanytypedescription = " . (!empty($newDescription) ? "'$newDescription'" : "NULL") . "
                  WHERE tbtouristcompanytypeid = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $newName, $id);
        $result = $stmt->execute();
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }
    

    /*public function updateTbTouristCompanyType($TouristCompanyType) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $id = $TouristCompanyType->getId();

        $newName = mysqli_real_escape_string($conn,  $TouristCompanyType->getName());
        $newDescription = mysqli_real_escape_string($conn,  $TouristCompanyType->getDescription());
        
        $currentName = $this->getByIdTbTouristCompanyType($id);

        if ($currentName && $currentName->getName() !== $newName) {
            if ($this->getTbTouristCompanyTypeByName($newName)) {
                mysqli_close($conn);
                return null;
            }
        }

        $query = "UPDATE tbtouristcompanytype 
                  SET tbtouristcompanytypename = '$newName', tbtouristcompanytypedescription = '$newDescription'
                  WHERE tbtouristcompanytypeid = $id";
        $result = mysqli_query($conn, $query);
    
        mysqli_close($conn);
        return $result;
    } */

    public function getTbTouristCompanyTypeByName($companyTypeName) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbtouristcompanytype WHERE tbtouristcompanytypename= '$companyTypeName'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $rollReturn = true : $rollReturn = false;
    
        mysqli_close($conn);
        return $rollReturn;
    } 

    public function getByIdTbTouristCompanyType($idTouristCompanyType) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbtouristcompanytype WHERE tbtouristcompanytypeid= $idTouristCompanyType";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);
        $companyType = new touristCompanyType($row['tbtouristcompanytypeid'], $row['tbtouristcompanytypename'], $row['tbtouristcompanytypedescription']);
    
        mysqli_close($conn);
        return $companyType;
    }

    
}
<?php

include_once 'data.php';
include_once '../domain/TouristCompanyType.php';

class touristCompanyTypeData extends Data {
    public function insertTbTouristCompanyType($companyType) {
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
    }

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
            $companyType = new TouristCompanyType($row['tbtouristcompanytypeid'], $row['tbtouristcompanytypename'], $row['tbtouristcompanytypedescription']);
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
    
        $id = $TouristCompanyType->getId();
        $newName = mysqli_real_escape_string($conn,  $TouristCompanyType->getName());
        $newDescription = mysqli_real_escape_string($conn,  $TouristCompanyType->getDescription());
    
        $query = "UPDATE tbtouristcompanytype SET tbtouristcompanytypename = '$newName', tbtouristcompanytypedescription = '$newDescription' WHERE tbtouristcompanytypeid = $id";
        $result = mysqli_query($conn, $query);
    
        mysqli_close($conn);
        return $result;
    }

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
}
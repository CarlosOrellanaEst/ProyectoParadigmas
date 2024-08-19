<?php

include_once 'data.php';
include_once '../domain/TouristCompany.php';
class TouristCompanyData extends Data{

    public function insertTouristCompany($touristCompany) {

        error_log($this->server);
        error_log($this->user);
        error_log($this->password);
        error_log($this->db);

        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $conn->set_charset('utf8');

        $queryGetLastId = "SELECT MAX(tbtouristcompanyid) AS tbtouristcompanyid FROM tbtouristcompany";

        $idCont = mysqli_query($conn, $queryGetLastId);

        $nextId = 1;

        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }

        if($this->getTouristCompanyByName($touristCompany->getLegalName())->getLegalName()=== $touristCompany->getLegalName()){
             return $result = null;
        }


        $queryInsert = "INSERT INTO tbtouristcompany (tbtouristcompanyid, tbtouristcompanylegalname, tbtouristcompanymagicname, tbtouristcompanyowner, tbtouristcompanycompanyType, tbtouristcompanystatus) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $tbtouristcompanyid = $nextId;
        $tbtouristcompanylegalname = $touristCompany->getLegalName();
        $tbtouristcompanymagicname = $touristCompany->getMagicName();
        $tbtouristcompanyowner = $touristCompany->getOwner();
        $tbtouristcompanycompanyType = $touristCompany->getCompanyType();
        $tbtouristcompanystatus = $touristCompany->getStatus();

        $stmt->bind_param("issiii", $tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbtouristcompanystatus);

        $result = $stmt->execute();

        $stmt->close();

        mysqli_close($conn);

        return $result;
   
    }

    public function getAllTouristCompanies() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');

        $query = "SELECT * FROM tbtouristcompany WHERE tbtouristcompanystatus=1;";

        $result = mysqli_query($conn, $query);

        $touristCompanies = array();

        while ($row = mysqli_fetch_array($result)) {
            $touristCompanies[] = new TouristCompany($row['tbtouristcompanyid'], $row['tbtouristcompanylegalname'], $row['tbtouristcompanymagicname'], $row['tbtouristcompanyowner'], $row['tbtouristcompanycompanyType'], $row['tbtouristcompanystatus']);
        }

        mysqli_close($conn);

        return $touristCompanies;
    }

    public function deleteTouristCompany($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');

        $query = "UPDATE tbtouristcompany SET tbtouristcompanystatus=0 WHERE tbtouristcompanyid=?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $id);

        $result = $stmt->execute();

        $stmt->close();

        mysqli_close($conn);

        return $result;
    }

    public function updateTouristCompany($touristCompany) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');

        $query = "UPDATE tbtouristcompany SET tbtouristcompanylegalname=?, tbtouristcompanymagicname=?, tbtouristcompanyowner=?, tbtouristcompanycompanyType=?, tbtouristcompanystatus=? WHERE tbtouristcompanyid=?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        if($this->getTouristCompanyByName($touristCompany->getLegalName())->getLegalName()=== $touristCompany->getLegalName()){
            return $result = null;
       }

        $tbtouristcompanyid = $touristCompany->getId();
        $tbtouristcompanylegalname = $touristCompany->getLegalName();
        $tbtouristcompanymagicname = $touristCompany->getMagicName();
        $tbtouristcompanyowner = $touristCompany->getOwner();
        $tbtouristcompanycompanyType = $touristCompany->getCompanyType();
        $tbtouristcompanystatus = $touristCompany->getStatus();

        $stmt->bind_param("ssiiii", $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbtouristcompanystatus, $tbtouristcompanyid);

        $result = $stmt->execute();

        $stmt->close();

        mysqli_close($conn);

        return $result;
    }

    public function getTouristById($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');

        $query = "SELECT * FROM tbtouristcompany WHERE tbtouristcompanyid=?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->bind_result($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbtouristcompanystatus);

        $stmt->fetch();

        $touristCompany = new TouristCompany($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbtouristcompanystatus);

        $stmt->close();

        mysqli_close($conn);

        return $touristCompany;
    }

    public function getTouristCompanyByName($touristCompanyName){
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');

        $query = "SELECT * FROM tbtouristcompany WHERE tbtouristcompanylegalname=?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $touristCompanyName);

        $stmt->execute();

        $stmt->bind_result($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbtouristcompanystatus);

        $stmt->fetch();

        $touristCompany = new TouristCompany($tbtouristcompanyid, $tbtouristcompanylegalname, $tbtouristcompanymagicname, $tbtouristcompanyowner, $tbtouristcompanycompanyType, $tbtouristcompanystatus);

        $stmt->close();

        mysqli_close($conn);

        return $touristCompany;
    }

    


}

<?php

include_once 'data.php';
include '../domain/bull.php';

class BullData extends Data {

    public function insertTBBull($bull) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        //Get the last id
        $queryGetLastId = "SELECT MAX(idtbbull) AS idtbbull  FROM tbbull";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;

        if ($row = mysqli_fetch_row($idCont)) {
            $nextId = trim($row[0]) + 1;
        }
        $queryInsert = "INSERT INTO tbbull VALUES (" . $nextId . ",'" .
                $bull->getCodeTBBull() . "','" .
                $bull->getNameTBBull() . "','" .
                $bull->getCommercialCaseTBBull() . "','" .
                $bull->getBuyDateTBBull() . "'," .
                $bull->getStrawsQuantityTBBull() . "," .
                $bull->getStrawsPriceTBBull() . "," .
                $bull->getRanchTBBull() . ");";

        $result = mysqli_query($conn, $queryInsert);
        mysqli_close($conn);
        return $result;
    }

    public function updateTBBull($bull) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');
        $queryUpdate = "UPDATE tbbull SET codebull='" . $bull->getCodeTBBull() .
                "', namebull='" . $bull->getNameTBBull() .
                "', commercialcase='" . $bull->getCommercialCaseTBBull() .
                "', buydate='" . $bull->getBuyDateTBBull() .
                "',strawsquantity=" . $bull->getStrawsQuantityTBBull() .
                ",strawsprice=" . $bull->getStrawsPriceTBBull() .
                " WHERE idtbbull=" . $bull->getIdTBBull() . ";";

        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function deleteTBBull($idBull) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "DELETE from tbbull where idtbbull=" . $idBull . ";";
        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    }

    public function getAllTBBull() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "SELECT * FROM tbbull;";
        $result = mysqli_query($conn, $querySelect);
        mysqli_close($conn);
        $bulls = [];
        while ($row = mysqli_fetch_array($result)) {
            $currentBull = new Bull($row['idtbbull'], $row['codebull'], $row['ranch'], $row['namebull'], $row['commercialcase'], $row['buydate'], $row['strawsquantity'], $row['strawsprice']);
            array_push($bulls, $currentBull);
        }
        return $bulls;
    }
    
    public function getBullsInventary() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $querySelect = "select tbbull.idtbbull as 'idtbbull', CONCAT(tbbull.namebull, "
                . "' - ', tbbull.codebull) as 'bull', tbbull.strawsquantity as "
                . "'strawsquantity' from tbbull group by tbbull.idtbbull; ";
        $result = mysqli_query($conn, $querySelect);
        
        $bulls = [];
        while ($row = mysqli_fetch_array($result)) {
            $currentBull = array('idtbbull' => $row['idtbbull'], 
                'bull' => $row['bull'], 
                'strawsquantity' => $row['strawsquantity']);
            array_push($bulls, $currentBull);
        }
        
        $newBulls = [];
        foreach ($bulls as $currentBull) {
            $querySelect = "select sum(tbinsemination.strawsquantity) as 'strawsquantity' " .
            "from tbinsemination where bull =" . $currentBull['idtbbull'] . " group by tbinsemination.bull;";
            $result = mysqli_query($conn, $querySelect);
            $row = mysqli_fetch_array($result);
            $quantityStrawsUse = $row[0];
            $currentBull['strawsquantity'] = $currentBull['strawsquantity'] - $quantityStrawsUse;
            array_push($newBulls, $currentBull);
        }
        
        mysqli_close($conn);
        return $newBulls;
        
    }

}

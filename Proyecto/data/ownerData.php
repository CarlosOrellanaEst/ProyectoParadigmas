<?php

include_once 'data.php';
include_once '../domain/Owner.php';

class ownerData extends Data {
    public function getAllTBOwners() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
        
        $query = "SELECT * FROM tbowner WHERE tbownerstatus = 1;";
        $result = mysqli_query($conn, $query);
        
        $owners = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $currentOwner = new Owner(
                $row['tbownerid'],
                $row['tbownerdirection'],
                $row['tbownername'],
                $row['tbownersurnames'],
                $row['tbownerlegalidentification'],
                $row['tbownerphone'],
                $row['tbowneremail'],
                $row['tbownerstatus']
            );
            array_push($owners, $currentOwner);
        }
        
        mysqli_close($conn);
        return $owners;
    }
}
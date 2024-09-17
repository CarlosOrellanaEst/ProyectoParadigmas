<?php
require_once 'data.php';
require 'ownerData.php';
require_once '../domain/User.php'; 

class LoginData extends Data {

    public function getUserByUsername ($username, $password) { // getUserByNickName deberia ser el nombre del metodo
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $query = "SELECT * FROM tbuser WHERE tbusernickname = ? AND tbuserpassword = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $user = new User();            
            $user->setUserID($row['tbuserid']);
            $user->setName($row['tbusername']);
            $user->setSurnames($row['tbusersurnames']);
            $user->setLegalIdentification($row['tbuserlegalidentification']);
            $user->setPhone($row['tbuserphone']);
            $user->setEmail($row['tbuseremail']);
            $user->setNickname($row['tbusernickname']);
            $user->setPassword($row['tbuserpassword']);
            $user->setActive($row['tbuserstatus']);
            // podria ser un objeto roll, pero eso necesita el innerjoin con tbroll que quiero evitar
            if ($row['tbrollid'] == 1) {
                $user->setUserType("Administrador");
            } else if ($row['tbrollid'] == 2) {
                $user->setUserType("Turista");
            } else if ($row['tbrollid'] == 3) {
                // ir a traerme los de tbowner con el valor de tbuserid
                $user->setUserType("Propietario");
                $ownerData = new ownerData();
                $ownerDB = $ownerData->getTBOwnerByUserId($row['tbuserid']);
                $finalOwner = new Owner (
                    $ownerDB->getIdTBOwner(),            
                    $ownerDB->getDirectionTBOwner(),
                    $ownerDB->getPhotoURLTBOwner(),
                    $ownerDB->getStatusTBOwner(),
                    $user->getId(),
                    $user->getNickname(),
                    $user->getPassword(),
                    $user->getActive(),
                    $user->getUserType(),
                    $user->getName(),
                    $user->getSurnames(),
                    $user->getLegalIdentification(),
                    $user->getPhone(),
                    $user->getEmail(), 
                );   
                // asigno que el usuario loggeado es una instancia de propietario
                $user = $finalOwner;
        //        echo ($user);
            }
        } else {
            $user = null;
        }
        $stmt->close();
        return $user;
    }
}

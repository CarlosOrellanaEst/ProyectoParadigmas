<?php
require_once 'data.php'; // Incluir la clase Data
require_once '../domain/user.php'; // Incluir la clase User

class LoginData extends Data {

    public function getUserByUsername($username, $password) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db); // Usar el método de conexión de la clase Data

        $query = "SELECT * FROM tbuser WHERE tbusername = ? AND tbuserpassword = ? LIMIT 1";
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
            $user->setUserName($row['tbusername']);
            $user->setUserLastName($row['tbuserlastname']);
            $user->setPassword($row['tbuserpassword']);
            $user->setPhone($row['tbuserphone']);
            $user->setActive($row['tbuserstatus']);
            $user->setUserType($row['tbusertype']);
        } else {
            $user = null;
        }

        $stmt->close();

        return $user;
    }
}

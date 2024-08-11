<?php
require_once 'data.php'; // Incluir la clase Data
require_once '../domain/user.php'; // Incluir la clase User
class LoginData {
    private $connection;

    public function __construct() {
        $db = new Data();
        $this->connection = $db->connect(); // Establecer la conexión a la base de datos
    }

    public function getUserByUsername($username, $password) {
        $query = "SELECT * FROM tbuser WHERE tbuserName = ? AND tbuserpassword = ? LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $user = new User();
            $user->setUserID($row['tbuserid']);
            $user->setUserName($row['tbuserName']);
            $user->setUserLastName($row['tbuserLastName']);
            $user->setPassword($row['tbuserpassword']);
            $user->setPhone($row['tbuserphone']);
            $user->setActive($row['tbuserStatus']);
            $user->setUserType($row['tbuserType']);
        } else {
            $user = null;
        }
        
        $stmt->close();
        return $user;
    }
    
    
    

    public function __destruct() {
        $this->connection->close(); // Cerrar la conexión al final
    }
}


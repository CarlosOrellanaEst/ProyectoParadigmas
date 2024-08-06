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
        $query = "SELECT * FROM tbuser WHERE tbuserName = ? AND tbpassword = ? LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        //Revisar si trae el objeto completo y estudiarlo...
        $user = $result->fetch_assoc();
        $stmt->close();
    
        return $user ? $user : null;
    }
    
    

    public function __destruct() {
        $this->connection->close(); // Cerrar la conexión al final
    }
}


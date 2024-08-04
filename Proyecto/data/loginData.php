<?php
require_once 'data.php'; // Incluir la clase Data

class LoginData {
    private $connection;

    public function __construct() {
        $db = new Data();
        $this->connection = $db->connect(); // Establecer la conexión a la base de datos
    }

    public function getUserByUsername($username) {
        $query = "SELECT * FROM tbuser WHERE userName = ? LIMIT 1";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function __destruct() {
        $this->connection->close(); // Cerrar la conexión al final
    }
}
?>

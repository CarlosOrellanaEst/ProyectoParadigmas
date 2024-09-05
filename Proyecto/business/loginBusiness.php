<?php
require_once '../data/loginData.php'; // Ajusta la ruta si es necesario

class LoginBusiness {
    private $loginData;

    public function __construct() {
        $this->loginData = new LoginData();
    }

    public function authenticate($username, $password) {
        // Hashear la contraseña con SHA-256
        $hashedText = hash('sha256', $password);
        // Obtener el usuario por nombre de usuario y contraseña hasheada
        $user = $this->loginData->getUserByUsername($username, $hashedText);
    
        // Retornar el objeto User si se encontró, o null si no
        return $user !== null ? $user : null;
    }
    
}

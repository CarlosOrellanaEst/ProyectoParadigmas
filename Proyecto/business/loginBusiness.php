<?php
require_once '../data/loginData.php'; // Ajusta la ruta si es necesario

class LoginBusiness {
    private $loginData;

    public function __construct() {
        $this->loginData = new LoginData();
    }

    public function authenticate($username, $password) {
        $hashedText = hash('sha256', $password);
        $user = $this->loginData->getUserByUsername($username, $hashedText);
        return $user;
    }
}

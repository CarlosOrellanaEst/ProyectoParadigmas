<?php
require_once '../data/loginData.php'; // Ajusta la ruta si es necesario

class LoginBusiness {
    private $loginData;

    public function __construct() {
        $this->loginData = new LoginData();
    }

    public function authenticate($username, $password) {
        $user = $this->loginData->getUserByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }
}

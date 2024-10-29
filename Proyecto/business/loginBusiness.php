<?php
require_once '../data/loginData.php'; // Ajusta la ruta si es necesario

class LoginBusiness {
    private $loginData;

    public function __construct() {
        $this->loginData = new LoginData();
    }

    public function authenticate($username, $password) {
        $user = $this->loginData->getUserByUsername($username);
      
        if ($user === null) {
            return null;
        }
      
        // Verificar la contraseña usando password_verify
        if (password_verify($password, $user->getPassword())) {
            return $user;
        } else {
            return null;
        }
    }   
}

<?php
require_once '../data/loginData.php'; // Ajusta la ruta si es necesario

class LoginBusiness {
    private $loginData;

    public function __construct() {
        $this->loginData = new LoginData();
    }

    public function authenticate($username, $password) {
        // Obtener el usuario por nombre de usuario sin verificar la contraseña aún
        $user = $this->loginData->getUserByUsername($username, $password);
    
      
        if ($user === null) {
            return null;
        }
    
        // Verificar la contraseña usando password_verify
        if (password_verify($password, $user->getPassword())) {
            // Devolver el objeto User (Owner, User o Admin) si la contraseña es correcta
            return $user;
        } else {
            
            return null;
        }
    }
    
    
}

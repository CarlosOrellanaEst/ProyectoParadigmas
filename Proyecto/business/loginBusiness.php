<?php
require_once 'data/data.php';

class LoginBusiness {
    private $loginData;

    public function __construct() {
        $this->loginData = new data();
    }

    public function authenticate($username, $password) {
        // Obtener el usuario por el nombre de usuario
        $user = $this->loginData->getUserByUsername($username);

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Retornar los datos del usuario si la autenticación es exitosa
        }
        return null; // Retornar null si la autenticación falla
    }
}
?>

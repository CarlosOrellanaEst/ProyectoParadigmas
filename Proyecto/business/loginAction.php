<?php
require_once './loginBusiness.php';
require '../utils/utils.php';


session_start();

header('Content-Type: application/json');

$response = array();

if (isset($_POST['create'])) {
    $username = $_POST['userName'];
    $password = $_POST['password'];

    $loginBusiness = new LoginBusiness();
    $user = $loginBusiness->authenticate($username, $password);

    if ($user) {
        $_SESSION['user'] = $user;
        $response['success'] = true;
        $response['message'] = "Bienvenido";       
        $response['userType'] = $user->getUserType(); // Accede al tipo de usuario usando el método getUserType()
        Utils::$userLogged = $user;
        error_log("User Type: " . $response['userType']);
    } else {
        $response['success'] = false;
        $response['message'] = "Usuario o contraseña incorrecto";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Datos incompletos";
}

echo json_encode($response);
exit();



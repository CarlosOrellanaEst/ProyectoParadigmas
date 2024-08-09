<?php
require_once 'C:/xampp/htdocs/ProyectoParadigmas/Proyecto/business/loginBusiness.php';


session_start();

header('Content-Type: application/json');

$response = array();

if (isset($_POST['userName']) && isset($_POST['password'])) {
    $username = $_POST['userName'];
    $password = $_POST['password'];

    $loginBusiness = new LoginBusiness();
    $user = $loginBusiness->authenticate($username, $password);

    if ($user) {
        $_SESSION['user'] = $user;
        $response['success'] = true;
        $response['message'] = "Bienvenido";       
        $response['userType'] = $user->getUserType(); // Accede al tipo de usuario usando el método getUserType()
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



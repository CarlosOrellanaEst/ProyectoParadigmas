<?php

require_once './loginBusiness.php';
require '../utils/utils.php';

 session_start(); 

header('Content-Type: application/json');

$response = array();

if (isset($_POST['nickName']) && isset($_POST['password'])) {
    $nickName = $_POST['nickName'];
    $password = $_POST['password'];
    $_SESSION['nickname'] = $nickName;
    $_SESSION['password'] = $password;

    $loginBusiness = new LoginBusiness();
    $user = $loginBusiness->authenticate($nickName, $password);
    // echo($user);
    if ($user !== null) {
        $_SESSION['user'] = $user;
        $response['success'] = true;
        $response['message'] = "Bienvenido " . $user->getName();       
        $response['userType'] = $user->getUserType();
        $_SESSION['userType'] = $user->getUserType();
        Utils::setUserLogged($user);
   //     error_log("User Type: " . $response['userType']);
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

?>



<?php
require_once 'business/loginBusiness.php';

session_start(); // Iniciar sesión

// Verificar si se enviaron los datos del formulario
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loginBusiness = new LoginBusiness();
    $user = $loginBusiness->authenticate($username, $password);

    if ($user) {
        // Autenticación exitosa
        $_SESSION['user'] = $user;
        header('Location: dashboard.php'); // Redirigir al usuario a la página del dashboard
        exit();
    } else {
        // Autenticación fallida
        header('Location: login.php?error=1'); // Redirigir al login con un mensaje de error
        exit();
    }
} else {
    // Si los datos no están completos, redirigir al login
    header('Location: login.php?error=2');
    exit();
}
?>

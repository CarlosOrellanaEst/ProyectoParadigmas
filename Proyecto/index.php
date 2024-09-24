<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <?php 
        session_start();
    ?>
</head>
<body>
    <div>
        <h2>Iniciar Sesión</h2>
        <div id="message" hidden></div>
        <form id="loginForm" method="POST">
            <div>
                <label for="nickName">Nombre de Usuario</label>
                <input type="text" id="nickName" name="nickName" >
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" >
            </div>
            <div>
                <button type="submit">Ingresar</button>
            </div>
<!--             <div>
                <a href="/forgot_password.php">¿Olvidaste tu contraseña?</a>
            </div> -->
            <div>
                <label for = "register">¿No tienes cuenta?</label>     
                <a href="./selectRegister.php">Registrarse</a>


            </div>
        </form>
    </div>
    <script src="resources/loginAJAX.js"></script>
</body>
</html>

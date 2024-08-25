<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <a href="../index.html">← Volver al inicio</a>
    <title>Iniciar Sesión</title>
</head>
<body>
    <div>
        <h2>Iniciar Sesión</h2>
        <div id="message" hidden></div>

        <form id="loginForm" method="POST" action="../business/loginAction.php">
            <div>
                <label for="userName">Nombre de Usuario</label>
                <input type="text" id="userName" name="userName" >
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" >
            </div>
            <div>
                <button type="submit">Ingresar</button>
            </div>
            <div>
                <a href="/forgot_password.php">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>

    <script src="../resources/loginAJAX.js"></script>
    
</body>
</html>

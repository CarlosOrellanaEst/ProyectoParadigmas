

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css"> <!-- Incluye tu archivo de estilos CSS -->
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <!-- Muestra el mensaje de error si existe -->
        <?php if (!empty($errorMsg)): ?>
            <div class="error"><?php echo $errorMsg; ?></div>
        <?php endif; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="userName">Nombre de Usuario</label>
                <input type="text" id="userName" name="userName" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Ingresar</button>
            </div>
            <div class="form-group">
                <a href="/forgot_password.php">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>
</body>
</html>

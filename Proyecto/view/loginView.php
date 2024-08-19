<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <button><a href="index.html">Index</a></button>
    <title>Iniciar Sesión</title>
</head>
<body>
    <div>
        <h2>Iniciar Sesión</h2>
        <div id="message" hidden></div>

        <form id="loginForm" method="POST" action="../business/loginAction.php">
            <div>
                <label for="userName">Nombre de Usuario</label>
                <input type="text" id="userName" name="userName" required>
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Ingresar</button>
            </div>
            <div>
                <a href="/forgot_password.php">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Previene el envío del formulario por defecto

            const userName = document.getElementById("userName").value;
            const password = document.getElementById("password").value;

            // Realiza la solicitud AJAX
            fetch("../business/loginAction.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `userName=${encodeURIComponent(userName)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById("message");
                
                if (data.success) {
                    messageDiv.innerText = data.message;
                    
                    alert(messageDiv.innerText);
                    
                    // Redirige a la página de dashboard después de mostrar el mensaje
                    // Redirige a la página correspondiente según el userType después de mostrar el mensaje
                    setTimeout(() => {
                        if (data.userType === "Administrador") {
                            window.location.href = "adminView.php";
                        } else if (data.userType === "Turista") {
                            window.location.href = "touristView.php";
                        } else if(data.userType === "Propietario") {
                            window.location.href = "propietarioView.php";
                        }
                    }, 2000); // Redirige después de 2 segundos
                } else {
                    messageDiv.innerText = data.message;
                    
                    alert(messageDiv.innerText);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    </script>
</body>
</html>

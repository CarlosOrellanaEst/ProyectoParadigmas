<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>
<body>
    <div>
        <h2>Iniciar Sesión</h2>
        <div id="message" style="display: none;"></div>
        <form id="loginForm" method="POST" action="/Proyecto/business/loginAction.php">
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
            fetch("/Proyecto/business/loginAction.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `userName=${encodeURIComponent(userName)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById("message");
                messageDiv.style.display = "block";
                if (data.success) {
                    messageDiv.innerText = data.message;
                    messageDiv.style.color = "green";
                    // Redirige a la página de dashboard después de mostrar el mensaje
                    setTimeout(() => {
                        window.location.href = "dashboard.php";
                    }, 2000); // Redirige después de 2 segundos
                } else {
                    messageDiv.innerText = data.message;
                    messageDiv.style.color = "red";
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    </script>
</body>
</html>

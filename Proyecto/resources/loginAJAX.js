document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Previene el envío del formulario por defecto

        const userName = document.getElementById("userName").value.trim(); // Obtiene el valor del campo de usuario
        const password = document.getElementById("password").value.trim(); // Obtiene el valor del campo de contraseña

        if(userName === ''){
            alert('El usuario no puede estar vacio.');
            return;
        } else if(password === ''){
            alert('La contraseña no puede estar vacia.');
            return; 
        }

        const postData = {
            userName: userName,
            password: password
        };

        let xhr = new XMLHttpRequest();    
        xhr.open('POST', '../business/loginAction.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {   
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.success === true) {
                            document.getElementById('loginForm').reset();
                            if (response.userType === 'Administrador') {
                                // Redireccionar a la vista de administrador
                                window.location.href = '../index.html';
                            } else if (response.userType === 'Turista') {
                                // Redireccionar a la vista de usuario normal
                                window.location.href = '../view/touristView.php';
                            } else if(response.userType === 'Propietario'){
                                // Redireccionar a la vista de guia
                                window.location.href = '../view/propietarioView.php';
                            } else{
                                alert('Tipo de usuario desconocido');
                            }
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } else {
                        alert('HTTP Error: ' + xhr.status);
                    }
                } catch (e) {
                    //console.error('Invalid JSON response:', xhr.responseText);
                    alert('Error procesando la respuesta del servidor.');
                }
            }
        };
        xhr.send('userName=' + encodeURIComponent(postData.userName) + '&password=' + encodeURIComponent(postData.password));
    });
});

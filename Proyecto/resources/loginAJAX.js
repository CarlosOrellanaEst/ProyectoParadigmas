document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Previene el envío del formulario por defecto

        const nickName = document.getElementById("nickName").value.trim(); // Obtiene el valor del campo de usuario
        const password = document.getElementById("password").value.trim(); // Obtiene el valor del campo de contraseña

        if(nickName === ''){
            alert('El usuario no puede estar vacio.');
            return;
        } else if(password === ''){
            alert('La contraseña no puede estar vacia.');
            return; 
        }

        const postData = {
            nickName: nickName,
            password: password
        };
       console.log(postData.nickName+postData.password);

        let xhr = new XMLHttpRequest();    
        xhr.open('POST', 'business/loginAction.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {   
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.success === true) {
                            document.getElementById('loginForm').reset();
                            if (response.userType === 'Administrador') {
                                window.location.href = 'view/adminView.php';
                            } else if (response.userType === 'Turista') {
                                window.location.href = 'view/touristView.php';
                            } else if(response.userType === 'Propietario') {
                                window.location.href = 'view/ownerViewSession.php';
                            } else {
                                alert('Tipo de usuario desconocido');
                            }
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } else {
                        alert('HTTP Error: ' + xhr.status);
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', xhr.responseText);
                    alert('Error procesando la respuesta del servidor.');
                }
            }
        };
        xhr.send('nickName=' + encodeURIComponent(postData.nickName) + '&password=' + encodeURIComponent(postData.password));
    });
});

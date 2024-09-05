document.addEventListener('DOMContentLoaded', function () { 
    //Create Roll
    document.getElementById('formCreate').addEventListener('submit', function (e) {
        e.preventDefault();
    
        const name = document.getElementById('name').value.trim();    
        if (name === ' ') {
            alert('El nombre de la actividad no puede estar vacio.');
            return;
        }
        const postData = {
            nameTouristCompanyType: document.getElementById('name').value,
            description: document.getElementById('description').value,
        };
    
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/touristCompanyTypeAction.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.status === 'success') {
                            alert(response.message);
                            document.getElementById('formCreate').reset();
                            redirectToCleanURL();
                            location.reload();
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
        xhr.send('nameTouristCompanyType=' + encodeURIComponent(postData.nameTouristCompanyType) + '&description=' + encodeURIComponent(postData.description));
    });
})

function redirectToCleanURL() {
    const cleanURL = window.location.origin + window.location.pathname;
    window.history.replaceState({}, document.title, cleanURL);
}

window.onload = function () {
    showAlertBasedOnURL();
    redirectToCleanURL(); // Esto limpiará la URL después de mostrar los mensajes de alerta.
};

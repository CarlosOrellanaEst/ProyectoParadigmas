document.addEventListener('DOMContentLoaded', function () { 
    document.getElementById('formCreate').addEventListener('submit', function (e) {
        e.preventDefault();
    
        const accountNumber = document.getElementById('accountNumber').value.trim();    
        if (accountNumber === ' ') {
            alert('El número de cuenta no puede estar vacio.');
            return;
        }
        const postData = {
            ownerId: document.getElementById('ownerId').value,
            accountNumber: document.getElementById('accountNumber').value,
            sinpeNumber: document.getElementById('sinpeNumber').value,
        };
    
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/paymentTypeAction.php', true);
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
        xhr.send('ownerId=' + encodeURIComponent(postData.ownerId) + '&accountNumber=' + encodeURIComponent(postData.accountNumber)
        + '&sinpeNumber=' + encodeURIComponent(postData.sinpeNumber));
    });
})

function redirectToCleanURL() {
    const cleanURL = window.location.origin + window.location.pathname;
    window.history.replaceState({}, document.title, cleanURL);
}

window.onload = function () {
    showAlertBasedOnURL();
    redirectToCleanURL();
};

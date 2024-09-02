document.addEventListener('DOMContentLoaded', function () { 
    //Create Roll
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
            bank: document.getElementById('bank').value,
            status: document.getElementById('status').value,
        };
    
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/bankAccountAction.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (response.status === 'success') {
                            alert(response.message);
                            document.getElementById('formCreate').reset();
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
        + '&bank=' + encodeURIComponent(postData.bank) + '&status=' + encodeURIComponent(postData.status));
    });
})

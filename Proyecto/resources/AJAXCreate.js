document.addEventListener('DOMContentLoaded', function () { 

    //Create Roll
    document.getElementById('formCreate').addEventListener('submit', function (e) {
        e.preventDefault();
    
        const name = document.getElementById('name').value.trim();    
        if (name === '') {
            alert('El nombre del roll es obligatorio.');
            return;
        }
        const postData = {
            name: document.getElementById('name').value,
            description: document.getElementById('description').value,
        };
    
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/rollAction.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    alert(response.message);
                    document.getElementById('formCreate').reset();
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            }
        };
        xhr.send('name=' + encodeURIComponent(postData.name) + '&description=' + encodeURIComponent(postData.description));
    });

     //Create Roll
    /*  
     document.getElementById('formCreate').addEventListener('submit', function (e) {
        e.preventDefault();
    
        const name = document.getElementById('name').value.trim();    
        if (name === '') {
            alert('Roll name cannot be empty.');
            return;
        }
        const postData = {
            name: document.getElementById('name').value,
            description: document.getElementById('description').value,
        };
    
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../business/rollPrueba.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    alert(response.message);
                    document.getElementById('formCreate').reset();
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            }
        };
        xhr.send('name=' + encodeURIComponent(postData.name) + '&description=' + encodeURIComponent(postData.description));
    });
 */

})
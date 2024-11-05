document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.attribute-value');
    let typingTimer;
    const doneTypingInterval = 1000; // esperamos 1 segundo al keyup

    inputs.forEach(input => {
        input.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            const attribute = this.getAttribute('data-attribute');
            const value = this.value;
            
            // Only proceed if there's actually a value
            if (value.trim().length > 0) {
                typingTimer = setTimeout(() => {
                    fetchRecommendedActivities(attribute, value);
                }, doneTypingInterval);
                console.log(value);
            } else {
                // Hide the results table if input is empty
                document.getElementById('recommended-activities-table').style.display = 'none';
            }
        });
    });

    function fetchRecommendedActivities(attribute, value) {
        // Create FormData object
        const formData = new FormData();
        formData.append('attribute', attribute);
        formData.append('value', value);

        // Make the AJAX request
        fetch('../business/recommendationAction.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(activities => {
            displayRecommendedActivities(activities);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function displayRecommendedActivities(activities) {
        const tableBody = document.getElementById('recommended-activities-table').querySelector('tbody');
        const table = document.getElementById('recommended-activities-table');
        tableBody.innerHTML = '';

        if (activities && activities.length > 0) {
            table.style.display = 'table';
            
            activities.forEach(activity => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${activity.tbactivityname}</td>
                    <td>${activity.tbactivityservicecompanyid}</td>
                    <td>${Array.isArray(activity.tbactivityatributearray) ? activity.tbactivityatributearray.join(', ') : activity.tbactivityatributearray}</td>
                    <td>${Array.isArray(activity.tbactivityurl) ? activity.tbactivityurl.map(url => `<img src="${url}" width="50" height="50"/>`).join('') : ''}</td>
                    <td>${activity.tbactivitydate}</td>
                    <td>${activity.tbactivitylongitude}</td>
                    <td>${activity.tbactivitylatitude}</td>
                    <td><a href="bookingView.php?idTBActivity=${activity.tbactivityid}">Reservar</a></td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            table.style.display = 'table';
            tableBody.innerHTML = '<tr><td colspan="8">No se encontraron actividades coincidentes</td></tr>';
        }
    }
});
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Actividades</title>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<a href="adminView.php">← Volver al inicio</a>
    <h2>Calendario de Actividades Turísticas</h2>

    <!-- Selección de rango de fechas y tipo de filtro -->
    <label for="start-date">Fecha de Inicio:</label>
    <input type="date" id="start-date">
    
    <label for="filter-type">Filtrar por:</label>
    <select id="filter-type">
        <option value="day">Día</option>
        <option value="week">Semana</option>
        <option value="month">Mes</option>
    </select>

    <!-- Mapa para seleccionar ubicación -->
    <h3>Seleccionar ubicación</h3>
    <div id="map"></div>

    <!-- Inputs ocultos para las coordenadas seleccionadas -->
    <input type="text" id="selected-latitude" hidden>
    <input type="text" id="selected-longitude" hidden>

    <!-- Tabla para mostrar actividades filtradas -->
    <h3>Actividades Disponibles</h3>
    <table id="activities-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Latitud</th>
                <th>Longitud</th>
                <th>Ubicación</th>
            </tr>
        </thead>
        <tbody> 
       

        </tbody>
    </table>

    <script>
        const activities = [
            { name: 'Tour a la playa', date: '2024-10-17', latitude: 10.3166183, longitude: -83.9221164 },
            { name: 'Excursión a la montaña', date: '2024-10-18', latitude: 10.3236183, longitude: -83.9321164 },
            { name: 'Visita a parque nacional', date: '2024-10-19', latitude: 10.3096183, longitude: -83.9121164 },
            { name: 'Recorrido cultural', date: '2024-10-20', latitude: 10.3196183, longitude: -83.9421164 },
            { name: 'Aventura en kayak', date: '2024-10-25', latitude: 10.3193683, longitude: -83.9231164 }
        ];

        let map; // Mapa de Google
        let markers = []; // Almacena los marcadores en el mapa
        let userMarker; // Almacena el marcador del usuario
        let infoWindow; // Almacena la ventana de información

        function initMap() {
            const lugarEstandar = { lat: 10.3193683, lng: -83.9231164 };
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 11,
                center: lugarEstandar,
                mapId: 'd54b09205a9c0cf9'
            });

            // Muestra los datos de las actividades por defecto
            displayActivities(activities);
            activities.forEach(activity => {
                addMarker(activity); // Agregar marcadores de actividades
            });

            // Evento de clic en el mapa
            map.addListener('click', function(event) {
                const clickedLocation = event.latLng;
                document.getElementById('selected-latitude').value = clickedLocation.lat();
                document.getElementById('selected-longitude').value = clickedLocation.lng();

                // Marcar la ubicación seleccionada por el usuario
                if (userMarker) {
                    userMarker.setMap(null); // Eliminar marcador anterior
                }
                userMarker = new google.maps.Marker({
                    position: clickedLocation,
                    map: map,
                    title: 'Ubicación Seleccionada'
                });

                filterActivities(); // Filtrar actividades después de seleccionar ubicación
            });

            // Inicializar la ventana de información
            infoWindow = new google.maps.InfoWindow();
        }

        function addMarker(activity) {
            const marker = new google.maps.Marker({
                position: { lat: activity.latitude, lng: activity.longitude },
                map: map,
                title: activity.name
            });

            // Añadir evento de clic al marcador para mostrar información
            marker.addListener('click', function() {
                infoWindow.setContent(`<div><strong>${activity.name}</strong><br>${activity.date}<br>Lat: ${activity.latitude}<br>Lng: ${activity.longitude}</div>`);
                infoWindow.open(map, marker);
            });

            markers.push(marker);
        }

        function clearMarkers() {
            markers.forEach(marker => {
                marker.setMap(null);
            });
            markers = [];
        }

        function filterActivities() {
            const startDate = new Date(document.getElementById('start-date').value);
            const selectedLatitude = parseFloat(document.getElementById('selected-latitude').value);
            const selectedLongitude = parseFloat(document.getElementById('selected-longitude').value);
            const filterType = document.getElementById('filter-type').value;

            let endDate = new Date(startDate); // Copia la fecha de inicio

            // Ajustar el rango de fechas según el filtro seleccionado
            if (filterType === 'week') {
                endDate.setDate(startDate.getDate() + 6); // Incrementa 6 días para la semana
            } else if (filterType === 'month') {
                endDate.setMonth(startDate.getMonth() + 1); // Incrementa un mes
                endDate.setDate(endDate.getDate() - 1); // Ajusta para incluir todo el mes
            }

            const filteredActivities = activities.filter(activity => {
                const activityDate = new Date(activity.date);
                const distance = calculateDistance(activity.latitude, activity.longitude, selectedLatitude, selectedLongitude);
                const isInRange = distance <= 10; // 10 km de rango
                const isInDateRange = activityDate >= startDate && activityDate <= endDate;
                return isInRange && isInDateRange;
            });

            clearMarkers(); // Limpiar marcadores existentes en el mapa
            displayActivities(filteredActivities);
            filteredActivities.forEach(activity => {
                addMarker(activity); // Agregar marcadores de actividades filtradas
            });
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radio de la Tierra en km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        function displayActivities(activities) {
            const tableBody = document.getElementById('activities-table').querySelector('tbody');
            tableBody.innerHTML = '';
            activities.forEach(activity => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${activity.name}</td>
                    <td>${activity.date}</td>
                    <td>${activity.latitude}</td>
                    <td>${activity.longitude}</td>
                    <td><a href="https://www.google.com/maps/search/?api=1&query=${activity.latitude},${activity.longitude}" target="_blank">Ver ubicación</a></td>
                `;
                tableBody.appendChild(row);
            });
        }

        document.getElementById('start-date').addEventListener('change', filterActivities);
        document.getElementById('filter-type').addEventListener('change', filterActivities);
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRQx6ssQ25Ezy99nFNHJYSCVIpE9JeAUI&callback=initMap"
        async defer></script>
        <script src="../resources/calendar.js" defer></script>
</body>
</html>

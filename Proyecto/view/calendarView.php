<?php
require_once '../domain/Owner.php';
require_once '../business/paymentTypeBusiness.php';
require_once '../business/ownerBusiness.php';
require_once '../business/activityBusiness.php';
require_once '../business/serviceCompanyBusiness.php';

session_start();
$userLogged = $_SESSION['user'];
$ownerBusiness = new ownerBusiness();

if ($userLogged->getUserType() == "Administrador") {
    $owners = $ownerBusiness->getAllTBOwners();
    if (!$owners || empty($owners)) {
        echo "<script>alert('No se encontraron propietarios.');</script>";
    }
} else if ($userLogged->getUserType() == "Propietario") {
    $owners = [$userLogged];
}

$_SESSION['owners'] = $owners;

?>

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

    th,
    td {
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

    <label for="start-date">Fecha de Inicio:</label>
    <input type="date" id="start-date">

    <label for="filter-type">Filtrar por:</label>
    <select id="filter-type">
        <option value="day">Día</option>
        <option value="week">Semana</option>
        <option value="month">Mes</option>
    </select>

    <h3>Seleccionar ubicación</h3>
    <div id="map"></div>

    <input type="text" id="selected-latitude" hidden>
    <input type="text" id="selected-longitude" hidden>



    <h3>Actividades Disponibles</h3>
    <table id="activities-table">
        <thead>
            <tr>
                <th>Nombre de la Actividad</th>
                <th>Servicio</th>
                <th>Atributos y Datos</th>
                <th>Fotos</th>
                <th>Fecha y Hora</th>
                <th>Longitud</th>
                <th>Latitud</th>
                <th>Reservar</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <script>
    <?php
        $activityBusiness = new ActivityBusiness();
        $serviceCompanyBusiness = new ServiceCompanyBusiness();
        $allActivities = $activityBusiness->getAllActivities();

       
        ?>
    let map;
    let markers = [];
    let userMarker;
    let activities = <?php echo json_encode($allActivities); ?>; // Pasar actividades a JavaScript

    function initMap() {
        const lugarEstandar = {
            lat: 10.3193683,
            lng: -83.9231164
        };
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 11,
            center: lugarEstandar,
            mapId: 'd54b09205a9c0cf9'
        });

        // Mostrar datos de actividades por defecto
        displayActivities(activities);
        activities.forEach(activity => {
            addMarker(activity);
        });

        map.addListener('click', function(event) {
            const clickedLocation = event.latLng;
            document.getElementById('selected-latitude').value = clickedLocation.lat();
            document.getElementById('selected-longitude').value = clickedLocation.lng();

            if (userMarker) {
                userMarker.setMap(null);
            }
            userMarker = new google.maps.Marker({
                position: clickedLocation,
                map: map,
                title: 'Ubicación Seleccionada'
            });

            filterActivities();
        });
    }

    function addMarker(activity) {
        const marker = new google.maps.Marker({
            position: {
                lat: parseFloat(activity.tbactivitylatitude),
                lng: parseFloat(activity.tbactivitylongitude)
            },
            map: map,
            title: activity.tbactivityname
        });

        marker.addListener('click', function() {
            const infoWindow = new google.maps.InfoWindow({
                content: `<div>
            <br>Nombre: ${activity.tbactivityurl}
            <strong>${activity.tbactivityname}</strong>
            <br>Fecha: ${activity.tbactivitydate}
            <br>Atributos: ${activity.tbactivityatributearray}
            <br>DAtos: ${activity.tbactivitydataarray}
            <br>Lat: ${activity.tbactivitylatitude}
            <br>Lng: ${activity.tbactivitylongitude}
            <br><a href="bookingView.php?idTBActivity=${activity.tbactivityid}">Reservar</a>
            </div>
            `
            });
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

    let endDate = new Date(startDate);

    // Ajustar el rango según el tipo de filtro
    if (filterType === 'week') {
        // Suma 6 días para obtener el final de la semana
        endDate.setDate(startDate.getDate() + 6);
    } else if (filterType === 'day') {
        // Para 'day', no se modifica el endDate, solo usamos el mismo startDate
        endDate.setDate(startDate.getDate());
    } else if (filterType === 'month') {
        // Suma un mes al startDate
        endDate.setMonth(startDate.getMonth() + 1);
    }

    // Filtrar actividades por fecha, ignorando horas
    const filteredActivities = activities.filter(activity => {
        const activityDate = new Date(activity.tbactivitydate);

        // Elimina las horas de las fechas (mantener solo año, mes y día)
        const activityDateWithoutTime = new Date(activityDate.getFullYear(), activityDate.getMonth(), activityDate.getDate());
        const startDateWithoutTime = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate());
        const endDateWithoutTime = new Date(endDate.getFullYear(), endDate.getMonth(), endDate.getDate());

        // Calcular distancia para filtrar por rango
        const distance = calculateDistance(activity.tbactivitylatitude, activity.tbactivitylongitude,
            selectedLatitude, selectedLongitude);
        const isInRange = distance <= 50; // Rango de 50 km

        // Verificar si la actividad está en el rango de fechas
        const isInDateRange = activityDateWithoutTime >= startDateWithoutTime && activityDateWithoutTime <= endDateWithoutTime;

        return isInRange && isInDateRange;
    });

        clearMarkers();
        displayActivities(filteredActivities);
        filteredActivities.forEach(activity => {
            addMarker(activity);
        });
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 25000; // Radio de la Tierra en km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c
        return R * c;
    }

    function displayActivities(allActivities) {
        const tableBody = document.getElementById('activities-table').querySelector('tbody');
        tableBody.innerHTML = '';

        if (allActivities.length > 0) {
            allActivities.forEach(activity => {
                const row = document.createElement('tr');

                // Nombre de la actividad
                const activityName = activity.tbactivityname;

                // Servicio asociado
                const serviceNameArray = activity.tbactivityservicecompanyid.split(
                    ','); // Convierte el string en un array
                const serviceName = serviceNameArray.join(', ');

                // Atributos y datos
                const attributes = activity.tbactivityatributearray.join(', ');
                const data = activity.tbactivitydataarray.join(', ');

                // Fotos
                let photoElements = '';
                const urls = Array.isArray(activity.tbactivityurl) ? activity.tbactivityurl : activity
                    .tbactivityurl.split(',');

                if (typeof urls === 'string') {
                    urls = [urls]; 
                }

                urls.forEach(url => {
                    if (url.trim()) {
                        photoElements +=
                            `<img src="${url.trim()}" alt="Foto" width="50" height="50" />`;
                    }
                });

                // Fecha y hora
                const activityDate = activity.tbactivitydate;

                // Longitud y latitud
                const longitude = activity.tbactivitylongitude;
                const latitude = activity.tbactivitylatitude;

                // Crear la fila
                row.innerHTML = `
                <td>${activity.tbactivityname}</td>
                <td>${activity.tbactivityservicecompanyid}</td>
                <td>${activity.tbactivityatributearray.join(', ')}</td>
                <td>${activity.tbactivityurl.map(url => `<img src="${url}" width="50" height="50"/>`).join('')}</td>
                <td>${activity.tbactivitydate}</td>
                <td>${activity.tbactivitylongitude}</td>
                <td>${activity.tbactivitylatitude}</td>
                <td><a href="bookingView.php?idTBActivity=${activity.tbactivityid}">Reservar</a></td>
</td>
            `;
            tableBody.appendChild(row);
        });
        // Agregar evento a los botones de reserva
        const reserveButtons = document.querySelectorAll('.reserve-btn');
        reserveButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                openModal(this);
            });
        });
    } else {
        tableBody.innerHTML = '<tr><td colspan="8">No se encontraron resultados</td></tr>';
    }
    }


    document.getElementById('start-date').addEventListener('change', filterActivities);
    document.getElementById('filter-type').addEventListener('change', filterActivities);


    </script>

<script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRQx6ssQ25Ezy99nFNHJYSCVIpE9JeAUI&libraries=marker&callback=initMap&loading=async"
        defer></script>

</body>

</html>
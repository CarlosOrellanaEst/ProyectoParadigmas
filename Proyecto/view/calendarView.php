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
            <th>Ubicación</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $activityBusiness = new ActivityBusiness();
        $serviceCompanyBusiness = new ServiceCompanyBusiness();
        $allActivities = $activityBusiness->getAllActivities();

        if ($allActivities && count($allActivities) > 0) {
            foreach ($allActivities as $current) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($current['tbactivityname']) . '</td>';
                $serviceName = $serviceCompanyBusiness->getTBServicesByIds($current['tbactivityservicecompanyid']);
                echo '<td>' . htmlspecialchars(is_array($serviceName) 
                    ? implode(', ', array_map(fn($s) => $s->getTbservicename(), $serviceName)) 
                    : $serviceName) . '</td>';
                
                echo '<td>';
                $attributeArray = $current['tbactivityatributearray'];
                $dataArray = $current['tbactivitydataarray'];
                for ($i = 0; $i < count($attributeArray); $i++) {
                    echo '<div><span>Atributo: ' . htmlspecialchars($attributeArray[$i]) . '</span> - <span>Dato: ' . htmlspecialchars($dataArray[$i]) . '</span></div>';
                }
                echo '</td>';

                echo '<td>';
                $urls = $current['tbactivityurl'];
                if (is_string($urls)) {
                    $urls = explode(',', $urls);
                }
                foreach ($urls as $url) {
                    if (!empty(trim($url))) {
                        echo '<img src="' . htmlspecialchars(trim($url)) . '" alt="Foto" width="50" height="50" />';
                    }
                }
                echo '</td>';

                echo '<td>' . htmlspecialchars($current['tbactivitydate']) . '</td>';
                echo '<td>' . htmlspecialchars($current['tbactivitylongitude']) . '</td>';
                echo '<td>' . htmlspecialchars($current['tbactivitylatitude']) . '</td>';
                echo '<td><a href="https://www.google.com/maps/search/?api=1&query=' . $current['tbactivitylatitude'] . ',' . $current['tbactivitylongitude'] . '" target="_blank">Ver ubicación</a></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="8">No se encontraron resultados</td></tr>';
        }
        ?>
    </tbody>
</table>

<script>
  let map;
  let markers = [];
  let userMarker;
  let activities = <?php echo json_encode($allActivities); ?>; // Pasar actividades a JavaScript

  function initMap() {
    const lugarEstandar = { lat: 10.3193683, lng: -83.9231164 };
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
        position: { lat: parseFloat(activity.tbactivitylatitude), lng: parseFloat(activity.tbactivitylongitude) },
        map: map,
        title: activity.tbactivityname
    });

    marker.addListener('click', function() {
        const infoWindow = new google.maps.InfoWindow({
            content: `<div><strong>${activity.tbactivityname}</strong><br>${activity.tbactivitydate}<br>Lat: ${activity.tbactivitylatitude}<br>Lng: ${activity.tbactivitylongitude}</div>`
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

    if (filterType === 'week') {
        endDate.setDate(startDate.getDate() + 6);
    } else if (filterType === 'month') {
        endDate.setMonth(startDate.getMonth() + 1);
        endDate.setDate(endDate.getDate() - 1);
    }

    const filteredActivities = activities.filter(activity => {
        const activityDate = new Date(activity.tbactivitydate);
        const distance = calculateDistance(activity.tbactivitylatitude, activity.tbactivitylongitude, selectedLatitude, selectedLongitude);
        const isInRange = distance <= 10; // 10 km de rango
        const isInDateRange = activityDate >= startDate && activityDate <= endDate;
        return isInRange && isInDateRange;
    });

    clearMarkers();
    displayActivities(filteredActivities);
    filteredActivities.forEach(activity => {
        addMarker(activity);
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
            const serviceName = activity.serviceName || ''; // Ensure serviceName is defined

            // Atributos y datos
            const attributes = activity.tbactivityatributearray.join(', ');
            const data = activity.tbactivitydataarray.join(', ');

            // Fotos
            let photoElements = '';
            const urls = Array.isArray(activity.tbactivityurl) ? activity.tbactivityurl : activity.tbactivityurl.split(',');

            if (typeof urls === 'string') {
                urls = [urls]; // Ensure it is an array
            }

            urls.forEach(url => {
                if (url.trim()) {
                    photoElements += `<img src="${url.trim()}" alt="Foto" width="50" height="50" />`;
                }
            });

            // Fecha y hora
            const activityDate = activity.tbactivitydate;

            // Longitud y latitud
            const longitude = activity.tbactivitylongitude;
            const latitude = activity.tbactivitylatitude;

            // Crear la fila
            row.innerHTML = `
                <td>${activityName}</td>
                <td>${serviceName}</td>
                <td>${attributes} - ${data}</td>
                <td>${photoElements}</td>
                <td>${activityDate}</td>
                <td>${longitude}</td>
                <td>${latitude}</td>
                <td><a href="https://www.google.com/maps/search/?api=1&query=${latitude},${longitude}" target="_blank">Ver ubicación</a></td>
            `;
            tableBody.appendChild(row);
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

<?php
require_once '../domain/Owner.php';
require_once '../business/paymentTypeBusiness.php';
require_once '../business/ownerBusiness.php';
require_once '../business/activityBusiness.php';
require_once '../business/serviceCompanyBusiness.php';

session_start();
$userLogged = $_SESSION['user'];
$ownerBusiness = new ownerBusiness();

// Definimos los propietarios en función del tipo de usuario
if ($userLogged->getUserType() == "Administrador") {
    $owners = $ownerBusiness->getAllTBOwners();
    if (!$owners || empty($owners)) {
        echo "<script>alert('No se encontraron propietarios.');</script>";
    }
} else if ($userLogged->getUserType() == "Propietario") {
    $owners = [$userLogged];
}

// Guardamos la lista de propietarios en la sesión para usarla abajo
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
        
    </tbody>
</table>

<script>

        <?php
        $activityBusiness = new ActivityBusiness();  // Instancia de la clase ActivityBusiness
        $serviceCompanyBusiness = new ServiceCompanyBusiness();  // Instancia de la clase ServiceCompanyBusiness
        $allActivities = $activityBusiness->getAllActivities();

       
        ?>
  let map; // Mapa de Google
  let markers = []; // Almacena los marcadores en el mapa
  let userMarker; // Almacena el marcador del usuario

  function initMap() {
      const lugarEstandar = { lat: 10.3193683, lng: -83.9231164 };
      map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
          center: lugarEstandar,
          mapId: 'd54b09205a9c0cf9'
      });

      // Muestra los datos de las actividades por defecto
      displayActivities(<?php echo json_encode($allActivities); ?>);

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
              title: "Ubicación seleccionada"
          });
          
          filterActivities(clickedLocation); // Filtrar actividades cercanas
      });
  }

  function filterActivities(location) {
      markers.forEach(marker => marker.setMap(null)); // Limpiar marcadores existentes
      markers = []; // Reiniciar array de marcadores

      const radius = 5000; // radio en metros
      <?php foreach ($allActivities as $activity): ?>
          const activityLocation = { lat: <?= $activity['tbactivitylatitude'] ?>, lng: <?= $activity['tbactivitylongitude'] ?> };
          const distance = google.maps.geometry.spherical.computeDistanceBetween(
              new google.maps.LatLng(location.lat(), location.lng()),
              new google.maps.LatLng(activityLocation.lat, activityLocation.lng)
          );

          if (distance <= radius) {
              addMarker(<?= json_encode($activity) ?>); // Añadir marcadores cercanos
          }
      <?php endforeach; ?>
  }

  function addMarker(activity) {
      const activityLocation = { lat: activity.tbactivitylatitude, lng: activity.tbactivitylongitude };
      const marker = new google.maps.Marker({
          position: activityLocation,
          map: map,
          title: activity.tbactivityname
      });
      markers.push(marker); // Agregar el marcador a la lista
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
              const serviceName = activity.serviceName; // Asumiendo que el nombre del servicio ya está incluido en el objeto activity

              // Atributos y datos
              const attributes = activity.tbactivityatributearray.join(', ');
              const data = activity.tbactivitydataarray.join(', ');

              // Fotos
              const urls = activity.tbactivityurl.split(',');
              const photoElements = urls.map(url => `<img src="${url.trim()}" alt="Foto" width="50" height="50" />`).join('');

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


        <script src="../resources/calendar.js" defer></script>
</body>
</html>

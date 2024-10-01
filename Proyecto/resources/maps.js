var map;
var marker;

function initMap() {
    var lugarEstandar = { lat: 10.3193683, lng: -83.9231164 }; 

    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 11,
      center: lugarEstandar,
      mapId: 'd54b09205a9c0cf9'
    });
  
    marker = new google.maps.marker.AdvancedMarkerElement({
      map: map,
      title: 'Ubicación seleccionada',
    });
  
    map.addListener('click', function(event) {
      var clickedLocation = event.latLng;

      marker.position = clickedLocation;
      // setteo los valores en los inputs del html solo para que se vea en el front. opcional. 
      document.getElementById('latitude').value = clickedLocation.lat();
      document.getElementById('longitude').value = clickedLocation.lng();
    });
}

function searchByLatnLng(lat, long) {
  var nuevaUbicacion = { lat: parseFloat(lat), lng: parseFloat(long) };

  if (map && marker) {
      map.setCenter(nuevaUbicacion);

      // Actualizar la posición del marcador
      marker.position = nuevaUbicacion;
  }

  // Actualizar los campos de latitud y longitud con la nueva ubicación
  document.getElementById('latitude').value = nuevaUbicacion.lat;
  document.getElementById('longitude').value = nuevaUbicacion.lng;
}

// Esta función será llamada cuando el usuario haga clic en el botón "Buscar en Mapa"
function geocodeAddress() {
  var lat = document.getElementById('inputLatitud').value;
  var long = document.getElementById('inputLongitud').value;

  if (lat && long) {
      searchByLatnLng(lat, long);
  } else {
      alert('Por favor, ingrese valores válidos para latitud y longitud.');
  }
}

  
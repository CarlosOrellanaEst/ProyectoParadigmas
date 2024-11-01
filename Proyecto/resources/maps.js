function initMap() {
  var lugarEstandar = { lat: 10.3193683, lng: -83.9231164 }; // Coordenadas por defecto

  var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 11,
      center: lugarEstandar,
      mapId: 'd54b09205a9c0cf9'
  });
  

  var marker = new google.maps.Marker({
      position: lugarEstandar,
      map: map,
      title: 'Ubicación seleccionada'
  });

  // Evento para capturar clics en el mapa
  map.addListener('click', function(event) {
      var clickedLocation = event.latLng;

      // Actualizar marcador
      marker.setPosition(clickedLocation);

      // Mostrar las coordenadas en los inputs ocultos
      document.getElementById('latitude').value = clickedLocation.lat();
      document.getElementById('longitude').value = clickedLocation.lng();
  });
}

function initMap() {
    var lugarEstandar = { lat: 10.3193683, lng: -83.9231164 }; 

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 11,
      center: lugarEstandar,
      mapId: 'd54b09205a9c0cf9' // ID de mapa de Google Cloud
    });
  
    // marcador que se actualizará en cada clic
    var marker = new google.maps.marker.AdvancedMarkerElement({
      map: map,
      title: 'Ubicación seleccionada',
    });
  
    map.addListener('click', function(event) {
      // coordenadas donde el usuario hizo clic
      var clickedLocation = event.latLng;

      marker.position = clickedLocation;
  
      // Mostrar las coordenadas en un campo de input
      document.getElementById('latitud').value = clickedLocation.lat();
      document.getElementById('longitud').value = clickedLocation.lng();

    });
  }
  



  
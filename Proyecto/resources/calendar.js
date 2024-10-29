let map;
let markers = [];
let userMarker;

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
    const R = 25000; // Radio de la Tierra en km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

function displayActivities(allActivities) {
    const tableBody = document.getElementById('activities-table').querySelector('tbody');
    tableBody.innerHTML = '';

    if (allActivities.length > 0) {
        allActivities.forEach(activity => {
            const row = document.createElement('tr');

            const activityName = activity.tbactivityname;
            const serviceName = activity.serviceName || '';
            const attributes = activity.tbactivityatributearray.join(', ');
            const data = activity.tbactivitydataarray.join(', ');

            let photoElements = '';
            const urls = Array.isArray(activity.tbactivityurl) ? activity.tbactivityurl : activity.tbactivityurl.split(',');

            if (typeof urls === 'string') {
                urls = [urls];
            }

            urls.forEach(url => {
                if (url.trim()) {
                    photoElements += `<img src="${url.trim()}" alt="Foto" width="50" height="50" />`;
                }
            });

            const activityDate = activity.tbactivitydate;
            const longitude = activity.tbactivitylongitude;
            const latitude = activity.tbactivitylatitude;

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

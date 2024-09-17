<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planificador de Actividades</title>
</head>
<body>
    <h1>Planificador de Actividades</h1>

    <!-- Sección de filtro -->
    <div id="filter-section">
        <label for="filter">Filtrar actividades por:</label>
        <select id="filter" name="filter" onchange="filterActivities()">
            <option value="day">Día</option>
            <option value="week">Semana</option>
            <option value="month">Mes</option>
        </select>

        <input type="date" id="filterDate" name="filterDate" onchange="filterActivities()">
    </div>

    <!-- Tabla para mostrar actividades -->
    <table border=1>
        <thead>
            <tr>
                <th>Nombre de la Actividad</th>
                <th>Empresa</th>
                <th>Atributos</th>
                <th>Datos</th>
                <th>URL</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody id="activityTable">
            <!-- Aquí se llenarán las actividades -->
        </tbody>
    </table>

    <script>
        const activities = [
            // Aquí se simulan los datos obtenidos desde la base de datos
            {name: 'Actividad 1', company: 'Compañía A', attributes: 'Atributo 1', data: 'Dato 1', url: 'http://actividad1.com', date: '2024-09-16', status: 1},
            {name: 'Actividad 2', company: 'Compañía B', attributes: 'Atributo 2', data: 'Dato 2', url: 'http://actividad2.com', date: '2024-09-18', status: 0},
            {name: 'Actividad 3', company: 'Compañía C', attributes: 'Atributo 3', data: 'Dato 3', url: 'http://actividad3.com', date: '2024-09-20', status: 1},
        ];

        // Filtrar actividades según el filtro seleccionado
        function filterActivities() {
            const filterType = document.getElementById('filter').value;
            const selectedDate = new Date(document.getElementById('filterDate').value);
            const tableBody = document.getElementById('activityTable');
            tableBody.innerHTML = ''; // Limpiar la tabla

            const filteredActivities = activities.filter(activity => {
                const activityDate = new Date(activity.date);
                switch (filterType) {
                    case 'day':
                        return activityDate.toDateString() === selectedDate.toDateString();
                    case 'week':
                        const startOfWeek = new Date(selectedDate);
                        startOfWeek.setDate(startOfWeek.getDate() - startOfWeek.getDay());
                        const endOfWeek = new Date(startOfWeek);
                        endOfWeek.setDate(endOfWeek.getDate() + 6);
                        return activityDate >= startOfWeek && activityDate <= endOfWeek;
                    case 'month':
                        return activityDate.getMonth() === selectedDate.getMonth() && activityDate.getFullYear() === selectedDate.getFullYear();
                    default:
                        return false;
                }
            });

            // Llenar la tabla con actividades filtradas
            filteredActivities.forEach(activity => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${activity.name}</td>
                    <td>${activity.company}</td>
                    <td>${activity.attributes}</td>
                    <td>${activity.data}</td>
                    <td><a href="${activity.url}" target="_blank">${activity.url}</a></td>
                    <td>${new Date(activity.date).toLocaleDateString()}</td>
                    <td>${activity.status === 1 ? 'Activo' : 'Inactivo'}</td>
                `;
                tableBody.appendChild(row);
            });
        }
    </script>
</body>
</html>

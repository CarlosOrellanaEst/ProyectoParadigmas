<?php
// Obtener los valores de la URL
$activityName = isset($_GET['name']) ? urldecode($_GET['name']) : '';
$serviceName = isset($_GET['service']) ? urldecode($_GET['service']) : '';
$activityDate = isset($_GET['date']) ? urldecode($_GET['date']) : '';
$latitude = isset($_GET['lat']) ? urldecode($_GET['lat']) : '';
$longitude = isset($_GET['lng']) ? urldecode($_GET['lng']) : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Actividad</title>
</head>
<body>

<h2>Formulario de Reserva</h2>

<form action="submitBooking.php" method="POST">
    <label for="activity-name">Nombre de la Actividad:</label>
    <input type="text" id="activity-name" name="activity-name" value="<?php echo htmlspecialchars($activityName); ?>" readonly>

    <label for="service-name">Nombre del Servicio:</label>
    <input type="text" id="service-name" name="service-name" value="<?php echo htmlspecialchars($serviceName); ?>" readonly>

    <label for="activity-date">Fecha de la Actividad:</label>
    <input type="text" id="activity-date" name="activity-date" value="<?php echo htmlspecialchars($activityDate); ?>" readonly>

    <label for="latitude">Latitud:</label>
    <input type="text" id="latitude" name="latitude" value="<?php echo htmlspecialchars($latitude); ?>" readonly>

    <label for="longitude">Longitud:</label>
    <input type="text" id="longitude" name="longitude" value="<?php echo htmlspecialchars($longitude); ?>" readonly>

    <button type="submit">Confirmar Reserva</button>
</form>

</body>
</html>

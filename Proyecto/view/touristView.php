<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Texto Simple</title>
</head>
<body>
    <p>PAGINA DE TURISTA.</p>
    <ol>
        <li><a href="touristCompanyView.php">Ver Empresas turísticas</a></li>
        <li><a href="activityView.php">Ver Actividades</a></li>
        <li><a href="calendarView.php">Calendario</a></li>
    </ol>
    <?php 
       echo ' <a href="../index.php">Cerrar Sesión</a> ';
       $_SESSION = array();
    //   session_destroy();
    ?>
</body>
</html>

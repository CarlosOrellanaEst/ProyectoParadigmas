<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Texto Simple</title>
</head>
<body>
    <p>PAGINA DE ADMIN.</p>
    <ol>  
        <li><a href="rollView.php">CRUD Roles</a></li>
        <li><a href="paymentTypeView.php">CRUD Tipo de Pago</a></li>
        <li><a href="ownerView.php">CRUD Propietarios</a></li>
        <li><a href="touristCompanyTypeView.php">CRUD Tipo de empresa turística</a></li>
        <li><a href="touristCompanyView.php">CRUD Empresas turísticas</a></li>
        <li><a href="activityView.php">CRUD Actividades</a></li>
        <li><a href="serviceView.php">CRUD Servicios</a></li>
        <!--<li><a href="PlannerView.php">CRUD Planificador</a></li> -->
        <li><a href="registerAdminView.php">Registrar administrador</a></li>
        <li><a href="calendarView.php">Calendario</a></li>
        
        <!-- <li><a href="pruebaMapaView.html">Prueba Mapa</a></li> -->
    </ol>
    <?php 
       echo ' <a href="../index.php">Cerrar Sesión</a> ';
       $_SESSION = array();
    //   session_destroy();
    ?>
</body>
</html>

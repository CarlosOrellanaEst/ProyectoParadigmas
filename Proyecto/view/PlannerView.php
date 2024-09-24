<?php
    require '../domain/Owner.php';
    require '../business/ownerBusiness.php';

    session_start();
   
    $userLogged = $_SESSION['user'];
    $userId = $userLogged->getUserId(); // Llamada al método que devuelve el ID del usuario

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
    <title>Lista de Actividades Activas</title>
    <?php
        include_once '../business/activityBusiness.php';
        $activityBusiness = new ActivityBusiness();

        // Inicializamos la variable de filtro de fechas
        $filterType = isset($_POST['filterType']) ? $_POST['filterType'] : '';
        $filterDate = isset($_POST['filterDate']) ? $_POST['filterDate'] : '';

        // Función para validar la fecha
        function isValidDate($date) {
            return (DateTime::createFromFormat('Y-m-d', $date) !== false);
        }

        // Verificamos que el filtro de fecha sea válido
        if (!empty($filterDate) && !isValidDate($filterDate)) {
            echo "<script>alert('Fecha inválida, por favor selecciona una fecha válida.');</script>";
            $activities = [];
        } else {
            if ($filterType === 'day') {
                $activities = $activityBusiness->getActivitiesByDay($filterDate);
            } elseif ($filterType === 'week') {
                $activities = $activityBusiness->getActivitiesByWeek($filterDate);
            } elseif ($filterType === 'month') {
                $activities = $activityBusiness->getActivitiesByMonth($filterDate);
            } else {
                $activities = $activityBusiness->getAllActivities();
            }
        }

        // Ruta base para imágenes
        $imageBasePath = '../images/activity/';
        
        // Obtener el ID del usuario en sesión
        $userLogged = $_SESSION['user'];
        $userId = $userLogged->getUserId();
    ?>
</head>
<body>

<h2>Actividades Activas</h2>

<form method="POST" action="">
    <label for="filterType">Filtrar por:</label>
    <select name="filterType" id="filterType">
        <option value="day" <?php echo ($filterType === 'day') ? 'selected' : ''; ?>>Día</option>
        <option value="week" <?php echo ($filterType === 'week') ? 'selected' : ''; ?>>Semana</option>
        <option value="month" <?php echo ($filterType === 'month') ? 'selected' : ''; ?>>Mes</option>
    </select>

    <label for="filterDate">Fecha:</label>
    <input type="date" name="filterDate" id="filterDate" value="<?php echo htmlspecialchars($filterDate); ?>" required>

    <button type="submit">Filtrar</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Compañía de Servicio</th>
            <th>Atributos</th>
            <th>Datos</th>
            <th>Imágenes</th>
            <th>Fecha de Actividad</th>
            <th>Reservar</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($activities)): ?>
            <?php foreach ($activities as $activity): ?>
                <tr>
                    <td><?php echo htmlspecialchars($activity->getIdTBActivity()); ?></td>
                    <td><?php echo htmlspecialchars($activity->getNameTBActivity()); ?></td>
                    <td><?php echo htmlspecialchars($activity->getTbservicecompanyid()); ?></td>
                    <td>
                        <ul>
                            <?php foreach ($activity->getAttributeTBActivityArray() as $attribute): ?>
                                <li><?php echo htmlspecialchars($attribute); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <?php foreach ($activity->getDataAttributeTBActivityArray() as $data): ?>
                                <li><?php echo htmlspecialchars($data); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <?php foreach ($activity->getTbactivityURL() as $url): ?>
                                <li><img src="<?php echo $imageBasePath . htmlspecialchars($url); ?>" alt="Foto" width="50" height="50"></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td><?php echo htmlspecialchars($activity->getActivityDate()); ?></td>
                    <td>
                        <!-- Formulario para realizar la reserva -->
                        <form method="POST" action="reserveAction.php">
                            <input type="hidden" name="activityId" value="<?php echo $activity->getIdTBActivity(); ?>">
                            <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                            <label for="quantity">Cantidad:</label>
                            <input type="number" name="quantity" min="1" required>
                            <button type="submit">Reservar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No hay actividades activas disponibles.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

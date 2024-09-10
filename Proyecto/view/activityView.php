<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Actividades</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="../resources/activityAJAX.js"></script>
    <?php
        include '../business/serviceCompanyBusiness.php';
        $serviceCompanyBusiness = new serviceCompanyBusiness();
        $services = $serviceCompanyBusiness->getAllTBServiceCompanies();
        $imageBasePath = '../images/activity/';
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <header>
        <h1>CRUD Actividades</h1>
        <a href="../index.html">← Volver al inicio</a>
    </header>

    <section id="create">
        <h2>Crear Actividad</h2>
        <form method="post" id="formCreate" action="../business/activityAction.php" enctype="multipart/form-data">
            <label for="nameTBActivity">Nombre de la Actividad: </label>
            <input placeholder="Nombre de la Actividad" type="text" name="nameTBActivity" id="nameTBActivity" required />
            <br><br>
            <label for="serviceId1">Servicio: </label>
            <select name="serviceId" id="serviceId1" required>
                <?php foreach ($services as $service): ?>
                    <option value="<?php echo htmlspecialchars($service->getTbservicecompanyid()); ?>">
                        <?php echo htmlspecialchars($service->getTbservicecompanyid()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <div id="attributes">
                <div>
                    <label for="attribute1">Atributo: </label>
                    <input type="text" name="attributeTBActivityArray" id="attribute1" placeholder="Atributo" required />
                    <label for="dataAttributeTBActivityArray">Dato: </label>
                    <input type="text" name="dataAttributeTBActivityArray" placeholder="Dato" required />
                </div>
            </div>
            <button type="button" id="addAttribute">Agregar otro atributo</button>
            <br><br>
            <label for="imagenes">Selecciona las imágenes (máximo 5): </label>
            <input type="file" name="imagenes[]" id="imagenes" multiple />
            <br><br>
            <input type="hidden" id="statusTBActivity" name="statusTBActivity" value="1">
            <input type="submit" value="Crear" name="create" id="create" />
        </form>
    </section>

    <hr>

    <section>
        <h2>Buscar y Editar Actividades</h2>
        <form id="formSearchOne" method="get">
            <label for="searchOne">Buscar por nombre: </label>
            <input type="text" placeholder="Nombre" name="searchOne" id="searchOne">
            <input type="submit" value="Buscar" />
        </form>
        <br>
        <div id="message" hidden></div>
        <table>
            <thead>
                <tr>
                    <th>Nombre de la Actividad</th>
                    <th>Servicio</th>
                    <th>Fotos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
include_once '../business/activityBusiness.php';
$activityBusiness = new ActivityBusiness();
$allActivities = $activityBusiness->getAllActivities();
$activityFiltered = [];

// Filtrar los resultados si se ha realizado una búsqueda
if (isset($_GET['searchOne'])) {
    $searchTerm = $_GET['searchOne'];
    $activityFiltered = array_filter($allActivities, function ($activity) use ($searchTerm) {
        return stripos($activity->getNameTBActivity(), $searchTerm) !== false;
    });
}
if (count($activityFiltered) > 0) {
    $allActivities = $activityFiltered;
}

if (count($allActivities) > 0) {
    foreach ($allActivities as $current) {
        $assignedService = $serviceCompanyBusiness->getServiceCompany($current->getTbservicecompanyid());
        echo '<tr>'; // Añadir una fila para cada actividad
        echo '<form method="post" action="../business/activityAction.php" enctype="multipart/form-data" onsubmit="return confirmAction(event);">';
        echo '<input type="hidden" name="idTBActivity" value="' . $current->getIdTBActivity() . '">';
        echo '<input type="hidden" name="existingImages" value="' . htmlspecialchars(is_array($current->getTbactivityURL()) ? implode(',', $current->getTbactivityURL()) : $current->getTbactivityURL()) . '">';
    
        echo '<td>';
        echo '<input type="text" name="nameTBActivity" value="' . htmlspecialchars($current->getNameTBActivity()) . '">';
        echo '</td>';
    
        echo '<td>';
        echo '<select name="serviceId" required>';
        foreach ($services as $service) {
            echo '<option value="' . htmlspecialchars($service->getTbservicecompanyid()) . '"';
            if ($service->getTbservicecompanyid() == $current->getTbservicecompanyid()) {
                echo ' selected';
            }
            echo '>' . htmlspecialchars($service->getTbservicecompanyid()) . '</option>';
        }
        echo '</select>';
        echo '</td>';
    
        // Mostrar las imágenes
        echo '<td>';
        $urls = $current->getTbactivityURL();
    
        if (is_string($urls)) {
            $urls = explode(',', $urls);
        }
    
        foreach ($urls as $index => $url) {
            if (!empty($url)) {
                $fullImagePath = $imageBasePath . trim($url);
                echo '<img src="' . htmlspecialchars($fullImagePath) . '" alt="Foto" width="50" height="50" />';
            }
        }
        echo '</td>';
    
        // Seleccionar y eliminar una imagen existente
        echo '<td>';
        echo '<label for="imageIndex">Eliminar imagen: </label>';
        echo '<select name="imageIndex">';
        foreach ($urls as $index => $url) {
            if (!empty($url)) {
                echo '<option value="' . $index . '">Imagen ' . ($index + 1) . '</option>';
            }
        }
        echo '</select>';
        echo '</td>';
    
        // Acciones: Actualizar, Eliminar la actividad o eliminar la imagen
        echo '<td>';
        echo '<input type="submit" value="Actualizar" name="update" />';
        echo '<input type="submit" value="Eliminar" name="delete" />';
        echo '<input type="submit" value="Eliminar Imagen" name="deleteImage" />';
        echo '</td>';
    
        echo '</form>'; // Cerrar el formulario
        echo '</tr>'; // Cerrar la fila
    }
    
    
} else {
    echo '<tr><td colspan="4">No se encontraron resultados</td></tr>';
}
?>
            </tbody>
        </table>
    </section>

    <script>
        document.getElementById('addAttribute').addEventListener('click', function () {
            const attributeContainer = document.createElement('div');
            attributeContainer.innerHTML = `
                <label>Atributo: </label>
                <input type="text" name="attributeTBActivityArray" placeholder="Atributo" required />
                <label>Dato: </label>
                <input type="text" name="dataAttributeTBActivityArray" placeholder="Dato" required />
            `;
            document.getElementById('attributes').appendChild(attributeContainer);
        });

        function confirmAction(event) {
            return confirm('¿Estás seguro de que deseas realizar esta acción?');
        }

        $('.show-attributes').click(function () {
            var activityId = $(this).data('activity-id');
            $('#attributes-' + activityId).toggle();
        });
    </script>
</body>

</html>

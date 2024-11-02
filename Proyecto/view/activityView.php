<?php
require_once '../domain/Owner.php';
require_once '../business/paymentTypeBusiness.php';
require_once '../business/ownerBusiness.php';
require_once '../business/activityBusiness.php';
require_once '../business/serviceCompanyBusiness.php';

    session_start();
    $userLogged = $_SESSION['user'];    
    $ownerBusiness = new ownerBusiness();

    // Definimos los propietarios en función del tipo de usuario
    if ($userLogged->getUserType() == "Administrador") {
        $owners = $ownerBusiness->getAllTBOwners();
        if (!$owners || empty($owners)) {
            echo "<script>alert('No se encontraron propietarios.');</script>";
        }
    } else if ($userLogged->getUserType() == "Propietario") {
        $owners = [$userLogged];
    }else if ($userLogged->getUserType() == "Propietario") {
        $owners = [$userLogged];
    }
 

$_SESSION['owners'] = $owners;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Actividades</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        .required {
            color: red;
        }
    </style>
    <script src="../resources/activityAJAX.js"></script>
    <?php
        $serviceCompanyBusiness = new serviceCompanyBusiness();
        if ($userLogged->getUserType() == "Propietario") { 
            $services = $serviceCompanyBusiness->getAllTBServiceCompaniesByOwner($userLogged->getIdTBOwner());
        } else {
            $services = $serviceCompanyBusiness->getAllTBServiceCompanies();
        }
        $imageBasePath = '../images/activity/';
    ?>
    <script src="../resources/maps.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRQx6ssQ25Ezy99nFNHJYSCVIpE9JeAUI&libraries=marker&callback=initMap&loading=async" defer></script>
</head>

<body>
    <?php
    if ($userLogged->getUserType() == "Propietario") {
        echo '<a href="ownerViewSession.php">← Volver al inicio</a>';
    } else if ($userLogged->getUserType() == "Administrador") {
        echo '<a href="adminView.php">← Volver al inicio</a>';
    } else if ($userLogged->getUserType() == "Turista") {
        echo '<a href="touristView.php">← Volver al inicio</a>';
    }
    ?>
    <header>
        <h1>CRUD Actividades</h1>
        <p><span class="required">*</span> Campos requeridos</p>
    </header>

    <section id="create">
        <h2>Crear Actividad</h2>
        <?php
        if ($userLogged->getUserType() == "Administrador" || $userLogged->getUserType() == "Propietario") {
        ?>
            <form method="post" id="formCreate" action="../business/activityAction.php" enctype="multipart/form-data">
                <label for="nameTBActivity">Nombre de la Actividad <span class="required">*</span></label>
                <input placeholder="Nombre de la Actividad" type="text" name="nameTBActivity" id="nameTBActivity" />
                <br><br>

                <label for="serviceId1">Servicio: </label>
                <select name="serviceId" id="serviceId1">
                    <?php foreach ($services as $service): ?>
                        <option value="<?php echo htmlspecialchars($service->getTbservicecompanyid()); ?>">
                            <?php
                            $serviceName = $serviceCompanyBusiness->getTBServicesByIds($service->getTbserviceid());
                            echo htmlspecialchars(is_array($serviceName) ? implode(', ', array_map(function ($s) {
                                return $s->getTbservicename();
                            }, $serviceName)) : $serviceName);
                            ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <br><br>

                <div id="attributes">
                    <div class="attribute-container">
                        <label for="attributeTBActivityArray">Atributo: </label>
                        <input type="text" name="attributeTBActivityArrayFORM" placeholder="Atributo" />
                        <label for="dataAttributeTBActivityArray">Dato: </label>
                        <input type="text" name="dataAttributeTBActivityArrayFORM" placeholder="Dato" />
                    </div>
                </div>

                <button type="button" id="addAttribute">Agregar otro atributo</button>
                <br><br>

                <label class="datetime-label" for="activityDate">Fecha y Hora de la Actividad: <span class="required">*</span></label>
                <input type="datetime-local" name="activityDate" id="activityDate">
                <br><br>

                <label for="imagenes">Selecciona las imágenes (máximo 5): </label><br>
                <input type="file" name="imagenes[]" id="imagenes" multiple />
                <br><br>
                <span class="required">*</span>Seleccionar una ubicacion diferente a la automatica
                <div id="map" style="height: 500px; width: 100%;">
                </div>
                <br><br>
                <input type="text" style="display:none;" name="latitude" id="latitude">
                <input type="text" style="display:none;" name="longitude" id="longitude">
                <br><br>

                <input type="hidden" id="statusTBActivity" name="statusTBActivity" value="1">
                <input type="submit" value="Crear" name="create" id="create" />
            </form>
        <?php
        }
        ?>
    </section>

    <hr>

    <section>
        <br>
        <div id="message" hidden></div>
        <h2>Actividades Registradas</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre de la Actividad</th>
                    <th>Servicio</th>
                    <th>Atributos y Datos</th>
                    <th>Fotos</th>
                    <th>Fecha y Hora</th>
                    <th>Longitud</th>
                    <th>Latitud</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $activityBusiness = new ActivityBusiness();
                if ($userLogged->getUserType() == "Propietario") {
                    $allActivities = $activityBusiness->getAllActivitiesByOwner($userLogged->getUserId());
                } else {
                    $allActivities = $activityBusiness->getAllActivities();
                }

                if (count($allActivities) > 0) {
                    foreach ($allActivities as $current) {
                        $assignedService = $serviceCompanyBusiness->getServiceCompany($current['tbactivityservicecompanyid']);
                        echo '<tr>';
                        echo '<form method="post" action="../business/activityAction.php" enctype="multipart/form-data" onsubmit="return confirmAction(event);">';
                        echo '<input type="hidden" name="idTBActivity" value="' . $current['tbactivityid'] . '">';
                        echo '<input type="hidden" name="existingImages" value="' . htmlspecialchars(is_array($current['tbactivityurl']) ? implode(',', $current['tbactivityurl']) : $current['tbactivityurl']) . '">';

                        echo '<td>';
                        echo '<input type="text" name="nameTBActivity" value="' . htmlspecialchars($current['tbactivityname']) . '">';
                        echo '</td>';

                        echo '<td>';
                        echo '<select name="serviceId">';
                        foreach ($services as $service) {
                            $serviceName = $serviceCompanyBusiness->getTBServicesByIds($service->getTbserviceid());
                            echo '<option value="' . htmlspecialchars($service->getTbservicecompanyid()) . '"';
                            if ($service->getTbservicecompanyid() == $current['tbactivityservicecompanyid']) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars(is_array($serviceName)
                                ? implode(', ', array_map(function ($s) {
                                    return $s->getTbservicename();
                                }, $serviceName))
                                : $serviceName) . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';

                        echo '<td>';
                        $attributeArray = is_array($current['tbactivityatributearray']) ? $current['tbactivityatributearray'] : explode(',', $current['tbactivityatributearray']);
                        $dataArray = is_array($current['tbactivitydataarray']) ? $current['tbactivitydataarray'] : explode(',', $current['tbactivitydataarray']);

                        $maxCount = max(count($attributeArray), count($dataArray));

                        for ($i = 0; $i < $maxCount; $i++) {
                            $attributeValue = $i < count($attributeArray) ? htmlspecialchars($attributeArray[$i]) : '';
                            $dataValue = $i < count($dataArray) ? htmlspecialchars($dataArray[$i]) : '';

                            echo '<div>';
                            echo '<input type="text" name="attributeTBActivityArrayTable" value="' . $attributeValue . '" placeholder="Atributo" />';
                            echo '<input type="text" name="dataAttributeTBActivityArrayTable" value="' . $dataValue . '" placeholder="Dato" />';
                            echo '</div>';
                        }
                        echo '</td>';



                        echo '<td>';
                        $urls = is_string($current['tbactivityurl']) ? explode(',', $current['tbactivityurl']) : $current['tbactivityurl'];
                        foreach ($urls as $index => $url) {
                            if (!empty($url)) {
                                $fullImagePath = $imageBasePath . trim($url);
                                echo '<img src="' . htmlspecialchars($fullImagePath) . '" alt="Foto" width="50" height="50" />';
                            }
                        }
                        echo '</td>';

                        echo '<td>';
                        echo '<input type="datetime-local" name="activityDate" value="' . htmlspecialchars($current['tbactivitydate']) . '" />';
                        echo '</td>';

                        echo '<td>';
                        echo '<input type="text" name="longitude" value="' . htmlspecialchars($current['tbactivitylongitude']) . '" />';
                        echo '</td>';

                        echo '<td>';
                        echo '<input type="text" name="latitude" value="' . htmlspecialchars($current['tbactivitylatitude']) . '" />';
                        echo '</td>';

                        echo '<td>';
                        if ($userLogged->getUserType() == "Administrador" || $userLogged->getUserType() == "Propietario") {
                            echo '<input type="submit" value="Actualizar" name="update" />';
                            echo '<input type="submit" value="Eliminar" name="delete" />';
                            echo '<select name="imageIndex">';
                            foreach ($urls as $index => $url) {
                                if (!empty($url)) {
                                    echo '<option value="' . $index . '">Eliminar Imagen ' . ($index + 1) . '</option>';
                                }
                            }
                            echo '</select>';
                            echo '<input type="submit" value="Eliminar Imagen" name="deleteImage" />';
                        }
                        echo '</td>';

                        echo '</form>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="8">No se encontraron resultados</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </section>

    <script>
        document.getElementById('addAttribute').addEventListener('click', function() {
            const attributeContainer = document.createElement('div');
            attributeContainer.innerHTML = `
            <label for="attributeTBActivityArray">Atributo: </label>
            <input type="text" name="attributeTBActivityArrayFORM" placeholder="Atributo" />
            <label for="dataAttributeTBActivityArray">Dato: </label>
            <input type="text" name="dataAttributeTBActivityArrayFORM" placeholder="Dato" />
        `;
            document.getElementById('attributes').appendChild(attributeContainer);
        });

        function confirmAction(event) {
            return confirm('¿Estás seguro de que deseas realizar esta acción?');
        }

        $('.show-attributes').click(function() {
            var activityId = $(this).data('activity-id');
            $('#attributes-' + activityId).toggle();
        });
    </script>
</body>

</html>
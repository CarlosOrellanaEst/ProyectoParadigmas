<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Servicios de Empresas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php
            include '../business/serviceCompanyBusiness.php';
            include '../business/TouristCompanyBusiness.php';

            $serviceCompanyBusiness = new serviceCompanyBusiness();
            $services = $serviceCompanyBusiness->getAllTBServices();
            $touristCompanyBusiness = new TouristCompanyBusiness();
            $companies = $touristCompanyBusiness->getAll();
        ?>
    <script src="../resources/serviceCompanyAJAX.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <header>
        <h1>CRUD Servicios de Empresas</h1>
        <a href="../index.html">← Volver al inicio</a>
    </header>

    <section id="create">
        <h2>Crear Servicios de Empresa</h2>
        <form method="post" id="formCreate" action="../business/serviceCompanyAction.php" enctype="multipart/form-data">
            <label for="companyID">Nombre de la Empresa Turistica: </label>
            <select name="companyID" id="companyID" >
            <?php foreach ($companies as $company): ?>
                    <option value="<?php echo htmlspecialchars($company->getTbtouristcompanyid()); ?>">
                        <?php echo htmlspecialchars($company->getTbtouristcompanymagicname()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>

            <div id="attributes">
                <div>
                    <label for="serviceId">Atributo: </label>
                    <select name="serviceId" id="serviceId" required>
                    <?php foreach ($services as $service): ?>
                    <option value="<?php echo htmlspecialchars($service->getIdTbservice()); ?>">
                        <?php echo htmlspecialchars($service->getTbservicename()); ?>
                    </option>
                <?php endforeach; ?>
                    </select>
                    <label for="dataAttributeTBActivityArray">Dato: </label>
                    <input type="file" name="dataAttributeTBActivityArray" placeholder="Dato" required />
                </div>
            </div>
            <button type="button" id="addAttribute">Agregar otro atributo</button>
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
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($current->getNameTBActivity()) . '</td>';
                        echo '<td>';
                        echo '<form method="post" action="../business/activityAction.php" onsubmit="return confirmAction(event);" enctype="multipart/form-data">';
                        echo '<input type="hidden" name="idTBActivity" value="' . $current->getIdTBActivity() . '">';
                        echo '<select name="nameTBActivity" required>';
                        // Aquí deberías cargar las opciones desde la base de datos o definirlas manualmente
                        echo '<option value="' . htmlspecialchars($current->getNameTBActivity()) . '" selected>' . htmlspecialchars($current->getNameTBActivity()) . '</option>';
                        echo '</select>';
                        echo '<button type="button" class="show-attributes" data-activity-id="' . $current->getIdTBActivity() . '">Mostrar Atributos</button>';
                        echo '<input type="submit" value="Actualizar" name="update" />';
                        echo '<input type="submit" value="Eliminar" name="delete"/>';
                        echo '<div id="attributes-' . $current->getIdTBActivity() . '" class="attributes-table" style="display:none;">';
                        echo '<table>';
                        echo '<tr><th>Atributo</th><th>Dato</th></tr>';

                        foreach ($current->getAttributeTBActivityArray() as $index => $attribute) {
                            echo '<tr>';
                            echo '<td><select name="attributeTBActivityArray[]" required>';
                            // Aquí deberías cargar las opciones desde la base de datos o definirlas manualmente
                            echo '<option value="' . htmlspecialchars($attribute) . '" selected>' . htmlspecialchars($attribute) . '</option>';
                            echo '</select></td>';
                            echo '<td><input type="file" name="dataAttributeTBActivityArray[]" /></td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '</div>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="2">No se encontraron resultados</td></tr>';
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
                <select name="serviceId[]" required>
                    <?php foreach ($services as $service): ?>
                    <option value="<?php echo htmlspecialchars($service->getIdTbservice()); ?>">
                        <?php echo htmlspecialchars($service->getTbservicename()); ?>
                    </option>
                <?php endforeach; ?>
                </select>
                <label>Dato: </label>
                <input type="file" name="dataAttributeTBActivityArray[]" required />
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

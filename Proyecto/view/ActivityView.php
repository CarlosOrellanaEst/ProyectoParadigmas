<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Actividades</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="../resources/activityAJAX.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <header>
        <h1>CRUD Actividades</h1>
        <a href="../index.html">← Volver al inicio</a>
    </header>

    <section id="create">
        <h2>Crear Actividad</h2>
        <form method="post" id="formCreate" action="../business/activityAction.php">
            <label for="nameTBActivity">Nombre de la Actividad: </label>
            <input placeholder="Nombre de la Actividad" type="text" name="nameTBActivity" id="nameTBActivity" required />
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
                        echo '<form method="post" action="../business/activityAction.php" onsubmit="return confirmAction(event);">';
                        echo '<input type="hidden" name="idTBActivity" value="' . $current->getIdTBActivity() . '">';
                        echo '<input type="text" name="nameTBActivity" value="' . htmlspecialchars($current->getNameTBActivity()) . '">';
                        echo '<button type="button" class="show-attributes" data-activity-id="' . $current->getIdTBActivity() . '">Mostrar Atributos</button>';
                        echo '<input type="submit" value="Actualizar" name="update" />';
                        echo '<input type="submit" value="Eliminar" name="delete"/>';
                        echo '<div id="attributes-' . $current->getIdTBActivity() . '" class="attributes-table" style="display:none;">';
                        echo '<table>';
                        echo '<tr><th>Atributo</th><th>Dato</th></tr>';

                        foreach ($current->getAttributeTBActivityArray() as $index => $attribute) {
                            echo '<tr>';
                            echo '<td><input type="text" name="attributeTBActivityArray[]" value="' . htmlspecialchars($attribute) . '"></td>';
                            echo '<td><input type="text" name="dataAttributeTBActivityArray[]" value="' . htmlspecialchars($current->getDataAttributeTBActivityArray()[$index]) . '"></td>';
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

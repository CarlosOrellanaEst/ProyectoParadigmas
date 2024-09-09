<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Actividades</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        td,
        th {
            border-right: 1px solid;
        }

        .text {
            width: 180px;
            height: 80px;
        }

        .attribute-container {
            margin-bottom: 10px;
        }
        .required {
            color: red;
        }
    </style>
    <script src="../resources/activityAJAX.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
     <a href="../index.html">← Volver al inicio</a>
    <header>
        <h1>CRUD Actividades</h1>
        <p><span class="required">*</span> Campos requeridos</p>
       
    </header>

    <section id="create">
        <h2>Crear Actividad</h2>
        <form method="post" id="formCreate" action="../business/activityAction.php">
        <label for="nameTBActivity">Nombre de la Actividad <span class="required">*</label>
        <input placeholder="Nombre de la Actividad" type="text" name="nameTBActivity" id="nameTBActivity" required />

            <br><br>

            <div id="attributes">
                <div class="attribute-container">
                <label for="attribute1">Atributo <span class="required">*</label>
                <input type="text" name="attributeTBActivityArray[]" id="attribute1" placeholder="Atributo" required /><br><br>
                    <label for="dataAttributeTBActivityArray[]">Dato <span class="required">*</label>
                    <input type="text" name="dataAttributeTBActivityArray[]" placeholder="Dato" required />
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
                    <th>Atributos</th>
                    <th>Datos de Atributos</th>
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
                    $activityFiltered = array_filter($allActivities, function($activity) use ($searchTerm) {
                        return stripos($activity->getNameTBActivity(), $searchTerm) !== false;
                    });
                }
                if (count($activityFiltered) > 0) {
                    $allActivities = $activityFiltered;
                }

                if (count($allActivities) > 0) {
                    foreach ($allActivities as $current) {
                        echo '<form method="post" action="../business/activityAction.php" onsubmit="return confirmAction(event);">';
                        echo '<tr>';
                        echo '<input type="hidden" name="idTBActivity" value="' . $current->getIdTBActivity() . '">';
                        echo '<td><input type="text" name="nameTBActivity" value="' . htmlspecialchars($current->getNameTBActivity()) . '"></td>';
                        echo '<td>';
                        foreach ($current->getAttributeTBActivityArray() as $attribute) {
                            echo '<input type="text" name="attributeTBActivityArray[]" value="' . htmlspecialchars($attribute) . '" required><br>';
                        }
                        echo '</td>';
                        echo '<td>';
                        foreach ($current->getDataAttributeTBActivityArray() as $data) {
                            echo '<input type="text" name="dataAttributeTBActivityArray[]" value="' . htmlspecialchars($data) . '" required><br>';
                        }
                        echo '</td>';
                        echo '<input type="hidden" name="statusTBActivity" value="1">';
                        echo '<td>';
                        echo '<input type="submit" value="Actualizar" name="update" />';
                        echo '<input type="submit" value="Eliminar" name="delete"/>';
                        echo '</td>';
                        echo '</tr>';
                        echo '</form>';
                    }
                } else {
                    echo '<tr><td colspan="4">No se encontraron resultados</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </section>

    <script>
        // Función para agregar otro campo de atributo y dato
        document.getElementById('addAttribute').addEventListener('click', function () {
            const attributeContainer = document.createElement('div');
            attributeContainer.className = 'attribute-container';
            attributeContainer.innerHTML = `
                <label>Atributo: </label>
                <input type="text" name="attributeTBActivityArray[]" placeholder="Atributo" required />
                <label>Dato: </label>
                <input type="text" name="dataAttributeTBActivityArray[]" placeholder="Dato" required />
            `;
            document.getElementById('attributes').appendChild(attributeContainer);
        });

        function confirmAction(event) {
            return confirm('¿Estás seguro de que deseas realizar esta acción?');
        }
    </script>
</body>

</html>

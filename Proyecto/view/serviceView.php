<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <a href="../index.html">← Volver al inicio</a>
    <title>CRUD Servicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        td, th {
            border-right: 1px solid;
        }
        .required {
            color: red;
        }
    </style>
    <script src="../resources/AJAXCreateService.js"></script>
</head>
<body>
    <header> 
        <h1>CRUD Servicios</h1>
        <p><span class="required">*</span> Campos requeridos</p>
    </header>
    <section>
        <form method="POST"  id="formCreate" enctype="multipart/form-data">
            <label for="serviceName">Nombre del servicio <span class="required">*</label>
            <input placeholder="servicio" type="text" name="serviceName" id="serviceName"/><br><br>
            <label for="images">Selecciona las imágenes del servicio (máximo 5):</label><br>
            <input type="file" id="images" name="images[]" accept="image/*" multiple><br>

            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>
    <br><br>
    <section>
<!--         <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                 /*    $serviceBusiness = new serviceBusiness();
                    $allRolls = $rollBusiness->getAllTBRolls();
                    $rollsFiltered = [];

                    // Filtrar los resultados si se ha realizado una búsqueda
                    if (isset($_GET['searchOne'])) {
                        $searchTerm = $_GET['searchOne'];
                        $rollsFiltered  = array_filter($allRolls, function($roll) use ($searchTerm) {
                            return stripos($roll->getNameTBRoll(), $searchTerm) !== false;
                        });
                    }
                    if (count($rollsFiltered) > 0) {
                        $allRolls = $rollsFiltered;
                    }

                    foreach ($allRolls as $current) {
                        echo '<form method="post" action="../business/rollAction.php" onsubmit="return confirmDelete(event);">';
                        echo '<input type="hidden" name="rollID" value="' . $current->getIdTBRoll() . '">';
                        echo '<tr>';
                            echo '<td><input type="text" name="rollName" value="' . $current->getNameTBRoll() . '"/></td>';
                            echo '<td><input type="text" name="rollDescription" value="' . $current->getDescriptionTBRoll() . '"/></td>';
                            echo '<td>';
                                echo '<input type="submit" value="Actualizar" name="update"/>';
                                echo '<input type="submit" value="Eliminar" name="delete"/>';
                            echo '</td>';
                        echo '</tr>';
                        echo '</form>'; 
                    }*/
                ?>
            </tbody>
        </table> -->
    </section>
</body>
</html>
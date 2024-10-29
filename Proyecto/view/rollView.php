<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <a href="adminView.php">← Volver al inicio</a>
    <title>CRUD Roll</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        td, th {
            border-right: 1px solid;
        }
        .required {
            color: red;
        }
    </style>
    
    <?php
    include '../business/rollBusiness.php';
    ?>
    <script src="../resources/rollView.js"></script>
    <script src="../resources/AJAXCreateRoll.js"></script>
</head>
<body>
    <header> 
        <h1>CRUD Roles</h1>
        <p><span class="required">*</span> Campos requeridos</p>
    </header>
    <section>
        <form method="post"  id="formCreate" >
            <label for="name">Nombre <span class="required">*</label>
            <input placeholder="nombre" type="text" name="rollName" id="name"/><br><br>
            <label for="description">Descripción</label>
            <input placeholder="descripción" type="text" name="rollDescription" id="description"/><br>
            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>
    <br><br>
    <section>
        <form id="formSearchOne" method="get">
            <label for="searchOne">Buscar por nombre</label>
            <input type="text" required placeholder="nombre del rol" name="searchOne" id="searchOne">
            <input type="submit" value="Buscar"/>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rollBusiness = new RollBusiness();
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
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>
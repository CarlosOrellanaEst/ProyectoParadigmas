<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CRUD Roll</title>

<!--     <link rel="icon" href="../resources/icons/bull.png"> -->
<!--     <link rel="stylesheet" href="../resources/css/css.css"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        td, th {
            border-right: 1px solid;
        }
    </style>
    
    <?php
    include '../business/rollBusiness.php';
    ?>
</head>
<body>
    <header> 
        <h1>CRUD Rolles</h1>
    </header>

    <section id="formCreate">
        <form method="post" action="../business/rollAction.php">
            <label for="name">Nombre</label>
            <input required placeholder="nombre" type="text" name="rollName" id="name"/></td>
            <label for="description">Descripción</label>
            <input placeholder="descripción" type="text" name="rollDescription" id="description"/></td>
            <input type="submit" value="Crear" name="create" id="create"/></td>
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
                    echo '<form method="post" action="../business/rollAction.php">';
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
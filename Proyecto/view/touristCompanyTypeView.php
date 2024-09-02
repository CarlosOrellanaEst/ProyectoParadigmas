<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Tipo de empresa turística</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <style>
            td, th {
                border-right: 1px solid;
            }
            .text{
                width: 180px;
                height: 80px;
            }
        </style>
        <?php
            include '../business/touristCompanyTypeBusiness.php';
        ?>
        <script src="../resources/touristCompanyTypeAJAX.js"></script>
        <script src="../resources/touristCompanyTypeView.js"></script>
        
    </head>
    <body>
        <a href="../index.html">← Volver al inicio</a>
        <header> 
            <h1>CRUD Tipo de empresa turística</h1>
        </header>
        <section>
            <form method="post"  id="formCreate">
                <label for="name">Nombre de la actividad: </label>
                <input placeholder="Nombre de la actividad" type="text" name="nameTouristCompanyType" id="name"/>
                <label for="description">Descripción de la actividad: </label>
                <input class="text" placeholder="Descripción" type="text" name="description" id="description"/>
                <input type="submit" value="Crear" name="create" id="create"/>
            </form>
        </section>
        <br>
        <section>
            <form id="formSearchOne" method="get">
                <label for="searchOne">Buscar por nombre: </label>
                <input type="text" required placeholder="Nombre" name="searchOne" id="searchOne">
                <input type="submit" value="Buscar"/>
            </form>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciónes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $touristCompanyTypeBusiness = new touristCompanyTypeBusiness();
                        $all = $touristCompanyTypeBusiness->getAll();
                        $companyTypeFiltered = [];

                        // Filtrar los resultados si se ha realizado una búsqueda
                        if (isset($_GET['searchOne'])) {
                            $searchTerm = $_GET['searchOne'];
                            $companyTypeFiltered  = array_filter($all, function($touristCompanyTypeBusiness) use ($searchTerm) {
                                return stripos($touristCompanyTypeBusiness->getName(), $searchTerm) !== false;
                            });
                        }
                        if (count($companyTypeFiltered) > 0) {
                            $all = $companyTypeFiltered;
                        }

                        if (count($all) > 0) {
                            foreach ($all as $current) {
                                echo '<form method="post" action="../business/touristCompanyTypeAction.php" onsubmit="return confirmAction(event);">';
                                echo '<input type="hidden" name="tbtouristcompanytypeid" value="' . $current->getId() . '">';
                                echo '<tr>';
                                    echo '<td><input type="text" name="name" value="' . $current->getName() . '"/></td>';
                                    echo '<td><input type="text" name="description" value="' . $current->getDescription() . '"/></td>';
                                    echo '<td>';
                                        echo '<input type="submit" value="Actualizar" name="update"/>';
                                        echo '<input type="submit" value="Eliminar" name="delete"/>';
                                    echo '</td>';
                                echo '</tr>';
                                echo '</form>';
                            }
                        } else {
                            echo '<tr>';
                                echo '<td colspan="5" style="text-align: center;">No hay registros</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </section>
    </body>
</html>    
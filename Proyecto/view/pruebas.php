<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <a href="../index.html">← Volver al inicio</a>
    <title>Empresa turística</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php include '../business/touristCompanyBusiness.php'; 
     include '../business/ownerBusiness.php'; 
     include '../business/touristCompanyTypeBusiness.php'; 
     include '../business/PhotoBusiness.php'; 
    $ownerBusiness = new OwnerBusiness();
        $owners = $ownerBusiness->getAllTBOwner();
        $touristCompanyTypeBusiness = new touristCompanyTypeBusiness();
        $touristCompanyTypes = $touristCompanyTypeBusiness->getAll();
        ?>
    <script src="../resources/touristCompanyView.js"></script>
</head>

<body>
    <header>
        <h1>CRUD Empresa turística</h1>
    </header>

    <!-- Botón para abrir el modal -->
    <button id="btnOpenModal">Agregar imágenes</button>

    <!-- Modal con el formulario de subir imágenes -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Subir Imágenes</h2>
            <form method="post" action="../business/PhotoAction.php" enctype="multipart/form-data">
                <label for="imagenes">Selecciona las imágenes (máximo 5):</label>
                <input type="file" name="imagenes[]" accept="image/*" multiple>
                <input type="submit" value="Crear" name="create" id="create" />
            </form>
        </div>
    </div>

    <!-- Formulario para crear empresas turísticas -->
    <section id="create">
        <form method="post" action="../business/touristCompanyAction.php" onsubmit="return confirmAction(event);">
            <label for="legalName">Nombre legal: </label>
            <input placeholder="Nombre legal" type="text" name="legalName" id="legalName" />
            <label for="magicName">Nombre mágico: </label>
            <input placeholder="Nombre mágico" type="text" name="magicName" id="magicName" />

            <label for="ownerId">Dueño: </label>
            <select name="ownerId" id="ownerId">
                <option value="0">Ninguno</option>
                <?php foreach ($owners as $owner): ?>
                    <option value="<?php echo htmlspecialchars($owner->getIdTBOwner()); ?>">
                        <?php echo htmlspecialchars($owner->getName() . ' ' . $owner->getSurnames()); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="companyType">Tipo de empresa: </label>
            <select name="companyType" id="companyType">
                <option value="0">Ninguno</option>
                <?php foreach ($touristCompanyTypes as $touristCompanyType): ?>
                    <option value="<?php echo htmlspecialchars($touristCompanyType->getId()); ?>">
                        <?php echo htmlspecialchars($touristCompanyType->getName()); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="hidden" name="status" value="1">
            <input type="submit" value="Crear" name="create" id="create" />
        </form>
    </section>

    <br>

    <!-- Formulario de búsqueda y tabla de empresas turísticas -->
    <section>
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
                    <th>Nombre legal</th>
                    <th>Nombre mágico</th>
                    <th>Dueño</th>
                    <th>Tipo de empresa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Código PHP para manejar la tabla
                $touristCompanyBusiness = new touristCompanyBusiness();
                $ownerBusiness = new OwnerBusiness();
                $touristCompanyTypeBusiness = new touristCompanyTypeBusiness();
                $all = $touristCompanyBusiness->getAll();
                $allowners = $ownerBusiness->getAllTBOwner();
                $alltouristCompanyTypes = $touristCompanyTypeBusiness->getAll();
                $touristCompanyFiltered = [];

                // Filtrar los resultados si se ha realizado una búsqueda
                if (isset($_GET['searchOne'])) {
                    $searchTerm = $_GET['searchOne'];
                    $touristCompanyFiltered = array_filter($all, function($touristCompanyBusiness) use ($searchTerm) {
                        return stripos($touristCompanyBusiness->getLegalName(), $searchTerm) !== false;
                    });
                }
                if (count($touristCompanyFiltered) > 0) {
                    $all = $touristCompanyFiltered;
                }

                if (count($all) > 0) {
                    foreach ($all as $current) {
                        $assignedCompanyType = $touristCompanyTypeBusiness->getById($current->getCompanyType());
                        $assignedOwner = $ownerBusiness->getTBOwner($current->getOwner());
                        echo '<form method="post" action="../business/touristCompanyAction.php" onsubmit="return confirmAction(event);">';
                        echo '<tr>';

                        echo '<td><input type="text" name="legalName" value="'. htmlspecialchars($current->getLegalName()) .'"></td>';
                        echo '<td><input type="text" name="magicName" value="' . htmlspecialchars($current->getMagicName()) . '"></td>';
                        echo '<td>';
                        echo '<select name="ownerId">';
                        foreach ($allowners as $owner) {
                            echo '<option value="' . htmlspecialchars($owner->getIdTBOwner()) . '"';
                            if ($owner->getIdTBOwner() == $current->getOwner()) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($owner->getFullName()) . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '<td>';
                        echo '<select name="companyType">';
                        foreach ($alltouristCompanyTypes as $touristCompanyType) {
                            echo '<option value="' . htmlspecialchars($touristCompanyType->getId()) . '"';
                            if ($touristCompanyType->getId() == $current->getCompanyType()) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($touristCompanyType->getName()) . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '<input type="hidden" name="status" value="1">';
                        echo '<td>';
                        echo '<input type="hidden" name="id" value="' . $current->getId() . '">';
                        echo '<input type="submit" value="Actualizar" name="update" />';
                        echo '<input type="submit" value="Eliminar" name="delete"/>';
                        echo '</td>';
                        echo '</tr>';
                        echo '</form>';
                    }
                } else {
                    echo '<tr><td colspan="6">No se encontraron resultados</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showAlertBasedOnURL();
            });
        </script>
    </section>

    <script>
        // JavaScript para manejar el modal
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("btnOpenModal");
        var span = document.getElementsByClassName("close")[0];

        // Cuando el usuario hace clic en el botón, abre el modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // Cuando el usuario hace clic en la 'x', cierra el modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Cuando el usuario hace clic fuera del modal, lo cierra
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>

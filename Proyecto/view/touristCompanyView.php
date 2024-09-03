<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>CRUD Empresa turística</title>
    <style>
        td, th {
            border-right: 1px solid;
        }
        .text {
            width: 180px;
            height: 80px;
        }
    </style>
    <?php
    include_once '../business/touristCompanyBusiness.php';
    include_once '../business/touristCompanyTypeBusiness.php';
    include_once '../business/ownerBusiness.php';

    $ownerBusiness = new OwnerBusiness();
    $owners = $ownerBusiness->getAllTBOwner();
    $touristCompanyTypeBusiness = new touristCompanyTypeBusiness();
    $touristCompanyTypes = $touristCompanyTypeBusiness->getAll();
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../resources/touristCompanyView.js"></script>
</head>
<body>
    <header>
        <h1>CRUD Empresa turística</h1>
    </header>
    <a href="../index.html">← Volver al inicio</a>

    <section id="create">
        <form method="post" id="formCreate" action="../business/touristCompanyAction.php" enctype="multipart/form-data">
            <label for="legalName">Nombre legal: </label>
            <input placeholder="Nombre legal" type="text" name="legalName" id="legalName" required />
            <label for="magicName">Nombre mágico: </label>
            <input placeholder="Nombre mágico" type="text" name="magicName" id="magicName" required />

            <label for="ownerId">Dueño: </label>
            <select name="ownerId" id="ownerId" required>
                <option value="0">Ninguno</option>
                <?php foreach ($owners as $owner): ?>
                    <option value="<?php echo htmlspecialchars($owner->getIdTBOwner()); ?>">
                        <?php echo htmlspecialchars($owner->getName() . ' ' . $owner->getSurnames()); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="companyType">Tipo de empresa: </label>
            <select name="companyType" id="companyType" required>
                <option value="0">Ninguno</option>
                <?php foreach ($touristCompanyTypes as $touristCompanyType): ?>
                    <option value="<?php echo htmlspecialchars($touristCompanyType->getId()); ?>">
                        <?php echo htmlspecialchars($touristCompanyType->getName()); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="imagenes">Imágenes: </label>
            <input type="file" name="imagenes[]" id="imagenes" multiple />

            <input type="hidden" id="status" name="status" value="1">
            <input type="submit" value="Crear" name="create" id="create" />
        </form>
    </section>

    <br>

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
                    $touristCompanyFiltered = array_filter($all, function($touristCompany) use ($searchTerm) {
                        return stripos($touristCompany->getLegalName(), $searchTerm) !== false;
                    });
                }
                if (count($touristCompanyFiltered) > 0) {
                    $all = $touristCompanyFiltered;
                }

                if (count($all) > 0) {
                    foreach ($all as $current) {
                        $assignedCompanyType = $touristCompanyTypeBusiness->getById($current->getCompanyType());
                        $assignedOwner = $ownerBusiness->getTBOwner($current->getOwner());
                        echo '<tr>';
                        echo '<form method="post" action="../business/touristCompanyAction.php" onsubmit="return confirmAction(event);">';
                        echo '<td><input type="text" name="legalName" value="'. htmlspecialchars($current->getLegalName()) .'" required></td>';
                        echo '<td><input type="text" name="magicName" value="' . htmlspecialchars($current->getMagicName()) . '" required></td>';
                        echo '<td>';
                        echo '<select name="ownerId" required>';
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
                        echo '<select name="companyType" required>';
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
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($current->getId()) . '">';
                        echo '<input type="submit" value="Actualizar" name="update" />';
                        echo '<input type="submit" value="Eliminar" name="delete"/>';
                        echo '</td>';
                        echo '</form>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">No se encontraron resultados</td></tr>';
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
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD Propietarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        td, th {
            border-right: 1px solid;
        }
        .required {
            color: red;
        }
    </style>
    <script src="../resources/ownerView.js"></script>
    <script src="../resources/AJAXOwner.js"></script>
</head>
<body>
    <header>
        <a href="adminView.php">← Volver al inicio</a>
        <h1>CRUD Propietarios</h1>
        <p><span class="required">*</span> Campos requeridos</p>
    </header>
    <form id="formCreate" method="post" enctype="multipart/form-data" action="../business/ownerAction.php">
            <label for="name">Nombre </label>
            <input placeholder="nombre" type="text" name="ownerName" id="name"/><br><br>
            <label for="surnames">Apellidos</label>
            <input placeholder="apellidos" type="text" name="ownerSurnames" id="surnames"/><br><br>
            <label for="idType">Tipo de Identificación</label>
            <select name="idType" id="idType">
                <option value="CR">Cédula Nacional de Costa Rica</option>
                <option value="foreign">Extranjero</option>
            </select><br><br>
            <label for="legalIdentification">Identificación Legal <span class="required">*</label>
            <input placeholder="identificacionLegal" type="text" name="ownerLegalIdentification" id="legalIdentification"/><br><br>
            <label for="phone">Teléfono</label>
            <input placeholder="telefono" type="text" name="ownerPhone" id="phone"/><br><br>
            <label for="email">Correo <span class="required">*</label>
            <input placeholder="correo" type="text" name="ownerEmail" id="email"/><br><br>
            <label for="direction">Dirección</label>
            <input placeholder="direccion" type="text" name="ownerDirection" id="direction"/><br><br>
            <input type="file" name="imagen" id="imagen"><br><br>
            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>

    <br>
    <section>
        <form id="formSearchOne" method="get">
            <label for="searchOne">Buscar por nombre</label>
            <input type="text" required placeholder="nombre del propietario" name="searchOne" id="searchOne">
            <input type="submit" value="Buscar"/>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Tipo de Identificación</th>
                    <th>Identificación Legal</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../business/ownerBusiness.php';
                $ownerBusiness = new ownerBusiness();
                $allowners = $ownerBusiness->getAllTBOwner();
                $ownersFiltered = [];

                // Filtrar los resultados si se ha realizado una búsqueda
                if (isset($_GET['searchOne'])) {
                    $searchTerm = $_GET['searchOne'];
                    $ownersFiltered = array_filter($allowners, function ($owner) use ($searchTerm) {
                        return stripos($owner->getName(), $searchTerm) !== false;
                    });
                }
                if (count($ownersFiltered) > 0) {
                    $allowners = $ownersFiltered;
                }

                foreach ($allowners as $current) {
                    echo '<form method="post" action="../business/ownerAction.php" onsubmit="return confirmDelete(event);" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="ownerID" value="' . $current->getIdTBOwner() . '">';
                    echo '<input type="hidden" name="userID" value="' . $current->getId(). '">';
                    echo '<tr>';
                    echo '<td><input type="text" name="ownerName" value="' . $current->getName() . '"/></td>';
                    echo '<td><input type="text" name="ownerSurnames" value="' . $current->getSurnames() . '"/></td>';
                    echo '<td>
                    <select name="idType">
                        <option value="CR">Cédula Nacional de Costa Rica</option>
                        <option value="foreign">Extranjero</option>
                    </select>
                </td>';
                    echo '<td><input type="text" name="ownerLegalIdentification" value="' . $current->getLegalIdentification() . '"/></td>';
                    echo '<td><input type="text" name="ownerPhone" value="' . $current->getPhone() . '"/></td>';
                    echo '<td><input type="text" name="ownerEmail" value="' . $current->getEmail() . '"/></td>';
                    echo '<td><input type="text" name="ownerDirection" value="' . $current->getDirectionTBOwner() . '"/></td>';

                    // Mostrar la imagen
                    $photoUrl = $current->getPhotoURLTBOwner();
                    echo '<td><img src="../images/' . $photoUrl . '" alt="Foto" width="75" height="75" /></td>';

                    echo '<td>';
                    // echo '<input type="file" name="newImage" accept="image/*" /><br />';
                    echo '<input type="submit" value="Actualizar" name="update" />';
                    echo '<input type="submit" value="Eliminar" name="delete" />';
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

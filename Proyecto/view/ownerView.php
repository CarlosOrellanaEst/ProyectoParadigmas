<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CRUD Propietarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        td, th {
            border-right: 1px solid;
        }
    </style>
    <?php
    include '../business/ownerBusiness.php';
    ?>
    <script src="../resources/ownerView.js"></script>
</head>
<body>
    <header> 
    <a href="../index.html">← Volver al inicio</a>
        <h1>CRUD Propietarios</h1>
    </header>
    <section id="formCreate">
        <form method="post" action="../business/ownerAction.php" enctype="multipart/form-data">
            <label for="name">Nombre</label>
            <input required placeholder="nombre" type="text" name="ownerName" id="name"/>
            <label for="surnames">Apellidos</label>
            <input placeholder="apellidos" type="text" name="ownerSurnames" id="surnames"/>
            <label for="idType">Tipo de Identificación</label>
            <select name="idType" id="idType">
                <option value="CR">Cédula Nacional de Costa Rica</option>
                <option value="foreign">Extranjero</option>
            </select>
            <label for="legalIdentification">Identificación Legal</label>
            <input placeholder="identificacionLegal" type="text" name="ownerLegalIdentification" id="legalIdentification"/>
            <label for="phone">Teléfono</label>
            <input placeholder="telefono" type="text" name="ownerPhone" id="phone"/>
            <label for="email">Correo</label>
            <input placeholder="correo" type="text" name="ownerEmail" id="email"/>
            <label for="direction">Dirección</label>
            <input placeholder="direccion" type="text" name="ownerDirection" id="direction"/>
            <input type="file" name="imagen" required>
            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>

    <br><br>
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
                $ownerBusiness = new ownerBusiness();
                $allowners = $ownerBusiness->getAllTBOwner();
                $ownersFiltered = [];

                // Filtrar los resultados si se ha realizado una búsqueda
                if (isset($_GET['searchOne'])) {
                    $searchTerm = $_GET['searchOne'];
                    $ownersFiltered = array_filter($allowners, function($owner) use ($searchTerm) {
                        return stripos($owner->getName(), $searchTerm) !== false;
                    });
                }
                if (count($ownersFiltered) > 0) {
                    $allowners = $ownersFiltered;
                }

                foreach ($allowners as $current) {
                    echo '<form method="post" action="../business/ownerAction.php" onsubmit="return confirmDelete(event);" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="ownerID" value="' . $current->getIdTBOwner() . '">';
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

                    // Campo de selección para el tipo de identificación
                  

                    // Botones de acción
                    echo '<td>';
                    echo '<input type="file" name="newImage" accept="image/*" /><br />';
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

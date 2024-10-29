<?php
require '../domain/Owner.php';
require '../business/ownerBusiness.php';

session_start();
$userLogged = $_SESSION['user'];
$ownerBusiness = new ownerBusiness();

// Definimos los propietarios en función del tipo de usuario
if ($userLogged->getUserType() == "Administrador") {
    $owners = $ownerBusiness->getAllTBOwners();
    if (!$owners || empty($owners)) {
        echo "<script>alert('No se encontraron propietarios.');</script>";
    }
} else if ($userLogged->getUserType() == "Propietario") {
    $owners = [$userLogged];
}

// Guardamos la lista de propietarios en la sesión para usarla abajo
$_SESSION['owners'] = $owners;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>CRUD Propietarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        td,
        th {
            border-right: 1px solid;
        }

        .required {
            color: red;
        }
    </style>
    <script src="../resources/ownerView.js"></script> <!-- Solo se necesita este archivo -->
</head>

<body>
    <header>
        <?php
        if ($userLogged->getUserType() == "Propietario") {
            echo '<a href="ownerViewSession.php">← Volver al inicio</a>';
        } else if ($userLogged->getUserType() == "Administrador") {
            echo '<a href="adminView.php">← Volver al inicio</a>';
        }
        ?>
        <h1>CRUD Propietarios</h1>
        <p><span class="required">*</span> Campos requeridos</p>
    </header>
    <section>
        <?php
        if ($userLogged->getUserType() == "Administrador") {
            echo '<form id="formCreate" method="post" enctype="multipart/form-data" onsubmit="return confirmAction(event);">';
            echo '<label for="name">Nombre </label>';
            echo '<input placeholder="nombre" type="text" name="ownerName" id="name"/><br><br>';

            echo '<label for="nickName">Nombre de Usuario <span class="required">*</span></label>';
            echo '<input placeholder="nombre de usuario" type="text" name="ownerNickName" id="nickName" autocomplete="username"/><br><br>';

            echo '<label for="surnames">Apellidos</label>';
            echo '<input placeholder="apellidos" type="text" name="ownerSurnames" id="surnames"/><br><br>';

            echo '<label for="idType">Tipo de Identificación</label>';
            echo '<select name="idType" id="idType">';
            echo '<option value="CR">Cédula Nacional de Costa Rica</option>';
            echo '<option value="foreign">Extranjero</option>';
            echo '</select><br><br>';

            echo '<label for="legalIdentification">Identificación Legal <span class="required">*</span></label>';
            echo '<input placeholder="identificacionLegal" type="text" name="ownerLegalIdentification" id="legalIdentification"/><br><br>';

            echo '<label for="phone">Teléfono</label>';
            echo '<input placeholder="telefono" type="text" name="ownerPhone" id="phone"/><br><br>';

            echo '<label for="email">Correo <span class="required">*</span></label>';
            echo '<input placeholder="correo" type="text" name="ownerEmail" id="email"/><br><br>';

            echo '<label for="direction">Dirección</label>';
            echo '<input placeholder="direccion" type="text" name="ownerDirection" id="direction"/><br><br>';

            echo '<label for="password">Contraseña <span class="required">*</span></label>';
            echo '<input placeholder="contraseña" type="password" name="password" id="password" autocomplete="new-password"/><br><br>';

            echo '<label for="confirmPassword">Confirmar Contraseña <span class="required">*</span></label>';
            echo '<input placeholder="confirmar contraseña" type="password" name="confirmPassword" id="confirmPassword" autocomplete="new-password"/><br><br>';

            echo '<input type="file" name="imagen" id="imagen"><br><br>';
            echo '<input type="submit" value="Crear" name="create" id="create"/>';
            echo '</form>';
        }
        ?>
    </section>

    <br>
    <section>
        <form id="formSearchOne" method="get">
            <label for="searchOne">Buscar por nombre</label>
            <input type="text" required placeholder="nombre del propietario" name="searchOne" id="searchOne">
            <input type="submit" value="Buscar" />
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
                    <th>Nombre de usuario</th>
                    <th>Dirección</th>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ownersFiltered = [];

                if (isset($_GET['searchOne'])) {
                    $searchTerm = $_GET['searchOne'];
                    $ownersFiltered = array_filter($owners, function ($owner) use ($searchTerm) {
                        return stripos($owner->getName(), $searchTerm) !== false;
                    });
                }
                if (count($ownersFiltered) > 0) {
                    $allowners = $ownersFiltered;
                }

                foreach ($owners as $current) {
                    echo '<form method="post" onsubmit="return confirmAction(event);">';

                    echo '<input type="hidden" name="ownerID" value="' . $current->getIdTBOwner() . '">';
                    echo '<input type="hidden" name="userID" value="' . $current->getId() . '">';
                    echo '<input type="hidden" name="password" value="' . $current->getPassword() . '">';
                    echo '<tr>';
                    echo '<td><input type="text" name="ownerName" value="' . $current->getName() . '"/></td>';
                    echo '<td><input type="text" name="ownerSurnames" value="' . $current->getSurnames() . '"/></td>';
                    $idType = ctype_digit($current->getLegalIdentification()) ? 'CR' : 'foreign';

                    // Genera el HTML con la selección basada en el tipo de identificación
                    echo '<td>
        <select name="idType">
            <option value="CR" ' . ($idType == 'CR' ? 'selected' : '') . '>Cédula Nacional de Costa Rica</option>
            <option value="foreign" ' . ($idType == 'foreign' ? 'selected' : '') . '>Extranjero</option>
        </select>
      </td>';
                    echo '<td><input type="text" name="ownerLegalIdentification" value="' . $current->getLegalIdentification() . '"/></td>';
                    echo '<td><input type="text" name="ownerPhone" value="' . $current->getPhone() . '"/></td>';
                    echo '<td><input type="text" name="ownerEmail" value="' . $current->getEmail() . '"/></td>';
                    echo '<td><input type="text" name="ownerNickName" value="' . $current->getNickName() . '"/></td>';
                    echo '<td><input type="text" name="ownerDirection" value="' . $current->getDirectionTBOwner() . '"/></td>';

                    $photoUrl = $current->getPhotoURLTBOwner();
                    echo '<td><img src="../images/' . $photoUrl . '" alt="Foto" width="75" height="75" /></td>';

                    echo '<td>';
                    echo '<input type="submit" value="Actualizar" name="update" class="update-button" />';
                    echo '<input type="submit" value="Eliminar" name="delete" class="delete-button" />';
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
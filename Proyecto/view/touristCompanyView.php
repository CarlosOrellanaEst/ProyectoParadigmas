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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>CRUD Empresa turística</title>
    <style>
        .required {
            color: red;
        }
    </style>
    <?php
        include_once '../business/touristCompanyBusiness.php';
        include_once '../business/touristCompanyTypeBusiness.php';
        include_once '../business/ownerBusiness.php';

        $touristCompanyTypeBusiness = new TouristCompanyTypeBusiness();
        $touristCompanyTypes = $touristCompanyTypeBusiness->getAll();
        $imageBasePath = '../images/';
    ?>

</head>

<body>
    <?php
        if ($userLogged->getUserType() == "Propietario") {
            echo '<a href="ownerViewSession.php">← Volver al inicio</a>';
        } else if ($userLogged->getUserType() == "Administrador") {
            echo '<a href="adminView.php">← Volver al inicio</a>';
        }
    ?>
    <header>
        <h1>CRUD Empresa turística</h1>
        <p><span class="required">*</span> Campos requeridos</p>
    </header>
    

    <section id="create">
        <form id="formCreate" method="post" action="../business/touristCompanyAction.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="legalName">Nombre legal:</label>
                <input placeholder="Nombre legal" type="text" name="legalName" id="legalName" />
            </div>

            <div class="form-group">
                <label for="magicName">Nombre mágico:</label>
                <input placeholder="Nombre mágico" type="text" name="magicName" id="magicName" />
            </div>

            <div class="form-group">
                <label for="ownerId">Dueño: <span id="ownerError" style="color:red; display:none;">*campo obligatorio</span></label>
                <select name="ownerId" id="ownerId" required>
                    <option value="0">Ninguno</option>
                    <?php foreach ($owners as $owner): ?>
                        <option value="<?php echo htmlspecialchars($owner->getIdTBOwner()); ?>">
                            <?php echo htmlspecialchars($owner->getName() . ' ' . $owner->getSurnames()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="companyType">Tipo de empresa:</label>
                <select name="companyType" id="companyType">
                    <option value="0">Ninguno</option>
                    <?php foreach ($touristCompanyTypes as $touristCompanyType): ?>
                        <option value="<?php echo htmlspecialchars($touristCompanyType->getId()); ?>">
                            <?php echo htmlspecialchars($touristCompanyType->getName()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="imagenes">Imágenes: <span id="imagenesError" style="color:red; display:none;">*campo obligatorio</span></label>
                <input type="file" name="imagenes[]" id="imagenes" multiple />
            </div>

            <input type="hidden" id="status" name="status" value="1">

            <div class="form-group">
                <input type="submit" value="Crear" name="create" id="create" />
            </div>
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
                    <th>Imágenes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $touristCompanyBusiness = new TouristCompanyBusiness();
                $ownerBusiness = new OwnerBusiness();
                $touristCompanyTypeBusiness = new TouristCompanyTypeBusiness();

                if ($userLogged->getUserType() == "Propietario") {
                    $all = $touristCompanyBusiness->getAllByOwnerID($userLogged->getIdTBOwner());
                } else if ($userLogged->getUserType() == "Administrador") {
                    $all = $touristCompanyBusiness->getAll();
                }

                $alltouristCompanyTypes = $touristCompanyTypeBusiness->getAll();
                $touristCompanyFiltered = [];

                if (isset($_GET['searchOne'])) {
                    $searchTerm = $_GET['searchOne'];
                    $touristCompanyFiltered = array_filter($all, function ($touristCompany) use ($searchTerm) {
                        return stripos($touristCompany->getTbtouristcompanylegalname(), $searchTerm) !== false;
                    });
                }
                if (count($touristCompanyFiltered) > 0) {
                    $all = $touristCompanyFiltered;
                }

                if (count($all) > 0) {
                    foreach ($all as $current) {
                        $assignedCompanyType = $touristCompanyTypeBusiness->getById($current->getTbtouristcompanycompanyType());
                        $assignedOwner = $ownerBusiness->getTBOwner($current->getTbtouristcompanyowner());
                        echo '<tr>';
                        echo '<form method="post" action="../business/touristCompanyAction.php" onsubmit="return confirmAction(event);">';
                        echo '<td><input type="text" name="legalName" value="' . htmlspecialchars($current->getTbtouristcompanylegalname()) . '" ></td>';
                        echo '<td><input type="text" name="magicName" value="' . htmlspecialchars($current->getTbtouristcompanymagicname()) . '" ></td>';
                        echo '<td>';
                        echo '<select name="ownerId" required>';
                        foreach ($owners as $owner) {
                            echo '<option value="' . htmlspecialchars($owner->getIdTBOwner()) . '"';
                            if ($owner->getIdTBOwner() == $current->getTbtouristcompanyowner()) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($owner->getName() . ' ' . $owner->getSurnames()) . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '<td>';
                        echo '<select name="companyType" required>';
                        foreach ($alltouristCompanyTypes as $touristCompanyType) {
                            echo '<option value="' . htmlspecialchars($touristCompanyType->getId()) . '"';
                            if ($touristCompanyType->getId() == $current->getTbtouristcompanycompanyType()) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($touristCompanyType->getName()) . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '<td>';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($current->getTbtouristcompanyid()) . '">';
                        echo '<input type="hidden" name="status" value="1">';
                        
                        $images = $current->getTbtouristcompanyurl(); 
                        echo '<td>';
                        foreach ($images as $index => $image) {
                            if (!empty($image)) {
                                echo '<img src="' . $imageBasePath . trim($image) . '" alt="Foto" width="50" height="50" />';
                            }
                        }
                        echo '</td>';

                        
                        echo '<td>';
                        echo '<select name="imageIndex">';
                        foreach ($images as $index => $image) {
                            echo '<option value="' . $index . '">Imagen ' . ($index + 1) . '</option>';
                        }
                        echo '</select>';
                        
                        echo '</td>';

                        
                        echo '<form method="post" action="../business/touristCompanyAction.php">';
                        echo '<input type="hidden" name="photoID" value="' . $current->getTbtouristcompanyid() . '">';

                       

                        echo '<td>';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($current->getTbtouristcompanyid()) . '">';
                        echo '<input type="submit" value="Actualizar" name="update" />';
                        echo '<input type="submit" value="Eliminar" name="delete"/>';
                        echo '<input type="submit" value="Eliminar Imagen" name="deleteImage">';
                        echo '</td>';
                        echo '</form>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6">No se encontraron resultados</td></tr>';
                }
                ?>
            </tbody>
        </table>

    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAlertBasedOnURL();
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../resources/touristCompanyView.js"></script>
</body>

</html>
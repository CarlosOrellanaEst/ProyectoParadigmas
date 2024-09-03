<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Empresa Turística</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <a href="../index.html">← Volver al inicio</a>
    
    <?php 
    include '../business/touristCompanyBusiness.php'; 
    include '../business/ownerBusiness.php'; 
    include '../business/touristCompanyTypeBusiness.php'; 

    // Obtener datos de dueños y tipos de empresas turísticas
    $ownerBusiness = new OwnerBusiness();
    $owners = $ownerBusiness->getAllTBOwner();
    $touristCompanyTypeBusiness = new TouristCompanyTypeBusiness();
    $touristCompanyTypes = $touristCompanyTypeBusiness->getAll();
    $imageBasePath = '../images/';
    ?>

    <script src="../resources/touristCompanyView.js"></script>
</head>

<body>
    <header>
        <h1>CRUD Empresa Turística</h1>
    </header>

    <!-- Formulario para crear una nueva empresa turística -->
    <form method="post" action="../business/touristCompanyAction.php" enctype="multipart/form-data">
        <label for="imagenes">Selecciona las imágenes (máximo 5):</label>
        <input type="file" name="imagenes[]" accept="image/*" multiple>
        
        <label for="legalName">Nombre legal: </label>
        <input placeholder="Nombre legal" type="text" name="legalName" id="legalName" required>
        
        <label for="magicName">Nombre mágico: </label>
        <input placeholder="Nombre mágico" type="text" name="magicName" id="magicName" required>
        
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
        
        <input type="hidden" name="status" value="1">
        <input type="submit" value="Crear" name="create" id="create">
    </form>

    <br>

    <!-- Sección para buscar y mostrar empresas turísticas -->
    <section>
        <form id="formSearchOne" method="get">
            <label for="searchOne">Buscar por nombre: </label>
            <input type="text" placeholder="Nombre" name="searchOne" id="searchOne">
            <input type="submit" value="Buscar">
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
                    <th>Fotos</th>
                    <th>Actualizar Imagen</th>
                    <th>Eliminar Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Instancias de negocio para manejar las operaciones
                $touristCompanyBusiness = new TouristCompanyBusiness();
                $all = $touristCompanyBusiness->getAll();
                $touristCompanyFiltered = [];

                // Filtrar resultados si hay una búsqueda
                if (isset($_GET['searchOne'])) {
                    $searchTerm = $_GET['searchOne'];
                    $touristCompanyFiltered = array_filter($all, function($touristCompany) use ($searchTerm) {
                        return stripos($touristCompany->getTbtouristcompanylegalname(), $searchTerm) !== false;
                    });
                }

                // Mostrar resultados filtrados si existen
                if (count($touristCompanyFiltered) > 0) {
                    $all = $touristCompanyFiltered;
                }

                // Mostrar cada empresa turística en una fila
                if (count($all) > 0) {
                    foreach ($all as $current) {
                        $assignedCompanyType = $touristCompanyTypeBusiness->getById($current->getTbtouristcompanycompanyType());
                        $assignedOwner = $ownerBusiness->getTBOwner($current->getTbtouristcompanyowner());
                        
                        echo '<form method="post" action="../business/touristCompanyAction.php" onsubmit="return confirmAction(event);">';
                        echo '<tr>';

                        echo '<td><input type="text" name="legalName" value="'. htmlspecialchars($current->getTbtouristcompanylegalname()) .'" required></td>';
                        echo '<td><input type="text" name="magicName" value="' . htmlspecialchars($current->getTbtouristcompanymagicname()) . '" required></td>';
                        
                        // Dropdown para seleccionar el dueño
                        echo '<td>';
                        echo '<select name="ownerId" required>';
                        foreach ($owners as $owner) {
                            echo '<option value="' . htmlspecialchars($owner->getIdTBOwner()) . '"';
                            if ($owner->getIdTBOwner() == $current->getTbtouristcompanyowner()) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($owner->getFullName()) . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';
                        
                        // Dropdown para seleccionar el tipo de empresa
                        echo '<td>';
                        echo '<select name="companyType" required>';
                        foreach ($touristCompanyTypes as $touristCompanyType) {
                            echo '<option value="' . htmlspecialchars($touristCompanyType->getId()) . '"';
                            if ($touristCompanyType->getId() == $current->getTbtouristcompanycompanyType()) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($touristCompanyType->getName()) . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';
                        
                        // Mostrar fotos asociadas a la empresa
                        echo '<td>';
                        $photos = $current->getPhotos();
                        if (count($photos) > 0) {
                            echo '<div style="white-space: nowrap;">'; // Contenedor en línea para las imágenes
                            foreach ($photos as $photo) {
                                // Obtener la URL de la foto
                                $photoUrl = $photo->getUrlTBPhoto();
                                // Dividir la cadena de URLs en un array usando comas como delimitador
                                $photoUrlsArray = explode(',', $photoUrl);
                                // Mostrar cada URL de la foto
                                foreach ($photoUrlsArray as $url) {
                                    // Concatenar la ruta base con la URL de la imagen y eliminar espacios alrededor de la URL
                                    $imageUrl = $imageBasePath . trim(htmlspecialchars($url));
                                    echo '<img src="' . $imageUrl . '" alt="Foto" style="width:70px;height:70px;display:inline-block;">';
                                }
                            }
                            echo '</div>';
                        } else {
                            echo 'No hay fotos';
                        }
                        echo '</td>';
                        echo '<td>';
                        // Formulario para actualizar una imagen
                        echo '<form method="post" action="../business/PhotoAction.php" enctype="multipart/form-data">';
                        echo '<input type="hidden" name="photoID" value="' . htmlspecialchars($current->getTbphotoid()) . '">';
                        // Convertir $photoUrl en un array
                        $photoUrlsArray = explode(',', $photoUrl);
                        echo '<input type="hidden" name="existingUrls" value="' . htmlspecialchars(implode(',', $photoUrlsArray)) . '">';
                        echo '<select name="imageIndex">';
                        foreach ($photoUrlsArray as $index => $photo) {
                            if (trim($photo) !== '') {
                                echo '<option value="' . $index . '">Imagen ' . ($index + 1) . '</option>';
                            }
                        }
                        echo '</select>';
                        echo '<input type="file" name="newImage" accept="image/*">';
                        echo '<input type="submit" value="Actualizar Imagen" name="update">';
                        echo '</form><br>'; // Línea de separación entre formularios
                        echo '</td>';
                        echo '<td>';
                        // Formulario para eliminar una imagen específica
                        echo '<form method="post" action="../business/PhotoAction.php">';
                        $photoUrlsArray = explode(',', $photoUrl);
                        echo '<input type="hidden" name="photoID" value="' . htmlspecialchars($current->getTbphotoid()) . '">';
                        echo '<select name="imageIndex">';
                        foreach ($photoUrlsArray as $index => $photo) {
                            if (trim($photo) !== '') {
                                echo '<option value="' . $index . '">Imagen ' . ($index + 1) . '</option>';
                            }
                        }
                        echo '</select>';
                        echo '<input type="submit" value="Eliminar Imagen" name="delete">';
                        echo '</form>';
                        echo '</td>';
                        echo '<td>';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($current->getTbtouristcompanyid()) . '">';
                        echo '<input type="submit" value="Actualizar">';
                        echo '<input type="submit" value="Eliminar" name="delete" onclick="return confirm(\'¿Estás seguro de que deseas eliminar esta empresa turística?\');">';
                        echo '</td>';
                        echo '</tr>';
                        echo '</form>';
                    }
                } else {
                    echo '<tr><td colspan="8">No hay empresas turísticas disponibles.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>

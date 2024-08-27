<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CRUD Fotos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php include '../business/PhotoBusiness.php'; ?>
</head>
<body>
    <header> 
        <h1>CRUD Fotos</h1>
    </header>

    <!-- Formulario para crear nuevas fotos -->
    <section id="formCreate">
        <form method="post" action="../business/PhotoAction.php" enctype="multipart/form-data">
            <label for="imagenes">Selecciona las imágenes (máximo 5):</label>
            <input type="file" name="imagenes[]" accept="image/*" multiple>
            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>

    <br><br>

    <!-- Listado de fotos -->
    <section>
        <table>
            <thead>
                <tr>
                    <th>Fotos</th>
                    <th>Actualizar Imagen</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $photoBusiness = new PhotoBusiness();
                $allphotos = $photoBusiness->getAllTBPhotos();

                foreach ($allphotos as $current) {
                    $photoUrls = explode(',', $current->getUrlTBPhoto());
                    echo '<tr>';
                    echo '<td>';
                    foreach ($photoUrls as $index => $photo) {
                        echo '<img src="../images/' . trim($photo) . '" alt="Foto" width="100" height="100" />';
                    }
                    echo '</td>';
                    echo '<td>';
                    // Formulario para actualizar una imagen
                    echo '<form method="post" action="../business/PhotoAction.php" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="photoID" value="' . $current->getIdTBPhoto() . '">';
                    echo '<input type="hidden" name="existingUrls" value="' . htmlspecialchars(implode(',', $photoUrls)) . '">';
                    echo '<select name="imageIndex">';
                    foreach ($photoUrls as $index => $photo) {
                        echo '<option value="' . $index . '">Imagen ' . ($index + 1) . '</option>';
                    }
                    echo '</select>';
                    echo '<input type="file" name="newImage" accept="image/*">';
                    echo '<input type="submit" value="Actualizar Imagen" name="update">';
                    echo '</form><br>'; // Línea de separación entre formularios
                    echo '</td>';
                    echo '<td>';
                    // Formulario para eliminar todas las imágenes de un registro
                    echo '<form method="post" action="../business/PhotoAction.php">';
                    echo '<input type="hidden" name="photoID" value="' . $current->getIdTBPhoto() . '">';
                    echo '<input type="submit" value="Eliminar" name="delete">';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>

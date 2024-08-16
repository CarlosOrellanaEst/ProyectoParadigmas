<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CRUD Fotos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php
    include '../business/photoBusiness.php';
    ?>
</head>
<body>
    <header> 
        <h1>CRUD Fotos</h1>
    </header>

    <!-- Formulario para crear una nueva foto -->
    <section id="formCreate">
        <form method="post" action="../business/photoAction.php" enctype="multipart/form-data">
            <input type="file" name="imagen" required>
            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>

    <br><br>

    <!-- Listado de fotos -->
    <section>
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $photoBusiness = new photoBusiness();
                $allphotos = $photoBusiness->getAllTBPhotos();
                
                foreach ($allphotos as $current) {
                    echo '<form method="post" action="../business/photoAction.php" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="photoID" value="' . $current->getIdTBPhoto() . '">';
                    echo '<tr>';
                    echo '<td><img src="../images/' . $current->getUrlTBPhoto() . '" alt="Foto" width="100" height="100" name="photoURL"/></td>';
                    echo '<td>';
                        // Input para seleccionar la nueva imagen
                        echo '<input type="file" name="newImage" accept="image/*">';
                        // Botones de acción
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <a href="../index.html">← Volver al inicio</a>
    <title>CRUD Fotos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php include '../business/PhotoBusiness.php'; ?>
</head>
<body>
    <header> 
        <h1>CRUD Fotos</h1>
    </header>

    <!-- Formulario para crear nuevas fotos -->
<!--     <section id="formCreate">
        <form method="post" action="../business/PhotoAction.php" enctype="multipart/form-data">
            <label for="imagenes">Selecciona las imágenes (máximo 5):</label>
            <input type="file" name="imagenes[]" accept="image/*" multiple>
            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section> -->
    <?php include '../componentes/View/formPhoto.php'; ?>

    <br><br>

    <!-- Listado de fotos -->
    <section>
        <?php
        $photoBusiness = new PhotoBusiness();
        $allphotos = $photoBusiness->getAllTBPhotos();
        ?>

        <!-- Verificar si hay fotos -->
        <?php if (empty($allphotos)): ?>
            <p>No hay imágenes disponibles.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Fotos</th>
                        <th>Actualizar Imagen</th>
                        <th>Eliminar Imagen</th>
                        <th>Eliminar Todo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allphotos as $current): ?>
                        <?php
                        $photoUrls = explode(',', $current->getUrlTBPhoto());
                        $hasActivePhotos = false;
                        foreach ($photoUrls as $photo) {
                            if (trim($photo) !== '5' && !empty(trim($photo))) {
                                $hasActivePhotos = true;
                                break;
                            }
                        }
                        ?>
                        <tr>
                            <td>
                                <?php if ($hasActivePhotos): ?>
                                    <?php foreach ($photoUrls as $index => $photo): ?>
                                        <?php if (trim($photo) !== '5' && !empty(trim($photo))): ?>
                                            <img src="../images/<?php echo trim($photo); ?>" alt="Foto" width="100" height="100" />
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    Vacío
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Formulario para actualizar una imagen -->
                                <form method="post" action="../business/PhotoAction.php" enctype="multipart/form-data">
                                    <input type="hidden" name="photoID" value="<?php echo $current->getIdTBPhoto(); ?>">
                                    <select name="imageIndex">
                                        <?php foreach ($photoUrls as $index => $photo): ?>
                                            <?php if (trim($photo) !== '5' && !empty(trim($photo))): ?>
                                                <option value="<?php echo $index; ?>">Imagen <?php echo ($index + 1); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="file" name="newImage" accept="image/*">
                                    <input type="submit" value="Actualizar Imagen" name="update">
                                </form>
                            </td>
                            <td>
                                <!-- Formulario para eliminar una imagen específica -->
                                <form method="post" action="../business/PhotoAction.php">
                                    <input type="hidden" name="photoID" value="<?php echo $current->getIdTBPhoto(); ?>">
                                    <select name="imageIndex">
                                        <?php foreach ($photoUrls as $index => $photo): ?>
                                            <?php if (trim($photo) !== '5' && !empty(trim($photo))): ?>
                                                <option value="<?php echo $index; ?>">Imagen <?php echo ($index + 1); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="submit" value="Eliminar Imagen" name="delete">
                                </form>
                            </td>
                            <td>
                                <!-- Formulario para eliminar todas las imágenes de un registro -->
                                <form method="post" action="../business/PhotoAction.php">
                                    <input type="hidden" name="photoID" value="<?php echo $current->getIdTBPhoto(); ?>">
                                    <input type="submit" value="Eliminar Todo" name="deleteAll">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</body>
</html>


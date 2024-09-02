<?php 
   echo ' <section id="formCreate">
        <form method="post" action="../business/PhotoAction.php" enctype="multipart/form-data">
            <label for="imagenes">Selecciona las imágenes (máximo 5):</label>
            <input type="file" name="imagenes[]" accept="image/*" multiple>
            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>
    ';
?>
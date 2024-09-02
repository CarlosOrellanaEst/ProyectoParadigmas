<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <a href="../index.html">← Volver al inicio</a>
    <title>CRUD Servicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        td, th {
            border-right: 1px solid;
        }
    </style>

<!--     <script src="../resources/serviceView.js"></script>
    <script src="../resources/AJAXCreateService.js"></script> -->
</head>
<body>
    <header> 
        <h1>CRUD Servicios</h1>
    </header>
    <section>
        <form method="post"  id="formCreate">
            <label for="description">Nombre del servicio</label>
            <input placeholder="descripción" type="text" name="rollDescription" id="description"/>
            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>
        <!-- Botón para abrir el modal -->
        <button id="btnOpenModal">Agregar imágenes</button>

        <!-- Modal con el formulario de subir imágenes -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Subir Imágenes</h2>
                <form method="post" action="../business/PhotoAction.php" enctype="multipart/form-data">
                    <label for="imagenes">Selecciona las imágenes (máximo 5):</label>
                    <input type="file" name="imagenes[]" accept="image/*" multiple>
                    <input type="submit" value="Crear" name="create" id="create" />
                </form>
            </div>
        </div>
    <br><br>
    <section>

<!--         <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $serviceBusiness = new serviceBusiness();
                    $allRolls = $rollBusiness->getAllTBRolls();
                    $rollsFiltered = [];

                    // Filtrar los resultados si se ha realizado una búsqueda
                    if (isset($_GET['searchOne'])) {
                        $searchTerm = $_GET['searchOne'];
                        $rollsFiltered  = array_filter($allRolls, function($roll) use ($searchTerm) {
                            return stripos($roll->getNameTBRoll(), $searchTerm) !== false;
                        });
                    }
                    if (count($rollsFiltered) > 0) {
                        $allRolls = $rollsFiltered;
                    }

                    foreach ($allRolls as $current) {
                        echo '<form method="post" action="../business/rollAction.php" onsubmit="return confirmDelete(event);">';
                        echo '<input type="hidden" name="rollID" value="' . $current->getIdTBRoll() . '">';
                        echo '<tr>';
                            echo '<td><input type="text" name="rollName" value="' . $current->getNameTBRoll() . '"/></td>';
                            echo '<td><input type="text" name="rollDescription" value="' . $current->getDescriptionTBRoll() . '"/></td>';
                            echo '<td>';
                                echo '<input type="submit" value="Actualizar" name="update"/>';
                                echo '<input type="submit" value="Eliminar" name="delete"/>';
                            echo '</td>';
                        echo '</tr>';
                        echo '</form>';
                    }
                ?>
            </tbody>
        </table> -->
    </section>
    <script>
        // JavaScript para manejar el modal
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("btnOpenModal");
        var span = document.getElementsByClassName("close")[0];

        // Cuando el usuario hace clic en el botón, abre el modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // Cuando el usuario hace clic en la 'x', cierra el modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Cuando el usuario hace clic fuera del modal, lo cierra
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
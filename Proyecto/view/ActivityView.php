<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Actividad</title>
    <script>
        function agregarCampo() {
            const container = document.getElementById('atributos-container');
            const nuevoCampo = document.createElement('div');
            nuevoCampo.classList.add('atributo-pair');
            nuevoCampo.innerHTML = `
                <input type="text" name="atributos[]" placeholder="Nombre del Atributo" required>
                <input type="text" name="valores[]" placeholder="Valor del Atributo" required>
            `;
            container.appendChild(nuevoCampo);
        }
    </script>
</head>
<body>

    <h2>Agregar Nueva Actividad</h2>
    <form action="agregar_actividad.php" method="post">
        <label for="nombre_actividad">Nombre de la Actividad:</label><br>
        <input type="text" id="nombre_actividad" name="nombre_actividad" required><br><br>

        <div id="atributos-container">
            <div class="atributo-pair">
                <input type="text" name="atributos[]" placeholder="Nombre del Atributo" required>
                <input type="text" name="valores[]" placeholder="Valor del Atributo" required>
            </div>
        </div>

        <button type="button" onclick="agregarCampo()">Agregar Otro Atributo</button><br><br>

        <input type="submit" value="Agregar Actividad">
    </form>

</body>
</html>

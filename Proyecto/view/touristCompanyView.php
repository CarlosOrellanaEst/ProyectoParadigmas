<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Compañía Turística</title>
</head>
<body>
    <h2>Registrar Compañía Turística</h2>
    <form action="touristCompanyAction.php" method="post">
        <input type="hidden" name="action" value="create">

        <label for="legalName">Nombre Legal:</label><br>
        <input type="text" id="legalName" name="legalName" required><br><br>

        <label for="magicName">Nombre Mágico:</label><br>
        <input type="text" id="magicName" name="magicName" required><br><br>

        <label for="ownerId">Propietario:</label><br>
        <select id="ownerId" name="ownerId" required>
            <!-- Opciones deben cargarse dinámicamente desde la base de datos -->
            <option value="1">Propietario 1</option>
            <option value="2">Propietario 2</option>
        </select><br><br>

        <label for="companyTypeId">Tipo de Compañía:</label><br>
        <select id="companyTypeId" name="companyTypeId" required>
            <!-- Opciones deben cargarse dinámicamente desde la base de datos -->
            <option value="1">Tipo de Compañía 1</option>
            <option value="2">Tipo de Compañía 2</option>
        </select><br><br>

        <button type="submit">Registrar Compañía</button>
    </form>
</body>
</html>

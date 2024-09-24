
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Turista</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        td, th {
            border-right: 1px solid;
        }
        .required {
            color: red;
        }
    </style>
    <script src="../resources/touristAJAX.js"></script>
</head>
<body>
    <a href="../index.php">← Volver al inicio</a> 
    <header> 
        <h1>Registrar turista</h1>
        <p><span class="required">*</span> Campos requeridos</p>
    </header>
    
    <section>
        <form method="post" id="formCreate">
            <label for="touristName">Nombre </label>
            <input placeholder="Ingrese su nombre" type="text" name="touristName" id="touristName"/><br><br>
            
            <label for="touristSurnames">Apellidos </label>
            <input placeholder="Ingrese sus apellidos" type="text" name="touristSurnames" id="touristSurnames"/><br><br>

            <label for="idType">Tipo de Identificación</label>
            <select name="idType" id="idType">
                <option value="CR">Cédula Nacional de Costa Rica</option>
                <option value="foreign">Extranjero</option>
            </select><br><br>

            <label for="touristLegalIdentification">Identificación Legal <span class="required">*</label>
            <input placeholder="Ingrese su identificación legal" type="text" name="touristLegalIdentification" id="touristLegalIdentification"/><br><br>

            <label for="touristPhone">Teléfono </label>
            <input placeholder="Ingrese su teléfono" type="text" name="touristPhone" id="touristPhone"/><br><br>

            <label for="touristEmail">Correo <span class="required">*</label>
            <input placeholder="Ingrese su correo" type="text" name="touristEmail" id="touristEmail"/><br><br>

            <label for="touristNickName">Usuario <span class="required">*</label>
            <input placeholder="Ingrese su nombre de usuario" type="text" name="touristNickName" id="touristNickName"/><br><br>

            <label for="touristPassword">Contraseña <span class="required">*</label>
            <input placeholder="Ingrese su contraseña" type="password" name="touristPassword" id="touristPassword"/><br><br>

            <label for="confirmTouristPassword">Confirmar contraseña <span class="required">*</label>
            <input placeholder="Ingrese su contraseña nuevamente" type="password" name="confirmTouristPassword" id="confirmTouristPassword"/><br><br>

            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>
    <br>
    
</body>
</html>

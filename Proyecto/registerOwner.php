<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD Propietarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        td, th {
            border-right: 1px solid;
        }
        .required {
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php">← Volver al inicio</a>
        <h1>CRUD Propietarios</h1>
        <p><span class="required">*</span> Campos requeridos</p>
    </header>
    
    <form id="ownerForm" method="post" enctype="multipart/form-data" action="./business/ownerAction.php">
    <label for="name">Nombre <span class="required">*</span> </label>
    <input placeholder="nombre" type="text" name="ownerName" id="name" autocomplete="given-name"/><br><br>

    <label for="surnames">Apellidos</label>
    <input placeholder="apellidos" type="text" name="ownerSurnames" id="surnames" autocomplete="family-name"/><br><br>

    <label for="idType">Tipo de Identificación</label>
    <select name="idType" id="idType" autocomplete="country-name">
        <option value="CR">Cédula Nacional de Costa Rica</option>
        <option value="foreign">Extranjero</option>
    </select><br><br>

    <label for="legalIdentification">Identificación Legal <span class="required">*</span></label>
    <input placeholder="identificación legal" type="text" name="ownerLegalIdentification" id="legalIdentification" autocomplete="off"/><br><br>

    <label for="phone">Teléfono</label>
    <input placeholder="teléfono" type="text" name="ownerPhone" id="phone" autocomplete="tel"/><br><br>

    <label for="email">Correo <span class="required">*</span></label>
    <input placeholder="correo" type="text" name="ownerEmail" id="email" autocomplete="email"/><br><br>

    <label for="direction">Dirección</label>
    <input placeholder="dirección" type="text" name="ownerDirection" id="direction" autocomplete="street-address"/><br><br>

    <label for="password">Contraseña <span class="required">*</span></label>
    <input placeholder="contraseña" type="password" name="password" id="password" autocomplete="new-password"/><br><br>

    <label for="confirmPassword">Confirmar Contraseña <span class="required">*</span></label>
    <input placeholder="confirmar contraseña" type="password" id="confirmPassword" autocomplete="new-password" required/><br><br>


    <input type="file" name="imagen" id="imagen"><br><br>

    <input type="submit" value="Crear" name="create" id="create"/>
</form>

    <script src="./resources/ownerView.js"></script>
    <script src="./resources/AJAXOwner.js"></script>
    <script src="./resources/register.js"></script> 
</body>
</html>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cuenta de banco</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        td, th {
            border-right: 1px solid;
        }
    </style>
    
    <?php
    include '../business/bankAccountBusiness.php';
    include '../business/OwnerBusiness.php';
    $ownerBusiness = new OwnerBusiness();
    $owners = $ownerBusiness->getAllTBOwners();
    ?>
    <script src="../resources/bankAccountView.js"></script>
</head>
<body>
    <header> 
        <h1>CRUD Cuenta de banco</h1>
    </header>
    <section id="formCreate">
        <form method="post" action="../business/bankAccountAction.php">
            <label for="ownerId">ID del Propietario</label>
            <select name="ownerId" id="ownerId" required>
                <!-- Opciones se llenarán aquí con PHP -->
                <?php foreach ($owners as $owner): ?>
                    <option value="<?php echo htmlspecialchars($owner->getIdTBOwner()); ?>">
                        <?php echo htmlspecialchars($owner->getName() . ' ' . $owner->getSurnames()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label for="accountNumber">Número de Cuenta</label>
            <input required placeholder="Número de cuenta" type="text" name="accountNumber" id="accountNumber"/>
            
            <label for="bank">Nombre del Banco</label>
            <input required placeholder="Nombre del banco" type="text" name="bank" id="bank"/>
            
            <label for="status">Estado</label>
            <select name="status" id="status" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
            
            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>

    <br><br>
    
</body>
</html>
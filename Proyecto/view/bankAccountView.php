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
        include '../business/ownerBusiness.php';
        $ownerBusiness = new OwnerBusiness();
        if (Utils::$userLogged->getUserType() == "Administrador") {
            $owners = $ownerBusiness->getAllTBOwners();
        } else if (Utils::$userLogged->getUserType() == "Propietario") {
            $owners = $ownerBusiness->getTBOwner(Utils::$userLogged->getId); 
        }
    ?>
    <script src="../resources/bankAccountView.js"></script>
    <script src="../resources/bankAccountAJAX.js"></script>
</head>
<body>
    <a href="../index.html">← Volver al inicio</a>
    <header> 
        <h1>CRUD Forma de Pago</h1>
    </header>
    <section>
        <form method="post"  id="formCreate" >
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
            <input placeholder="Número de cuenta" type="text" name="accountNumber" id="accountNumber"/>
            <label for="bank">Nombre del Banco</label>
            <input  placeholder="Nombre del banco" type="text" name="bank" id="bank"/>
            <label for="status">Estado</label>
            <select name="status" id="status" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>
    <br>
    <section>
        <form id="formSearchOne" method="get">
            <label for="searchOne">Buscar por nombre del banco</label>
            <input type="text" required placeholder="nombre del banco" name="searchOne" id="searchOne">
            <input type="submit" value="Buscar"/>
        </form>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Id propietario</th>
                    <th>Nombre del banco</th>
                    <th>Número de cuenta</th>
                    <th>Estado</th>
                    <th>Acciónes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $bankAccountBusiness = new bankAccountBusiness();
                    $all = $bankAccountBusiness->getAllTBBankAccount();
                    $bankAccountFiltered = [];

                    // Filtrar los resultados si se ha realizado una búsqueda
                    if (isset($_GET['searchOne'])) {
                        $searchTerm = $_GET['searchOne'];
                        $bankAccountFiltered  = array_filter($all, function($bankAccount) use ($searchTerm) {
                            return stripos($bankAccount->getBank(), $searchTerm) !== false;
                        });
                    }
                    if (count($bankAccountFiltered) > 0) {
                        $all = $bankAccountFiltered;
                    }

                    if (count($all) > 0) {
                        foreach ($all as $current) {
                            echo '<form method="post" action="../business/bankAccountAction.php" onsubmit="return confirmAction(event);">';
                            echo '<input type="hidden" name="tbbankaccountid" value="' . $current->getTbBankAccountId() . '">';
                            echo '<tr>';
                                echo '<td><input type="text" name="OwnerId" value="' . $current->getOwnerId() . '"/></td>';
                                echo '<td><input type="text" name="BankName" value="' . $current->getBank() . '"/></td>';
                                echo '<td><input type="text" name="AccountNumber" value="' . $current->getAccountNumber() . '"/></td>';
                                echo '<td><select name="Status" id="Status">
                                                <option value="1"'.(($current->getStatus() == 1)? ' selected': '').'>Activo</option>
                                                <option value="0"'.(($current->getStatus() == 0)? ' selected': '').'>Inactivo</option>
                                            </select></td>';
                                echo '<td>';
                                    echo '<input type="submit" value="Actualizar" name="update"/>';
                                    echo '<input type="submit" value="Eliminar" name="delete"/>';
                                echo '</td>';
                            echo '</tr>';
                            echo '</form>';
                        }
                    } else {
                        echo '<tr>';
                            echo '<td colspan="5" style="text-align: center;">No hay registros</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tipo de pago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        td, th {
            border-right: 1px solid;
        }
    </style>
    
    <?php
        include '../business/paymentTypeBusiness.php';
        include '../business/OwnerBusiness.php';
        $ownerBusiness = new OwnerBusiness();
        $owners = $ownerBusiness->getAllTBOwners();
    ?>
    <script src="../resources/paymentTypeView.js"></script>
    <script src="../resources/paymentTypeAJAX.js"></script>
</head>
<body>
    <a href="../index.html">← Volver al inicio</a>
    <header> 
        <h1>CRUD Tipo de pago</h1>
    </header>
    <section>
        <form method="post"  id="formCreate" >
            <label for="ownerId">ID del Propietario</label>
            <select name="ownerId" id="ownerId" required>
                <!-- Opciones se llenarán aquí con PHP -->
                <?php foreach ($owners as $owner): ?>
                    <option value="<?php echo htmlspecialchars($owner->getOwnerId()); ?>">
                        <?php echo htmlspecialchars($owner->getName() . ' ' . $owner->getSurnames()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="accountNumber">Número de Cuenta</label>
            <input placeholder="Número de cuenta" type="text" name="accountNumber" id="accountNumber"/>
            <label for="sinpeNumber">Número de SINPE</label>
            <input  placeholder="Número de SINPE" type="text" name="sinpeNumber" id="sinpeNumber"/>
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
                    <th>Número de SINPE</th>
                    <th>Número de cuenta</th>
                    <th>Estado</th>
                    <th>Acciónes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $paymentTypeBusiness = new paymentTypeBusiness();
                    $all = $paymentTypeBusiness->getAll();
                    $bankAccountFiltered = [];

                    // Filtrar los resultados si se ha realizado una búsqueda
                    if (isset($_GET['searchOne'])) {
                        $searchTerm = $_GET['searchOne'];
                        $bankAccountFiltered  = array_filter($all, function($bankAccount) use ($searchTerm) {
                            return stripos($bankAccount->getSinpeNumber(), $searchTerm) !== false;
                        });
                    }
                    if (count($bankAccountFiltered) > 0) {
                        $all = $bankAccountFiltered;
                    }

                    if (count($all) > 0) {
                        foreach ($all as $current) {
                            echo '<form method="post" action="../business/paymentTypeAction.php" onsubmit="return confirmAction(event);">';
                            echo '<input type="hidden" name="tbpaymentTypeid" value="' . $current->getTbPaymentTypeId() . '">';
                            echo '<tr>';
                                echo '<td><input type="text" name="OwnerId" value="' . $current->getOwnerId() . '"/></td>';
                                echo '<td><input type="text" name="SinpeNumber" value="' . $current->getSinpeNumber() . '"/></td>';
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

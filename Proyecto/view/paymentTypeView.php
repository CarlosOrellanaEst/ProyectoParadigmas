<?php

    require '../domain/Owner.php';
    require '../business/paymentTypeBusiness.php';
    require '../business/ownerBusiness.php';

    session_start();

    // Incluimos las clases necesarias antes de acceder a la sesión

    // Obtenemos el usuario logueado desde la sesión
    $userLogged = $_SESSION['user'];
    $ownerBusiness = new ownerBusiness();

    // Definimos los propietarios en función del tipo de usuario
    if ($userLogged->getUserType() == "Administrador") {
        $owners = $ownerBusiness->getAllTBOwners();
        if (!$owners || empty($owners)) {
            echo "<script>alert('No se encontraron propietarios.');</script>";
        }
    } else if ($userLogged->getUserType() == "Propietario") {
        $owners = [$userLogged];  // Colocamos al propietario en un array para poder iterarlo
    }

    // Guardamos la lista de propietarios en la sesión para usarla más adelante
    $_SESSION['owners'] = $owners;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tipo de pago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        td, th {
            border-right: 1px solid;
        }
        .required {
            color: red;
        }
    </style>
    
    <?php
//         require '../business/paymentTypeBusiness.php';
//         require '../business/ownerBusiness.php';

//         session_start();
//         $userLogged = $_SESSION['user'];
//      //   echo ($userLogged);
//         $ownerBusiness = new ownerBusiness();
//          if ($userLogged->getUserType() == "Administrador") {
//             $owners = $ownerBusiness->getAllTBOwners();
//             if (!$owners || empty($owners)) {
//                 echo "<script>alert('No se encontraron propietarios.');</script>";
//             }
//          } else if ($userLogged->getUserType() == "Propietario") {
//             $owners  =  $userLogged;
//    //         echo ($owners);
//          }
    ?>
    <script src="../resources/paymentTypeView.js"></script>
    <script src="../resources/paymentTypeAJAX.js"></script>
</head>
<body>
    <a href="adminView.php">← Volver al inicio</a>
    <header> 
        <h1>CRUD Tipo de pago</h1>
        <p><span class="required">*</span> Campos requeridos</p>
    </header>
    
    <section>
        <form method="post" id="formCreate">
            <label for="ownerId">Propietario</label>
            <select name="ownerId" id="ownerId" required>
                <?php
                    // session_start();

                    // echo '<select>';
                    // if (!empty($owners)) {
                    //     foreach ($owners as $owner) {
                    //       //  echo ($owner->getFullName());
                    //         echo '<option value="' . htmlspecialchars($owner->getIdTBOwner()) . '">'
                    //             . htmlspecialchars($owner->getFullName()) 
                    //             . '</option>';
                    //     }
                    // } else {
                    //     echo '<option value="">No hay propietarios disponibles</option>';
                    // }
                    // echo '</select>';

                    $owners = $_SESSION['owners'];  // Recuperar de la sesión

                    if (!empty($owners)) {
                        foreach ($owners as $owner) {
                            echo '<option value="' . htmlspecialchars($owner->getIdTBOwner()) . '">'
                                . htmlspecialchars($owner->getFullName()) 
                                . '</option>';
                        }
                    } else {
                        echo '<option value="">No hay propietarios disponibles</option>';
                    }
                ?>
            <br><br>
            <label for="accountNumber">Número de Cuenta <span class="required">*</label>
            <input placeholder="Ingrese el número de cuenta" type="text" name="accountNumber" id="accountNumber"/><br><br>
            
            <label for="sinpeNumber">Número de SINPE</label>
            <input placeholder="Ingrese el número de SINPE" type="text" name="sinpeNumber" id="sinpeNumber"/><br>

            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>
    <br>
    <section>
        <form id="formSearchOne" method="get">
            <label for="searchOne">Buscar por número de cuenta</label>
            <input type="text" required placeholder="Ingrese el número de cuenta" name="searchOne" id="searchOne">
            <input type="submit" value="Buscar"/>
        </form>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Owner id</th>
                    <th>Número de SINPE</th>
                    <th>Número de cuenta</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $paymentTypeBusiness = new paymentTypeBusiness();
                    $all = $paymentTypeBusiness->getAll();
                    $bankAccountFiltered = [];

                    if (isset($_GET['searchOne'])) {
                        $searchTerm = $_GET['searchOne'];
                        $bankAccountFiltered = array_filter($all, function($bankAccount) use ($searchTerm) {
                            return stripos($bankAccount->getAccountNumber(), $searchTerm) !== false;
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
                                                <option value="1"' . (($current->getStatus() == 1) ? ' selected' : '') . '>Activo</option>
                                                <option value="0"' . (($current->getStatus() == 0) ? ' selected' : '') . '>Inactivo</option>
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

<!DOCTYPE html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Inventario Semen</title>
    <link rel="icon" href="../resources/icons/bull.png">
    <link rel="stylesheet" href="../resources/css/css.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php
    include '../business/bullBusiness.php';
    ?>

</head>

<body>

    <header> 
    </header>

    <?php
    $ranchId = $_GET['id'];
    ?>

    <nav>
        <ul>
            <li><a href="indexToUser.php?idRanch=<?php echo $ranchId; ?>">Fincas</a></li>
        </ul>
    </nav>
    

    <section id="form">
        <table>
            <tr>
                <th>Serie</th>
                <th>Nombre</th>
                <th>Casa Comercial</th>
                <th>Fecha Compra</th>
                <th>Cantidad Pajillas</th>
                <th>Precio x Pajillas</th>
                <th></th>
            </tr>
            <form method="post" enctype="multipart/form-data" action="../business/bullAction.php">
                <tr>
                    <input type="hidden" name="ranch" value="<?php echo $ranchId ;?>">
                    <td><input required type="text" name="code" id="code"/></td>
                    <td><input required type="text" name="name" id="name"/></td>
                    <td><input required type="text" name="commercialcase" id="commercialcase"/></td>
                    <td><input required type="date" name="buydate" id="buydate"/></td>
                    <td><input required type="number" name="strawsquantity" id="sstrawsquantity"/></td>
                    <td><input required type="number" name="strawsprice" id="sstrawsprice"/></td>
                    <td><input type="submit" value="Crear" name="create" id="create"/></td>
                </tr>
            </form>
            <?php
            $bullBusiness = new BullBusiness();
            $allBulls = $bullBusiness->getAllTBBull();
            foreach ($allBulls as $current) {
                echo '<form method="post" enctype="multipart/form-data" action="../business/bullAction.php">';
                echo '<input type="hidden" name="idBull" value="' . $current->getIdTBBull() . '">';
                echo '<tr>';
                echo '<input type="hidden" name="ranch" id="ranch" value="' . $current->getRanchTBBull() . '"/>';
                echo '<td><input type="text" name="code" id="code" value="' . $current->getCodeTBBull() . '"/></td>';
                echo '<td><input type="text" name="name" id="name" value="' . $current->getNameTBBull() . '"/></td>';
                echo '<td><input type="text" name="commercialcase" id="commercialcase" value="' . $current->getCommercialCaseTBBull() . '"/></td>';
                echo '<td><input type="date" name="buydate" id="buydate" value="' . $current->getBuyDateTBBull() . '"/></td>';
                echo '<td><input type="number" name="strawsquantity" id="sstrawsquantity" value="' . $current->getStrawsQuantityTBBull() . '"/></td>';
                echo '<td><input type="number" name="strawsprice" id="sstrawsprice" value="' . $current->getStrawsPriceTBBull() . '"/></td>';
                echo '<td><input type="submit" value="Actualizar" name="update" id="update"/></td>';
                echo '<td><input type="submit" value="Eliminar" name="delete" id="delete"/></td>';
                echo '</tr>';
                echo '</form>';
            }
            ?>


            <tr>
                <td></td>
                <td>
                    <?php
                    if (isset($_GET['error'])) {
                        if ($_GET['error'] == "emptyField") {
                            echo '<p style="color: red">Campo(s) vacio(s)</p>';
                        } else if ($_GET['error'] == "numberFormat") {
                            echo '<p style="color: red">Error, formato de numero</p>';
                        } else if ($_GET['error'] == "dbError") {
                            echo '<center><p style="color: red">Error al procesar la transacción</p></center>';
                        }
                    } else if (isset($_GET['success'])) {
                        echo '<p style="color: green">Transacción realizada</p>';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </section>

    <footer>
    </footer>

</body>
</html>
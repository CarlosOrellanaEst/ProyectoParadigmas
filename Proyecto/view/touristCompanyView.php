<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <button><a href="../index.html">Index</a></button>
        <title>Empresa turística</title> 
        
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <style>
            td,
            th {
                border-right: 1px solid;
            }

            .text {
                width: 180px;
                height: 80px;
            }
        </style>
        <?php
        include_once '../business/touristCompanyBusiness.php';
        include_once '../business/touristCompanyTypeBusiness.php';
        include_once '../business/ownerBusiness.php';
        
        $ownerBusiness = new OwnerBusiness();
        $owners = $ownerBusiness->getAllTBOwner();
        $touristCompanyTypeBusiness = new touristCompanyTypeBusiness();
        $touristCompanyTypes = $touristCompanyTypeBusiness->getAll();

        ?>
        <script src="../resources/touristCompanyView.js"></script>
    </head>

    <body>
        <header>
            <h1>CRUD Empresa turística</h1>
        </header>
        <section id="create">
            <form method="post" action="../business/touristCompanyAction.php" onsubmit="return confirmAction(event);">
                <label for="legalName">Nombre legal: </label>
                <input placeholder="Nombre legal" type="text" name="legalName" id="legalName" />
                <label for="magicName">Nombre mágico: </label>
                <input placeholder="Nombre mágico" type="text" name="magicName" id="magicName" />


                <label for="ownerId">Dueño: </label>
                <select name="ownerId" id="ownerId">
                    <option value="0">Ninguno</option>
                    <?php foreach ($owners as $owner): ?>
                        <option value="<?php echo htmlspecialchars($owner->getIdTBOwner()); ?>">
                            <?php echo htmlspecialchars($owner->getName() . ' ' . $owner->getSurnames()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="companyType">Tipo de empresa: </label>
                <select name="companyType" id="companyType">
                    <option value="0">Ninguno</option>
                    <?php foreach ($touristCompanyTypes as $touristCompanyType): ?>
                        <option value="<?php echo htmlspecialchars($touristCompanyType->getId()); ?>">
                            <?php echo htmlspecialchars($touristCompanyType->getName()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>


                <input type="hidden" name="status" value="1">


                <input type="submit" value="Crear" name="create" id="create" />
            </form>

        </section>
        <br>
        <section>
            <form id="formSearchOne" method="get">
                <label for="searchOne">Buscar por nombre: </label>
                <input type="text" placeholder="Nombre" name="searchOne" id="searchOne">
                <input type="submit" value="Buscar" />
            </form>
            <br>
            <div id="message" hidden></div>
            <table>
                <thead>
                    <tr>
                        <th>Nombre legal</th>
                        <th>Nombre mágico</th>
                        <th>Dueño</th>
                        <th>Tipo de empresa</th>
                        <th>Acciónes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $touristCompanyBusiness = new touristCompanyBusiness();
                    $ownerBusiness = new OwnerBusiness();
                    $touristCompanyTypeBusiness = new touristCompanyTypeBusiness();
                    $all = $touristCompanyBusiness->getAll();
                    $allowners = $ownerBusiness->getAllTBOwner();
                    $alltouristCompanyTypes = $touristCompanyTypeBusiness->getAll();
                    $touristCompanyFiltered = [];

                    // Filtrar los resultados si se ha realizado una búsqueda
                    if (isset($_GET['searchOne'])) {
                        $searchTerm = $_GET['searchOne'];
                        $touristCompanyFiltered = array_filter($all, function($touristCompanyBusiness) use ($searchTerm) {
                            return stripos($touristCompanyBusiness->getLegalName(), $searchTerm) !== false;
                        });
                    }
                    if (count($touristCompanyFiltered) > 0) {
                        $all = $touristCompanyFiltered;
                    }

                    
                    

                    if (count($all) > 0) {
                        foreach ($all as $current) {
                            
                            $assignedCompanyType = $touristCompanyTypeBusiness->getById($current->getCompanyType());
                            $assignedOwner = $ownerBusiness->getTBOwner($current->getOwner());
                            echo '<form method="post" action="../business/touristCompanyAction.php" onsubmit="return confirmAction(event);">';
                            echo '<tr>';

                            '<td><type="hidden" name="id" value="' . $current->getId() . '"></td>';

                            echo '<td><input type="text" name="tbtouristcompanyid" value="'. htmlspecialchars($current->getLegalName()) .'"></td>';
                            echo '<td><input type="text" name="magicName" value="' . htmlspecialchars($current->getMagicName()) . '"></td>';
                            echo '<td>';
                            echo '<select name="ownerId">';
                            foreach ($allowners as $owner) {
                                echo '<option value="' . htmlspecialchars($owner->getIdTBOwner()) . '"';
                                
                                if ($owner->getIdTBOwner() == $current->getOwner()) {
                                    
                                    echo ' selected';
                                }
                                echo '>' . htmlspecialchars($owner->getFullName()) . '</option>';
                            }
                            echo '>' . htmlspecialchars($assignedOwner->getFullName()) . '</option>';

                            echo '</select>';
                            echo '</td>';
                            echo '<td>';
                            echo '<select name="companyType">';
                            foreach ($alltouristCompanyTypes as $touristCompanyType) {
                                echo '<option value="' . htmlspecialchars($touristCompanyType->getId()) . '"';
                                if ($touristCompanyType->getId() == $current->getCompanyType()) {
                                    echo ' selected';
                                }
                                echo '>' . htmlspecialchars($touristCompanyType->getName()) . '</option>';
                            }
                            echo '>' . htmlspecialchars($assignedCompanyType->getName()) . '</option>';

                            echo '</select>';
                            echo '</td>';
                            echo '<input type="hidden" name="status" value="1">';
                            echo '<td>';
                            echo '<input type="hidden" name="id" value="' . $current->getId() . '">';
                            echo '<input type="submit" value="Actualizar" name="update" />';
                            echo '<input type="submit" value="Eliminar" name="delete"/>';
                            echo '</td>';
                            echo '</tr>';
                            echo '</form>';
                        }
                    } else {
                        echo '<tr><td colspan="6">No se encontraron resultados</td></tr>';
                    }
                    ?>
                </tbody>
 
            </table>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showAlertBasedOnURL();
                });
            </script>



        
        </section>

                      
    </body>
    




</html>

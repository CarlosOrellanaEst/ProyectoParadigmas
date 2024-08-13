<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CRUD Propietarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <style>
        td, th {
            border-right: 1px solid;
        }
    </style>
    
    <?php
    include '../business/OwnerBusiness.php';
    ?>
    <script src="../resources/ownerView.js"></script>
</head>
<body>
    <header> 
        <h1>CRUD Propietarios</h1>
    </header>
    <section id="formCreate">
        <form method="post" action="../business/ownerAction.php">
            <label for="name">Nombre</label>
            <input 
                required 
                placeholder="nombre" 
                type="text" 
                name="ownerName" 
                id="name" 
                pattern="[A-Za-z\s]+" 
                title="Solo se permiten letras y espacios"
                oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')"
                minlength="2" 
                maxlength="50"
            />
            
            <label for="surnames">Apellidos</label>
            <input 
                required 
                placeholder="apellidos" 
                type="text" 
                name="ownerSurnames" 
                id="surnames" 
                pattern="[A-Za-z\s]+" 
                title="Solo se permiten letras y espacios"
                oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')"
                minlength="2" 
                maxlength="100"
            />
            
            <label for="legalIdentification">Identificación Legal</label>
            <input 
                required 
                placeholder="identificacionLegal" 
                type="text" 
                name="ownerLegalIdentification" 
                id="legalIdentification" 
                pattern="[0-9]{9}" 
                title="Debe ser un número de 9 dígitos"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            />
            
            <label for="phone">Teléfono</label>
            <input 
                required 
                placeholder="telefono" 
                type="text" 
                name="ownerPhone" 
                id="phone" 
                pattern="[0-9]{8}" 
                title="Debe ser un número de 8 dígitos"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            />
            
            <label for="email">Correo</label>
            <input 
                required 
                placeholder="correo" 
                type="email" 
                name="ownerEmail" 
                id="email"
            />
            
            <label for="direction">Dirección</label>
            <input 
                required 
                placeholder="direccion" 
                type="text" 
                name="ownerDirection" 
                id="direction" 
                minlength="5" 
                maxlength="255"
            />
            
            <input type="submit" value="Crear" name="create" id="create"/>
        </form>
    </section>


    <br><br>
    <section>
        <form id="formSearchOne" method="get">
            <label for="searchOne">Buscar por nombre</label>
            <input type="text" required placeholder="nombre del propietario" name="searchOne" id="searchOne">
            <input type="submit" value="Buscar"/>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Identificacion Legal</th>
                    <th>Telefono</th>
                    <th>Correo</th>
                    <th>Direccion</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ownerBusiness = new OwnerBusiness();
                $allowners = $ownerBusiness->getAllTBOwner();
                $ownersFiltered = [];

                // Filtrar los resultados si se ha realizado una búsqueda
                if (isset($_GET['searchOne'])) {
                    $searchTerm = $_GET['searchOne'];
                    $ownersFiltered  = array_filter($allowners, function($owner) use ($searchTerm) {
                        return stripos($owner->getName(), $searchTerm) !== false;
                    });
                }
                if (count($ownersFiltered) > 0) {
                    $allowners = $ownersFiltered;
                }

                foreach ($allowners as $current) {
                    echo '<form method="post" action="../business/ownerAction.php" onsubmit="return confirmDelete(event);">';
                    echo '<input type="hidden" name="ownerID" value="' . $current->getIdTBOwner() . '">';
                    echo '<tr>';
                        echo '<td><input type="text" name="ownerName" value="' . $current->getName() . '" 
                            pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios" 
                            oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, \'\')" 
                            minlength="2" maxlength="50" required/></td>';
                        
                        echo '<td><input type="text" name="ownerSurnames" value="' . $current->getSurnames() . '" 
                            pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios" 
                            oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, \'\')" 
                            minlength="2" maxlength="100" required/></td>';
                        
                        echo '<td><input type="text" name="ownerLegalIdentification" value="' . $current->getLegalIdentification() . '" 
                            pattern="[0-9]{9}" title="Debe ser un número de 9 dígitos" 
                            oninput="this.value = this.value.replace(/[^0-9]/g, \'\')" required/></td>';
                        
                        echo '<td><input type="text" name="ownerPhone" value="' . $current->getPhone() . '" 
                            pattern="[0-9]{8}" title="Debe ser un número de 8 dígitos" 
                            oninput="this.value = this.value.replace(/[^0-9]/g, \'\')" required/></td>';
                        
                        echo '<td><input type="email" name="ownerEmail" value="' . $current->getEmail() . '" required/></td>';
                        
                        echo '<td><input type="text" name="ownerDirection" value="' . $current->getDirectionTBOwner() . '" 
                            minlength="5" maxlength="255" required/></td>';
                        
                        echo '<td>';
                            echo '<input type="submit" value="Actualizar" name="update"/>';
                            echo '<input type="submit" value="Eliminar" name="delete"/>';
                        echo '</td>';
                    echo '</tr>';
                    echo '</form>';
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>

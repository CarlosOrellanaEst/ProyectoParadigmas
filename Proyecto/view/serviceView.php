<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Servicios de Empresas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php
        include '../business/serviceCompanyBusiness.php';
        include '../business/TouristCompanyBusiness.php';

        $serviceCompanyBusiness = new serviceCompanyBusiness();
        $services = $serviceCompanyBusiness->getAllTBServices();
        $touristCompanyBusiness = new TouristCompanyBusiness();
        $companies = $touristCompanyBusiness->getAll();
        $imageBasePath = '../images/services/';
    ?>
    <script src="../resources/AJAXCreateService.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>CRUD Servicios de Empresas</h1>
        <a href="../index.html">← Volver al inicio</a>
    </header>

    <section id="create">
        <h2>Crear Servicios de Empresa</h2>
        <form method="post" id="formCreate" action="../business/serviceCompanyAction.php" enctype="multipart/form-data">
            <label for="companyID">Nombre de la Empresa Turística: </label>
            <select name="companyID" id="companyID">
                <?php foreach ($companies as $company): ?>
                    <option value="<?php echo htmlspecialchars($company->getTbtouristcompanyid()); ?>">
                        <?php echo htmlspecialchars($company->getTbtouristcompanymagicname()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
                </div>
            </div>
            <br><br>

            <div id="attributes">
               <div>
                    <label for="serviceId1">Servicio: </label>
                 <select name="serviceId[]" id="serviceId1" required>
                    <?php foreach ($services as $service): ?>
                     <option value="<?php echo htmlspecialchars($service->getIdTbservice()); ?>">
                      <?php echo htmlspecialchars($service->getTbservicename()); ?>
                </option>
                     <?php endforeach; ?>
                 </select>
             </div>
            </div>
<button type="button" id="addAttribute">Agregar otro servicio</button>
            <br>
                    <label for="imagenes">Selecciona las imagenes (una por cada servicio):  </label>
                    <input type="file" name="imagenes[]" id="imagenes" multiple required />
            <br><br>

            <input type="submit" value="Crear" name="create" id="create" />
        </form>
    </section>
    <hr>
    <section>
    <h2>Buscar y Editar Servicios</h2>
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
                <th>Nombre de la Empresa</th>
                <th>Nombre del Servicio</th>
                <th>Imágenes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include_once '../business/serviceCompanyBusiness.php'; // Ajusta la ruta según sea necesario

            $serviceCompanyBusiness = new ServiceCompanyBusiness();
            $touristCompanyBusiness = new TouristCompanyBusiness();
            $allServiceCompanies = $serviceCompanyBusiness->getAllTBServiceCompanies();
            $allTouristCompanies = $touristCompanyBusiness->getAll();
            $allServices = $serviceCompanyBusiness->getAllTBServices();
            $serviceCompanyFiltered = [];

            // Filtrar los resultados si se ha realizado una búsqueda
            if (isset($_GET['searchOne'])) {
                $searchTerm = $_GET['searchOne'];
                $serviceCompanyFiltered = array_filter($allServiceCompanies, function ($serviceCompany) use ($searchTerm) {
                    return stripos($serviceCompany->getTbserviceid(), $searchTerm) !== false; // Ajusta según el campo por el que quieras buscar
                });
            }
            if (count($serviceCompanyFiltered) > 0) {
                $allServiceCompanies = $serviceCompanyFiltered;
            }

            if (count($allServiceCompanies) > 0) {
                foreach ($allServiceCompanies as $current) {
                    $assignedCompany = $touristCompanyBusiness->getById($current->getTbtouristcompanyid());
                    $assignedService = $serviceCompanyBusiness->getTBService($current->getTbserviceid());
                    echo '<tr>';
                    echo '<form method="post" action="../business/serviceCompanyAction.php" onsubmit="return confirmAction(event);" enctype="multipart/form-data">';
                    
                    // Combobox para seleccionar la empresa
                    echo '<td>';
                    echo '<select name="companyId" required>';
                    foreach ($allTouristCompanies as $company) {
                        echo '<option value="' . htmlspecialchars($company->getTbtouristcompanyid()) . '"';
                        if ($company->getTbtouristcompanyid() == $current->getTbtouristcompanyid()) {
                            echo ' selected';
                        }
                        echo '>' . htmlspecialchars($company->getTbtouristcompanylegalname()) . '</option>';
                    }
                   
                   
                    echo '<td>';
                    echo '<select name="serviceId" required>';
                    foreach ($allServices as $service) {
                        echo '<option value="' . htmlspecialchars($service->getIdTbservice()) . '"';
                        if ($service->getIdTbservice() == $current->getTbserviceid()) {
                            echo ' selected';
                        }
                        echo '>' . htmlspecialchars($service->getTbservicename()) . '</option>';
                    }
                    echo '</select>';
                    echo '</td>';

                    // Mostrar las imágenes
                    $urls = explode(',', $current->getTbservicecompanyURL());
                    echo '<td>';
                    foreach ($urls as $url) {
                        if (!empty($url)) {
                        echo '<img src="' . $imageBasePath . trim($url) . '" alt="Foto" width="50" height="50" />';
                        }
                    }
                    echo '</td>';

                        
                        echo '<td>';
                        echo '<select name="imageIndex">';
                        foreach ($urls as $index => $url) {
                            echo '<option value="' . $index . '">Imagen ' . ($index + 1) . '</option>';
                        }
                        echo '</select>';
                        // echo '<input type="file" name="newImage" accept="image/*" />';
                        echo '</td>';

                        // Botones de acciones: Actualizar y Eliminar
                        echo '<form method="post" action="../business/serviceCompanyAction.php">';
                        echo '<input type="hidden" name="serviceID" value="' . $current->getTbservicecompanyid() . '">';
                        echo '<td>';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($current->getTbservicecompanyid()) . '">';
                        echo '<input type="submit" value="Actualizar" name="update" />';
                        echo '<input type="submit" value="Eliminar" name="delete"/>';
                        echo '<input type="submit" value="Eliminar Imagen" name="deleteImage">';
                        echo '</td>';
                        echo '</form>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6">No se encontraron resultados</td></tr>';
                }
            ?>
        </tbody>
    </table>
</section>




    <script>
    
    document.getElementById('addAttribute').addEventListener('click', function () {
        const attributeContainer = document.createElement('div');
        attributeContainer.innerHTML = `
            <label>Servicio: </label>
            <select name="serviceId[]"  required>
            <?php foreach ($services as $service): ?>
                <option value="<?php echo htmlspecialchars($service->getIdTbservice()); ?>">
                    <?php echo htmlspecialchars($service->getTbservicename()); ?>
                </option>
            <?php endforeach; ?>
            </select>
        `;
        document.getElementById('attributes').appendChild(attributeContainer);
       
    });
</script>

</body>
</html>

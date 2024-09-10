<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Servicios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="../resources/AJAXCreateService.js"></script>
    <?php
        include '../business/serviceCompanyBusiness.php';
        include '../business/TouristCompanyBusiness.php';

        $serviceCompanyBusiness = new ServiceCompanyBusiness();
        $services = $serviceCompanyBusiness->getAllTBServices();
        $touristCompanyBusiness = new TouristCompanyBusiness();
        $companies = $touristCompanyBusiness->getAll();
        $imageBasePath = '../images/services/';
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>CRUD Servicios</h1>
        <a href="../index.html">← Volver al inicio</a>
    </header>

    <section id="create">
        <h2>Crear Servicio</h2>
        <form method="post" id="formCreate" action="../business/serviceCompanyAction.php" enctype="multipart/form-data">
            <label for="companyID">Nombre de la Empresa Turística: </label>
            <select name="companyID" id="companyID" required>
                <?php foreach ($companies as $company): ?>
                    <option value="<?php echo htmlspecialchars($company->getTbtouristcompanyid()); ?>">
                        <?php echo htmlspecialchars($company->getTbtouristcompanymagicname()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>

            <div id="servicesContainer">
                <div>
                    <label for="serviceId1">Servicio: </label>
                    <select name="serviceId" id="serviceId1" required>
                        <?php foreach ($services as $service): ?>
                            <option value="<?php echo htmlspecialchars($service->getIdTbservice()); ?>">
                                <?php echo htmlspecialchars($service->getTbservicename()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="button" id="addService">Agregar otro servicio</button>
            <br>

            <label for="imagenes">Selecciona las imágenes (una por cada servicio): </label>
            <input type="file" name="imagenes[]" id="imagenes" multiple />
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
                $allServiceCompanies = $serviceCompanyBusiness->getAllTBServiceCompanies();
                
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
                        echo '<form method="post" action="../business/serviceCompanyAction.php" enctype="multipart/form-data">';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($current->getTbservicecompanyid()) . '">';
                        echo '<input type="hidden" name="existingImages" value="' . htmlspecialchars(is_array($current->getTbservicecompanyURL()) ? implode(',', $current->getTbservicecompanyURL()) : $current->getTbservicecompanyURL()) . '">';
                        // Combobox para seleccionar la empresa
                        echo '<td>';
                        echo '<select name="companyId" required>';
                        foreach ($companies as $company) {
                            echo '<option value="' . htmlspecialchars($company->getTbtouristcompanyid()) . '"';
                            if ($company->getTbtouristcompanyid() == $current->getTbtouristcompanyid()) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($company->getTbtouristcompanylegalname()) . '</option>';
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

                        // Seleccionar la imagen para eliminar
                        echo '<td>';
                        echo '<select name="imageIndex">';
                        foreach ($urls as $index => $url) {
                            echo '<option value="' . $index . '">Imagen ' . ($index + 1) . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';

                        echo '<td>';
                        echo '<button type="button" class="show-attributes" data-service-company-id="' . $current->getTbservicecompanyid() . '">Mostrar Atributos</button>';
                        echo '<input type="submit" value="Actualizar" name="update" />';
                        echo '<input type="submit" value="Eliminar" name="delete"/>';
                        echo '<input type="submit" value="Eliminar Imagen" name="deleteImage" />';
                        echo '<div id="attributes-' . $current->getTbservicecompanyid() . '" class="attributes-table" style="display:none;">';
                        echo '<table>';
                        echo '<tr><th>Atributo</th></tr>';
                        $serviceIds = explode(',', $current->getTbserviceid());
                        foreach ($serviceIds  as $index => $serviceId) {
                            echo '<tr>';
                            echo '<td>';
                            echo '<select name="serviceIdInputs" required>';
                            foreach ($services as $service) {
                                echo '<option value="' . htmlspecialchars($service->getIdTbservice()) . '"';
                                if ($service->getIdTbservice() == $serviceId) {
                                    echo ' selected';
                                }
                                echo '>' . htmlspecialchars($service->getTbservicename()) . '</option>';
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        
                        echo '</table>';
                        echo '</div>';
                        echo '</td>';
                        
                        echo '</form>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">No se encontraron resultados.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </section>

    <script>
       document.addEventListener('DOMContentLoaded', function() {
    let serviceCount = 1; // Inicializamos el contador de servicios

    document.getElementById('addService').addEventListener('click', function () {
        if (serviceCount < 7) { // Limitar a 7 servicios
            serviceCount++;
            const servicesContainer = document.getElementById('servicesContainer');
            const newService = document.createElement('div');
            newService.innerHTML = `
                <label for="serviceId${serviceCount}">Servicio: </label>
                <select name="serviceId" id="serviceId${serviceCount}" required>
                    <?php foreach ($services as $service): ?>
                        <option value="<?php echo htmlspecialchars($service->getIdTbservice()); ?>">
                            <?php echo htmlspecialchars($service->getTbservicename()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            `;
            servicesContainer.appendChild(newService);
        } else {
            alert('No puedes agregar más de 7 servicios.');
        }
    });

    // Manejo del botón para mostrar atributos
    document.querySelectorAll('.show-attributes').forEach(button => {
        button.addEventListener('click', function() {
            const serviceCompanyId = this.getAttribute('data-service-company-id');
            const attributesDiv = document.getElementById('attributes-' + serviceCompanyId);
            if (attributesDiv) {
                attributesDiv.style.display = attributesDiv.style.display === 'none' ? 'block' : 'none';
            }
        });
    });
});

       
    </script>
</body>
</html>

<?php
    require '../domain/Owner.php';
    require '../business/ownerBusiness.php';

    session_start();
    $userLogged = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Servicios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="../resources/AJAXCreateService.js"></script>
    <?php
        include '../business/serviceCompanyBusiness.php';
        include_once '../business/touristCompanyBusiness.php';

        $serviceCompanyBusiness = new ServiceCompanyBusiness();
        $services = $serviceCompanyBusiness->getAllTBServices();
        $touristCompanyBusiness = new TouristCompanyBusiness();
        if ($userLogged->getUserType() == "Propietario") {
            $companies = $touristCompanyBusiness->getAllByOwnerID($userLogged->getIdTBOwner());
        } else if ($userLogged->getUserType() == "Administrador") {
            $companies = $touristCompanyBusiness->getAll();
        }
        
        $imageBasePath = '../images/services/';
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php
        if ($userLogged->getUserType() == "Propietario") {
            echo '<a href="ownerViewSession.php">← Volver al inicio</a>';
        } else if ($userLogged->getUserType() == "Administrador") {
            echo '<a href="adminView.php">← Volver al inicio</a>';
        } else if ($userLogged->getUserType() == "Turista") {
            echo '<a href="touristView.php">← Volver al inicio</a>';
        }
    ?>
    <header>
        <h1>CRUD Servicios</h1>
        <p><span class="required">*</span> Campos requeridos</p>
    </header>

    <section id="create">
        <h2>Crear Servicio</h2>
        <form method="post" id="formCreate" action="../business/serviceCompanyAction.php" enctype="multipart/form-data">
            <label for="companyID">Nombre de la Empresa Turística <span class="required">*</span></label>
            <select name="companyID" id="companyID" required>
                <?php foreach ($companies as $company): ?>
                    <option value="<?php echo htmlspecialchars($company->getTbtouristcompanyid()); ?>">
                        <?php echo htmlspecialchars($company->getTbtouristcompanylegalname()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>

            <div id="servicesContainer">
                <div id="serviceRow1">
                    <label for="serviceId">Servicio <span class="required">*</span></label>
                    <select name="serviceId" id="serviceId" required>
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

            <label for="imagenes">Selecciona las imágenes (máximo 5 imágenes)</label><br>
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

        <!-- Tabla para mostrar los resultados -->
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Nombre de la Empresa</th>
                    <th>Servicios</th>
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
                        return stripos($serviceCompany->getTbserviceid(), $searchTerm) !== false;
                    });
                }

                if (count($serviceCompanyFiltered) > 0) {
                    $allServiceCompanies = $serviceCompanyFiltered;
                }

                if (count($allServiceCompanies) > 0) {
                    foreach ($allServiceCompanies as $current) {
                        $assignedCompany = $touristCompanyBusiness->getById($current->getTbtouristcompanyid());
                        
                        echo '<tr>';
                        echo '<form method="post" action="../business/serviceCompanyAction.php" enctype="multipart/form-data">';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($current->getTbservicecompanyid()) . '">';

                        // Mostrar nombre de la empresa
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

                        // Mostrar servicios en select
                        echo '<td>';
                        $serviceIds = explode(',', $current->getTbserviceid());
                        
                        // Verificar si serviceIds está vacío
                        if (empty($serviceIds) || (count($serviceIds) === 1 && trim($serviceIds[0]) === '')) {
                            // Si no hay servicios asociados, mostrar un mensaje
                            echo '<div class="no-services-message">No hay servicios asociados</div>';
                        } else {
                            foreach ($serviceIds as $index => $serviceId) {
                                echo '<div class="service-row">';
                                echo '<select name="serviceId[]">';
                                foreach ($services as $service) {
                                    echo '<option value="' . htmlspecialchars($service->getIdTbservice()) . '"';
                                    if ($service->getIdTbservice() == $serviceId) {
                                        echo ' selected';
                                    }
                                    echo '>' . htmlspecialchars($service->getTbservicename()) . '</option>';
                                }
                                echo '</select>';
                                echo '</div>'; // Cerrar el div de service-row
                            }

                        }
                        echo '<button type="button" class="addServiceRow">Agregar servicio</button>';
                       
                        echo '</td>';
                        
                           
                            
                           
                        
                        echo '<select name="serviceIndex">'; 
                        foreach ($serviceIds as $index => $service) {
                            echo '<option value="' . $index . '">Servicio ' . ($index + 1) . '</option>';
                        }
                        echo '</select>';
                        
                        echo '</div>';
                        echo '</td>';

                        // Mostrar las imágenes asociadas
                        echo '<td>';
                        $urls = explode(',', $current->getTbservicecompanyURL());
                        foreach ($urls as $url) {
                            if (!empty($url)) {
                                echo '<img src="' . $imageBasePath . trim($url) . '" alt="Imagen" width="50" height="50" />';
                            }
                        }
                        
                        echo '<select name="imageIndex">'; 
                        foreach ($urls as $index => $url) {
                            echo '<option value="' . $index . '">Imagen ' . ($index + 1) . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';

                        // Acciones de actualización, eliminación, etc.
                        echo '<td>';
                        echo '<input type="submit" value="Actualizar" name="update" />';
                        echo '<input type="submit" value="Eliminar" name="delete"/>';
                        echo '<input type="submit" value="Eliminar Imagen" name="deleteImage" />';
                        echo '<input type="submit" value="Eliminar Servicio" name="deleteService" />';
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
                   newService.id = `serviceRow${serviceCount}`;
                   newService.innerHTML = `
                       <label for="serviceId${serviceCount}">Servicio: </label>
                       <select name="serviceId" id="serviceId${serviceCount}" required>
                           <?php foreach ($services as $service): ?>
                               <option value="<?php echo htmlspecialchars($service->getIdTbservice()); ?>">
                                   <?php echo htmlspecialchars($service->getTbservicename()); ?>
                               </option>
                           <?php endforeach; ?>
                       </select>
                       <button type="button" class="remove-service">Eliminar</button>
                   `;
                   servicesContainer.appendChild(newService);
               } else {
                   alert('No puedes agregar más de 7 servicios.');
               }
           });

           document.querySelectorAll('.addServiceRow').forEach(button => {
                button.addEventListener('click', function() {
                    const container = this.previousElementSibling;

                    if (container.querySelectorAll('.service-row').length < 7) {
                        const newService = document.createElement('div');
                        newService.classList.add('service-row');
                        newService.innerHTML = `
                            <select name="serviceId[]">
                                <?php foreach ($services as $service): ?>
                                    <option value="<?php echo htmlspecialchars($service->getIdTbservice()); ?>">
                                        <?php echo htmlspecialchars($service->getTbservicename()); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="remove-service">Eliminar</button>
                        `;
                        container.appendChild(newService);
                        
                        newService.querySelector('.remove-service').addEventListener('click', function() {
                            newService.remove();
                        });
                    } else {
                        alert("No puedes agregar más de 7 servicios.");
                    }
                });
            });
        });
    </script>
</body>
</html>

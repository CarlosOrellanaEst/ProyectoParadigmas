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
                            
                            $assignedOwner = $ownerBusiness->getTBOwner($current->getOwner());
                            
                            $assignedCompanyType = $touristCompanyTypeBusiness->getById($current->getCompanyType());

                            echo '<form method="post" action="../business/touristCompanyAction.php" onsubmit="return confirmAction(event);">';
                            echo '<tr>';
                            echo '<td><input type="text" name="tbtouristcompanyid" value="'. htmlspecialchars($current->getLegalName()) .'"></td>';
                            echo '<td><input type="text" name="magicName" value="' . htmlspecialchars($current->getMagicName()) . '"></td>';
                            echo '<td><input type="text" name="owner" value="' . htmlspecialchars($assignedOwner->getFullName()) . '"></td>';
                            echo '<td><input type="text" name="companyType" value="' . htmlspecialchars($assignedCompanyType->getName()) . '"></td>';
                            echo '<td><input type="text" name="status" value="' . ($current->getStatus() == 1 ? 'Activo' : 'Inactivo') . '"></td>';
                            echo '<td>';
                            echo '<input type="hidden" name="id" value="' . $current->getId() . '">';
                            echo '<input type="submit" value="Actualizar" name="update" />';
                            echo '<input type="submit" value="Eliminar" name="delete" />';
                            echo '</td>';
                            echo '</tr>';
                            echo '</form>';
                        }
                    } else {
                        echo '<tr><td colspan="6">No se encontraron resultados</td></tr>';
                    }
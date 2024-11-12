<?php
require '../domain/Owner.php';
require '../business/ownerBusiness.php';

session_start();
$userLogged = $_SESSION['user'];
$ownerBusiness = new ownerBusiness();

// Definimos los propietarios en función del tipo de usuario
if ($userLogged->getUserType() == "Administrador" || $userLogged->getUserType() == "Turista") {
    $owners = $ownerBusiness->getAllTBOwners();
    if (!$owners || empty($owners)) {
        echo "<script>alert('No se encontraron propietarios.');</script>";
    }
} else if ($userLogged->getUserType() == "Propietario") {
    $owners = [$userLogged];
}

// Guardamos la lista de propietarios en la sesión para usarla abajo
$_SESSION['owners'] = $owners;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>CRUD Empresa turística</title>
    <style>
        .required {
            color: red;
        }
    </style>
    <?php
    include_once '../business/touristCompanyBusiness.php';
    include_once '../business/touristCompanyTypeBusiness.php';
    include_once '../business/ownerBusiness.php';

    $touristCompanyTypeBusiness = new TouristCompanyTypeBusiness();
    $touristCompanyTypes = $touristCompanyTypeBusiness->getAll();
    $imageBasePath = '../images/';
    ?>

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
        <h1>CRUD Empresa turística</h1>
        <p><span class="required">*</span> Campos requeridos</p>
    </header>


    <section id="create">
    <?php
    if ($userLogged->getUserType() == "Administrador" || $userLogged->getUserType() == "Propietario") {
        ?>
        <form id="formCreate" method="post" enctype="multipart/form-data">
            
            <div class="form-group">
                <label for="legalName">Nombre legal:</label>
                <input placeholder="Nombre legal" type="text" name="legalName" id="legalName" />
            </div>

            <div class="form-group">
                <label for="magicName">Nombre mágico:</label>
                <input placeholder="Nombre mágico" type="text" name="magicName" id="magicName" />
            </div>

            <div class="form-group">
                <label for="ownerId">Dueño: <span id="ownerError" class="required">*</label>
                <select name="ownerId" id="ownerId" required>
                    <option value="0">Ninguno</option>
                    <?php foreach ($owners as $owner): ?>
                        <option value="<?php echo htmlspecialchars($owner->getIdTBOwner()); ?>">
                            <?php echo htmlspecialchars($owner->getName() . ' ' . $owner->getSurnames()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="companyType">Tipo de empresa: <span class="required">*</label>
                <select name="companyType" id="companyType">
                    <option value="0">Ninguno</option>
                    <?php foreach ($touristCompanyTypes as $touristCompanyType): ?>
                        <option value="<?php echo htmlspecialchars($touristCompanyType->getId()); ?>">
                            <?php echo htmlspecialchars($touristCompanyType->getName()); ?>
                        </option>
                    <?php endforeach; ?>
               
                </select>
                <button type="button" id="addBtn">+</button>
                <div id="selectedCompanyTypesList"></div>
                <label for="customCompanyType" style="display: none; margin-top: 10px;" id="customCompanyTypeName">
                    Nombre: <span id="customCompanyTypeError" style="color:red; display:none;">*Campo obligatorio</span>
                </label>
                <input type="text" name="customCompanyType" id="customCompanyType"
                    placeholder="Especifique otro tipo de empresa" style="display: none; margin-top: 10px;" />
            </div>

            <div class="form-group">
    <label for="imagenes">Imágenes: <span id="imagenesError" style="color:red; display:none;">*campo obligatorio</span></label>
    <input type="file" name="imagenes[]" id="imagenes" multiple />
    <div id="imagePreview" style="margin-top: 10px;">
        <!-- Aquí se mostrará la imagen cuando se edite -->
    </div>
</div>

            <input type="hidden" id="status" name="status" value="1">
            <!-- Campo oculto para el ID de la empresa -->
            <input type="hidden" id="companyId" name="companyId" value="">
            <!-- Campo oculto para la acción (crear o actualizar) -->
            

            <div class="form-group">
            <input type="hidden" id="actionType" name="actionType" value="create">
            <input type="submit" value="Crear" id="formSubmitButton">
              <!-- Botón oculto de actualizar -->
        <button type="button" id="updateButton" style="display: none;" onclick="updateTouristCompany()">Actualizar</button>
  
            </div>
        </form>
        <?php
        if ($userLogged->getUserType() == "Administrador" || $userLogged->getUserType() == "Propietario") {
            ?>
            <form id="formCreate" method="post">
                <div class="form-group">
                    <label for="legalName">Nombre legal:</label>
                    <input placeholder="Nombre legal" type="text" name="legalName" id="legalName" />
                </div>

                <div class="form-group">
                    <label for="magicName">Nombre mágico:</label>
                    <input placeholder="Nombre mágico" type="text" name="magicName" id="magicName" />
                </div>

                <div class="form-group">
                    <label for="ownerId">Dueño: <span id="ownerError" class="required">*</label>
                    <select name="ownerId" id="ownerId" required>
                        <option value="0">Ninguno</option>
                        <?php foreach ($owners as $owner): ?>
                            <option value="<?php echo htmlspecialchars($owner->getIdTBOwner()); ?>">
                                <?php echo htmlspecialchars($owner->getName() . ' ' . $owner->getSurnames()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                </div>

                <div class="form-group">
                    <label for="companyType">Tipo de empresa: <span class="required">*</label>
                    <select name="companyType" id="companyType">
                        <option value="0">Ninguno</option>
                        <?php foreach ($touristCompanyTypes as $touristCompanyType): ?>
                            <option value="<?php echo htmlspecialchars($touristCompanyType->getId()); ?>">
                                <?php echo htmlspecialchars($touristCompanyType->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="custom">Otro (Especifique)</option>
                    </select>
                    <button type="button" id="addBtn">+</button>
                    <div id="selectedCompanyTypesList"></div>
                    <!-- Campo oculto para el tipo de empresa personalizado -->
                    <label for="customCompanyType" style="display: none; margin-top: 10px;" id="customCompanyTypeName">
                        Nombre: <span id="customCompanyTypeError" style="color:red; display:none;">*Campo obligatorio</span>
                    </label>
                    <input type="text" name="customCompanyType" id="customCompanyType"
                        placeholder="Especifique otro tipo de empresa" style="display: none; margin-top: 10px;" />
                </div>

                <div class="form-group">
                    <label for="imagenes">Imágenes: <span id="imagenesError" style="color:red; display:none;">*campo
                            obligatorio</span></label>
                    <input type="file" name="imagenes[]" id="imagenes" multiple />
                </div>

                <input type="hidden" id="status" name="status" value="1">

                <div class="form-group">
                    <input type="submit" value="Crear" name="create" id="create" />
                </div>
            </form>
            <?php
        }
        ?>

    </section>

    <br>

    <section>
        <br>
        <div id="message" hidden></div>
        <table>
            <thead>
                <tr>
                    <th>Nombre legal</th>
                    <th>Nombre mágico</th>
                    <th>Dueño</th>
                    <th>Tipo de empresa</th>
                    <th>Imágenes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $touristCompanyBusiness = new TouristCompanyBusiness();
                $ownerBusiness = new OwnerBusiness();
                $touristCompanyTypeBusiness = new TouristCompanyTypeBusiness();

                if ($userLogged->getUserType() == "Propietario") {
                    $all = $touristCompanyBusiness->getAllByOwnerID($userLogged->getIdTBOwner());
                } else if ($userLogged->getUserType() == "Administrador" || $userLogged->getUserType() == "Turista") {
                    $all = $touristCompanyBusiness->getAll();
                }

            echo '<tr>';
            echo '<td>' . htmlspecialchars($current->getTbtouristcompanylegalname()) . '</td>';
            echo '<td>' . htmlspecialchars($current->getTbtouristcompanymagicname()) . '</td>';
            echo '<td>' . htmlspecialchars($assignedOwner->getName() . ' ' . $assignedOwner->getSurnames()) . '</td>';
            echo '<td>' . implode(', ', $companyTypesNames) . '</td>'; // Mostrar los tipos de empresa separados por coma
            echo '<td>';
            
   foreach ($current->getTbtouristcompanyurl() as $index => $image): ?>
        <?php if (!empty($image)): ?>
            <div class="image-container">
                <img src="<?php echo $imageBasePath . trim($image); ?>" alt="Foto" width="50" height="50" />
                <button type="button" onclick="deleteImage(<?php echo $current->getTbtouristcompanyid(); ?>, <?php echo $index; ?>)">Eliminar</button>
            </div>
        <?php endif; ?>
    <?php endforeach; 

            echo '</td>';
            echo '<td>';
            echo '<button type="button" onclick="deleteTouristCompany(' . htmlspecialchars($current->getTbtouristcompanyid()) . ')">Eliminar</button>';
            echo '<button type="button" onclick="fillForm(`' . htmlspecialchars($current->getTbtouristcompanyid()). '`, `' . htmlspecialchars($current->getTbtouristcompanylegalname()) . '`, `' . htmlspecialchars($current->getTbtouristcompanymagicname()) . '`, `' . htmlspecialchars($assignedOwner->getIdTBOwner()) . '`, `' . htmlspecialchars($companyTypes) . '`)">Editar</button>';
            echo '</td>';
            echo '</tr>';
        }
      else {
        echo '<tr><td colspan="6">No se encontraron resultados</td></tr>';
    }
    ?>
</tbody>
        </table>
    </section>

                if (count($all) > 0) {
                    foreach ($all as $current) {
                        $assignedCompanyType = $touristCompanyTypeBusiness->getById($current->getTbtouristcompanycompanyType());
                        $assignedOwner = $ownerBusiness->getTBOwner($current->getTbtouristcompanyowner());
                        echo '<tr>';
                        echo '<form method="post" action="../business/touristCompanyAction.php" onsubmit="return confirmAction(event);">';
                        echo '<td><input type="text" name="legalName" value="' . htmlspecialchars($current->getTbtouristcompanylegalname()) . '" ></td>';
                        echo '<td><input type="text" name="magicName" value="' . htmlspecialchars($current->getTbtouristcompanymagicname()) . '" ></td>';
                        echo '<td>';
                        echo '<select name="ownerId" required>';
                        foreach ($owners as $owner) {
                            echo '<option value="' . htmlspecialchars($owner->getIdTBOwner()) . '"';
                            if ($owner->getIdTBOwner() == $current->getTbtouristcompanyowner()) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($owner->getName() . ' ' . $owner->getSurnames()) . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '<td>';
                        echo '<select name="companyType" required>';
                        foreach ($alltouristCompanyTypes as $touristCompanyType) {
                            echo '<option value="' . htmlspecialchars($touristCompanyType->getId()) . '"';
                            if ($touristCompanyType->getId() == $current->getTbtouristcompanycompanyType()) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($touristCompanyType->getName()) . '</option>';
                        }
                        echo '</select>';
                        echo '</td>';
                        echo '<td>';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($current->getTbtouristcompanyid()) . '">';
                        echo '<input type="hidden" name="status" value="1">';

                        $images = $current->getTbtouristcompanyurl();
                        echo '<td>';
                        foreach ($images as $index => $image) {
                            if (!empty($image)) {
                                echo '<img src="' . $imageBasePath . trim($image) . '" alt="Foto" width="50" height="50" />';
                            }
                        }
                        echo '</td>';

                        /*    
                        echo '<td>';
                        echo '<select name="imageIndex">';
                        foreach ($images as $index => $image) {
                            echo '<option value="' . $index . '">Imagen ' . ($index + 1) . '</option>';
                        }
                        echo '</select>';
                        
                        echo '</td>';
                        */

                        echo '<form method="post" action="../business/touristCompanyAction.php">';
                        echo '<input type="hidden" name="photoID" value="' . $current->getTbtouristcompanyid() . '">';

                        echo '<td>';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($current->getTbtouristcompanyid()) . '">';
                        if ($userLogged->getUserType() == "Administrador" || $userLogged->getUserType() == "Propietario") {
                            // lo que esta comentado justo 8 lineas arriba (le quite ese <td> extra. es innecesario.)
                            echo '<select name="imageIndex">';
                            foreach ($images as $index => $image) {
                                echo '<option value="' . $index . '">Imagen ' . ($index + 1) . '</option>';
                            }
                            echo '</select>';

                            echo '<input type="submit" value="Actualizar" name="update" />';
                            echo '<input type="submit" value="Eliminar" name="delete"/>';
                            echo '<input type="submit" value="Eliminar Imagen" name="deleteImage">';
                        }
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

function fillForm(companyId, legalName, magicName, ownerId, companyTypeIds, imageUrl) {
    // Rellenar los campos del formulario
    $('#companyId').val(companyId);
    $('#legalName').val(legalName);
    $('#magicName').val(magicName);
    $('#ownerId').val(ownerId);
   

    // Guardar el valor original del tipo de empresa
    originalCompanyType = companyTypeIds;

    // Ocultar las secciones de imagen
    $('#imagenes').hide();  // Ocultar el campo de selección de imágenes
    $('#imagePreview').hide();  // Ocultar la vista previa de la imagen

    // Si hay una imagen existente, mostrar la vista previa
    if (imageUrl) {
        $('#imagePreview').html(`<img src="${imageUrl}" alt="Imagen actual" width="100" height="100" />`);
        $('#imagePreview').show(); // Mostrar la vista previa de la imagen
    } else {
        $('#imagePreview').html(''); // Limpiar la vista previa si no hay imagen
    }

    // Cambiar la acción del formulario a 'update' (actualización)
    $('#actionType').val('update');
    $('#formSubmitButton').hide(); // Ocultar el botón de "Crear"
    $('#updateButton').show(); // Mostrar el botón de "Actualizar"

    // Limpiar la lista de tipos de empresa seleccionados antes de agregar los nuevos
    $('#selectedCompanyTypesList').empty();

    // Si hay tipos de empresa asociados, agregar esos a la lista
    if (companyTypeIds) {
        const companyTypeArray = companyTypeIds.split(','); // Convertir la cadena a un array

        // Para cada tipo de empresa, agregamos un elemento visual en la lista de tipos seleccionados
        companyTypeArray.forEach(function(typeId) {
            // Crear un elemento de tipo de empresa en la lista
            const listItem = $('<div class="selected-type"></div>');
            listItem.text(`Tipo de empresa: ${typeId}`); // Mostrar el ID o el nombre (puedes cambiar esto)
            listItem.attr('data-type-id', typeId); // Guardamos el ID en el atributo 'data'

            // Agregar el elemento al contenedor de tipos seleccionados
            $('#selectedCompanyTypesList').append(listItem);
        });
    }
}




function updateTouristCompany() {
    let formData = new FormData($('#formCreate')[0]);

    formData.append('action', 'update');
    const magicName = $('#magicName').val();
    const legalName = $('#legalName').val();
    const ownerId = $('#ownerId').val();
    const companyType = $('#companyType').val();
    const customCompanyType = $('#customCompanyType').val();
    const status = $('#status').val();
    const companyId = $('#companyId').val();

    // Validación de propietario
    if (ownerId === "0") {
        alert("Error: se necesita seleccionar un propietario para registrar.");
        return;
    }

    // Verificar si el tipo de empresa ha cambiado o si se seleccionó uno nuevo
    let companyTypeData = '';
    if (companyType !== originalCompanyType || $('#selectedCompanyTypesList .selected-type').length > 0) {
        // Si el tipo de empresa cambió o se añadieron nuevos tipos, procesarlos
        const selectedCompanyTypes = [];
        const selectedCompanyTypeElements = document.querySelectorAll("#selectedCompanyTypesList .selected-type");

        selectedCompanyTypeElements.forEach(function (element) {
            selectedCompanyTypes.push(element.dataset.typeId);
        });

        if (selectedCompanyTypes.length > 0) {
            companyTypeData += selectedCompanyTypes.join(',');
        }

        if (companyType === "custom" && customCompanyType) {
            if (companyTypeData) {
                companyTypeData += ',';
            }
            companyTypeData += customCompanyType;
        }
    } else {
        // Si el tipo de empresa no cambió, usa el tipo de empresa original
        companyTypeData = originalCompanyType;
    }

    // Añadir datos al FormData
    formData.append("magicName", magicName);
    formData.append("legalName", legalName);
    formData.append("ownerId", ownerId);
    formData.append("status", status);
    formData.append("companyTypeData", companyTypeData);

    if (companyId) {
        formData.append('companyId', companyId);
    }

    // Enviar la solicitud Ajax
    sendAjaxRequest(formData, 'Actualizar empresa');
}

function sendAjaxRequest(formData, action) {
    $.ajax({
        url: '../business/touristCompanyAction.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert(response.message);
                location.reload(); // Recargar la página para ver los cambios
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Ocurrió un error al intentar procesar la solicitud.');
        }
    });
}

function deleteImage(companyId, imageIndex) {
    if (confirm("¿Estás seguro de que deseas eliminar esta imagen?")) {
        $.ajax({
            url: "../business/touristCompanyAction.php",
            type: "POST",
            data: {
                deleteImage: true,  // Indicamos que estamos eliminando una imagen
                photoID: companyId,
                imageIndex: imageIndex
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload(); // Recarga la página para actualizar la lista de imágenes
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("Ocurrió un error al intentar eliminar la imagen.");
            }
        });
    }
}

    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../resources/touristCompanyView.js"></script>
</body>

</html>
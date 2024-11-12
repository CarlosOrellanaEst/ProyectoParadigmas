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
    }
    ?>
</section>
    <br>

    <section>
        <br>
        <div id="message" hidden></div>
        <table border = 1>
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

    $alltouristCompanyTypes = $touristCompanyTypeBusiness->getAll();

    if (count($all) > 0) {
        foreach ($all as $current) {
            $imageUrl = !empty($current->getTbtouristcompanyurl()) ? $imageBasePath . trim($current->getTbtouristcompanyurl()[0]) : '';
            $assignedOwner = $ownerBusiness->getTBOwner($current->getTbtouristcompanyowner());

            // Obtención de los tipos de empresa como cadena separada por comas
            $companyTypes = $current->getTbtouristcompanycompanyType();
            $companyTypesArray = explode(',', $companyTypes); // Convertir la cadena en un array
            
            // Obtener los nombres de los tipos de empresa
            $companyTypesNames = [];
            foreach ($companyTypesArray as $typeId) {
                $assignedCompanyType = $touristCompanyTypeBusiness->getById(trim($typeId)); // Obtener el objeto del tipo de empresa
                if ($assignedCompanyType) {
                    $companyTypesNames[] = $assignedCompanyType->getName(); // Agregar el nombre al array
                }
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
    } else {
        echo '<tr><td colspan="6">No se encontraron resultados</td></tr>';
    }
    ?>
</tbody>
        </table>
    </section>

    <script>
      function deleteTouristCompany(id) {
    if (confirm("¿Estás seguro de que deseas eliminar esta empresa turística?")) {
        $.ajax({
            url: "../business/touristCompanyAction.php",
            type: "POST",
            data: { delete: true, id: id },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload(); // Recarga la página para actualizar la lista
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("Ocurrió un error al intentar eliminar la empresa.");
            }
        });
    }
}

const companyTypes = <?php echo json_encode(array_map(function($type) {
        return [
            'id' => $type->getId(),
            'name' => $type->getName()
        ];
    }, $touristCompanyTypes)); ?>;

    const companyTypesMap = {};
    companyTypes.forEach(type => {
        companyTypesMap[type.id] = type.name;
    });
// Variable global para almacenar el tipo de empresa original
let originalCompanyType = null;

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
        // Obtener el nombre del tipo de empresa utilizando el objeto companyTypesMap
        const companyTypeName = companyTypesMap[typeId];

        // Crear un elemento de tipo de empresa en la lista
        const listItem = $('<div class="selected-type"></div>');
        listItem.text(`Tipo de empresa: ${companyTypeName}`); // Mostrar el nombre en lugar del ID
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
            // Comprobar si hay duplicados
            const uniqueCompanyTypes = [...new Set(selectedCompanyTypes)];

            if (uniqueCompanyTypes.length !== selectedCompanyTypes.length) {
                alert("Error: No se pueden agregar tipos de empresa duplicados.");
                return; // Detenemos la ejecución si hay duplicados
            }

            companyTypeData += uniqueCompanyTypes.join(','); // Evita duplicados
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
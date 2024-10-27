<?php
include_once './touristBusiness.php';
include_once '../domain/User.php';

if (isset($_POST['create'])) {

    if (
        isset($_POST['touristLegalIdentification']) &&
        isset($_POST['touristEmail']) &&
        isset($_POST['touristNickName']) &&
        isset($_POST['touristPassword']) &&
        isset($_POST['confirmTouristPassword']) 
    ) {
        $touristName = isset($_POST['touristName']) ? trim($_POST['touristName']) : '';
        $touristSurnames = isset($_POST['touristSurnames']) ? trim($_POST['touristSurnames']) : '';
        $touristLegalIdentification = trim($_POST['touristLegalIdentification']);
        $touristPhone = isset($_POST['touristPhone']) ? trim($_POST['touristPhone']) : '';
        $touristEmail = trim($_POST['touristEmail']);
        $touristNickName = trim($_POST['touristNickName']);
        $touristPassword = trim($_POST['touristPassword']);
        $confirmTouristPassword = trim($_POST['confirmTouristPassword']);
        $idType = trim($_POST['idType']);

        // Identificación
        $isValidId = false;
        if ($idType == 'CR') {
            $isValidId = preg_match('/^\d{9}$/', $touristLegalIdentification);
            if (!$isValidId) {
                echo json_encode(['status' => 'error', 'message' => 'Identificación de Costa Rica inválida']);
                exit();
            }
        } elseif ($idType == 'foreign') {
            $isValidId = preg_match('/^[a-zA-Z0-9]{6,12}$/', $touristLegalIdentification);
            if (!$isValidId) {
                echo json_encode(['status' => 'error', 'message' => 'Identificación extranjera inválida. Debe contener entre 6 y 12 caracteres alfanuméricos.']);
                exit();
            }
        }

        // Nombre
        if (!empty($touristName) && is_numeric($touristName)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre contiene caracteres inválidos']);
            exit();
        }

        // Apellido
        if (!empty($touristSurnames) && is_numeric($touristSurnames)) {
            echo json_encode(['status' => 'error', 'message' => 'Los apellidos contienen caracteres inválidos']);
            exit();
        }

        // Teléfono
        if (!empty($touristPhone) && !preg_match('/^\d{8}$/', $touristPhone)) {
            echo json_encode(['status' => 'error', 'message' => 'Número de teléfono inválido']);
            exit();
        }

        // Email
        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $touristEmail)) {
            echo json_encode(['status' => 'error', 'message' => 'Formato de correo electrónico inválido']);
            exit();
        }

        // Validación de contraseña
        if ($touristPassword !== $confirmTouristPassword) {
            echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden']);
            exit();
        }

        // Encriptar la contraseña si son iguales
        $encryptedPassword = password_hash($touristPassword, PASSWORD_BCRYPT);

        $user = new User(0, $touristNickName, $encryptedPassword, true, "Turista", $touristName, $touristSurnames, $touristLegalIdentification, $touristPhone, $touristEmail);
        $touristBusiness = new touristBusiness();

        $result = $touristBusiness->insertTBUser($user);

        if ($result['status'] === 'success') {
            echo json_encode(['status' => 'success', 'message' => 'Cuenta creada correctamente']);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Fallo al agregar el Usuario: ' . $result['message']]);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos o inválidos']);
        exit();
    }
}

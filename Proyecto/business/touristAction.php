<?php
include_once './touristBusiness.php';
include_once '../domain/User.php';

if (isset($_POST['create'])) {
    $response = array();

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

        //Identificación
        $isValidId = false;
        if ($idType == 'CR') {
            $isValidId = preg_match('/^\d{9}$/', $touristLegalIdentification);
            if (!$isValidId) {
                $response['status'] = 'error';
                $response['message'] = 'Identificación de Costa Rica inválida';
                echo json_encode($response);
                exit();
            }
        } elseif ($idType == 'foreign') {
            $isValidId = preg_match('/^\d+$/', $touristLegalIdentification);
            if (!$isValidId) {
                header("Location: ../view/registerTouristView.php?error=invalidForeignId");
                exit();
            }
        }

        //Nombre
        if (!empty($touristName) && is_numeric($touristName)) {
            $response['status'] = 'error';
            $response['message'] = 'El nombre contiene caracteres inválidos';
            echo json_encode($response);
            exit();
        }

        //Apellido
        if (!empty($touristSurnames) && is_numeric($touristSurnames)) {
            $response['status'] = 'error';
            $response['message'] = 'Los apellidos contienen caracteres inválidos';
            echo json_encode($response);
            exit();
        }

        //Telefono
        if (!empty($touristPhone) && !preg_match('/^\d{8}$/', $touristPhone)) {
            $response['status'] = 'error';
            $response['message'] = 'Número de teléfono inválido';
            echo json_encode($response);
            exit();
        }

        //Email
        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $touristEmail)) {
            $response['status'] = 'error';
            $response['message'] = 'Formato de correo electrónico inválido';
            echo json_encode($response);
            exit();
        }

        // Validación de contraseña
        if ($touristPassword !== $confirmTouristPassword) {
            $response['status'] = 'error';
            $response['message'] = 'Las contraseñas no coinciden.';
            echo json_encode($response);
            exit();
        }

        // Encriptar la contraseña si son iguales
        $encryptedPassword = password_hash($touristPassword, PASSWORD_BCRYPT);
        
        $user = new User(0, $touristNickName, $encryptedPassword, true, "Turista", $touristName, $touristSurnames, $touristLegalIdentification, $touristPhone, $touristEmail);
        $touristBusiness = new touristBusiness();
        
        $result = $touristBusiness->insertTBUser($user);

        /*try {
            $result = $touristBusiness->insertTBUser($user);
        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['message'] = 'Excepción al insertar usuario: ' . $e->getMessage();
            echo json_encode($response);
            exit();
        }*/

        if ($result['status'] === 'success') {
            $response['status'] = 'success';
            $response['message'] = 'Usuario añadido correctamente';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Fallo al agregar el Usuario: ' . $result['message'];
        }
    }  else {
        $response['status'] = 'error';
        $response['message'] = 'Datos incompletos o inválidos';
    }

    echo json_encode($response);
    exit();  
}
<?php

include_once 'data.php';
include '../domain/Photo.php';

class PhotoData extends Data {

    public function insertMultiplePhotos($photoUrls) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $conn->set_charset('utf8');

        $queryGetLastId = "SELECT MAX(tbphotoid) AS idtbphoto FROM tbphoto";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $lastId = 0;

        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
        }

        $urls = array_slice(array_map('trim', explode(',', $photoUrls)), 0, 5);
        $indices = implode(',', array_keys($urls));
        $urlsString = implode(',', $urls);

        $queryInsert = "INSERT INTO tbphoto (tbphotoid, tbphotourl, tbphotoindex, tbphotostatus) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $statusDelete = true;
        $nextId = $lastId + 1;
        $stmt->bind_param("issi", $nextId, $urlsString, $indices, $statusDelete);

        $result = $stmt->execute();

        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }

        $stmt->close();
        mysqli_close($conn);

        return $result;
    }

    public function getAllTBPhotos() {
        // Establecer la conexión con la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        // Establecer el conjunto de caracteres a UTF-8
        $conn->set_charset('utf8');
    
        // Consulta para seleccionar todos los registros activos
        $query = "SELECT * FROM tbphoto WHERE tbphotostatus = 1;";
        $result = mysqli_query($conn, $query);
    
        // Array para almacenar las fotos
        $photos = [];
    
        // Procesar cada registro y crear un objeto Photo
        while ($row = mysqli_fetch_assoc($result)) {
            // Crear una instancia de Photo con los datos de la fila actual
            $currentPhoto = new Photo(
                $row['tbphotoid'],
                $row['tbphotourl'],
                $row['tbphotoindex'],
                $row['tbphotostatus']
            );
            
            // Agregar el objeto Photo al array de fotos
            array_push($photos, $currentPhoto);
        }
    
        // Cerrar la conexión
        mysqli_close($conn);
    
        // Retornar el array de fotos
        return $photos;
    }
    
    public function updateTBPhoto($photoId, $imageIndex, $newUrl, $existingUrls) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        // Escapar la URL para evitar inyecciones SQL
        $newUrl = mysqli_real_escape_string($conn, $newUrl);
    
        // Reconstruir el array de URLs con la nueva URL
        $existingUrls[$imageIndex] = $newUrl;
        $newUrlsString = implode(',', $existingUrls);
    
        // Actualizar el registro en la base de datos
        $queryUpdate = "UPDATE tbphoto SET tbphotourl = '$newUrlsString' WHERE tbphotoid = $photoId";
        $resultUpdate = mysqli_query($conn, $queryUpdate);
    
        if (!$resultUpdate) {
            die("Error en la actualización: " . mysqli_error($conn));
        }
    
        // Cierra la conexión
        mysqli_close($conn);
    
        return $resultUpdate;
    }
    
    
}

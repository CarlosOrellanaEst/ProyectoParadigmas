<?php

include_once 'data.php';
include_once '../domain/Photo.php';

class PhotoData extends Data {

    public function insertMultiplePhotos($photoUrls) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8');
    
        // Obtener el último ID insertado antes de la nueva inserción
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
        $nextId = $lastId + 1; // Incrementar el último ID para el nuevo registro
    
        $stmt->bind_param("issi", $nextId, $urlsString, $indices, $statusDelete); // index es 0 ya que solo hay un registro
        $result = $stmt->execute();
        $stmt->close();
        mysqli_close($conn);
    
        return $result ? $nextId : false;  // Devuelve el último ID insertado o false si falló
    }
    
    
    
   public function getAllTBPhotos() {
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn->set_charset('utf8');

    $query = "SELECT * FROM tbphoto WHERE tbphotostatus = 1";
    $result = mysqli_query($conn, $query);

    $photos = [];

    while ($row = mysqli_fetch_assoc($result)) {
        // Filtra las URLs inactivas
        $urls = array_filter(explode(',', $row['tbphotourl']), function($url) {
            return $url !== '5';
        });

        // Crear una instancia de Photo con las URLs filtradas
        $currentPhoto = new Photo(
            $row['tbphotoid'],
            implode(',', $urls), // Sólo URLs activas
            $row['tbphotoindex'],
            $row['tbphotostatus']
        );

        array_push($photos, $currentPhoto);
    }

    mysqli_close($conn);

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
    
    public function deleteTBPhoto($photoId, $imageIndex) {
        // Conectar a la base de datos
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        // Obtener las URLs existentes para la foto
        $queryGetUrls = "SELECT tbphotourl, tbphotoindex FROM tbphoto WHERE tbphotoid = ?";
        $stmtGetUrls = $conn->prepare($queryGetUrls);
        $stmtGetUrls->bind_param("i", $photoId);
        $stmtGetUrls->execute();
        $resultGetUrls = $stmtGetUrls->get_result();
    
        if ($resultGetUrls && $row = $resultGetUrls->fetch_assoc()) {
            $existingUrls = explode(',', $row['tbphotourl']);
            $existingIndexes = explode(',', $row['tbphotoindex']);
            
            if (isset($existingUrls[$imageIndex])) {
                // Eliminar la URL de la imagen especificada
                unset($existingUrls[$imageIndex]);
                unset($existingIndexes[$imageIndex]);
                
                // Reindexar los arrays para mantener los índices consecutivos
                $existingUrls = array_values($existingUrls);
                $existingIndexes = array_values($existingIndexes);
                
                // Contar la cantidad de imágenes restantes
                $imageCount = count($existingUrls);
                
                // Reconstruir los strings de URLs e índices
                $newUrlsString = implode(',', $existingUrls);
                $newIndexesString = implode(',', array_keys($existingIndexes));
                
                // Actualizar la URL y el número de índices en la base de datos
                $queryUpdate = "UPDATE tbphoto SET tbphotourl = ?, tbphotoindex = ? WHERE tbphotoid = ?";
                $stmtUpdate = $conn->prepare($queryUpdate);
                $stmtUpdate->bind_param("ssi", $newUrlsString, $newIndexesString, $photoId);
                $resultUpdate = $stmtUpdate->execute();
                
                $stmtGetUrls->close();
                $stmtUpdate->close();
                mysqli_close($conn);
                return $resultUpdate;
            } else {
                mysqli_close($conn);
                return false;
            }
        } else {
            mysqli_close($conn);
            return false;
        }
    }
    
    

}
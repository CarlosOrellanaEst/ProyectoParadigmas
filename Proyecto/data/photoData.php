<?php

include_once 'data.php';
include '../domain/Photo.php';

class photoData extends Data {

 // Prepared Statement
 public function insertTBPhoto($photo) {
    // Establece la conexión con la base de datos
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Establece el conjunto de caracteres a UTF-8
    $conn->set_charset('utf8');

    // Obtiene el último id de la tabla tbphoto
    $queryGetLastId = "SELECT MAX(tbphotoid) AS idtbphoto FROM tbphotoowner";
    $idCont = mysqli_query($conn, $queryGetLastId);

    // Verifica si la consulta falló
    if (!$idCont) {
        die("Error en la consulta SQL: " . mysqli_error($conn));
    }

    $nextId = 1;
    if ($row = mysqli_fetch_row($idCont)) {
        $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
        $nextId = $lastId + 1;
    }
    
    // Prepara la consulta de inserción
    $queryInsert = "INSERT INTO tbphotoowner (tbphotoid, tbphotourl, tbphotostatus) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($queryInsert);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error); 
    }

    // Obtén la URL de la foto y establece el estado como true
    $url = $photo->getUrlTBPhoto(); // Asegúrate de que getUrlTBPhoto devuelva la URL correcta
    $statusDelete = true;

    // Vincula los parámetros del statement
    $stmt->bind_param("isi", $nextId, $url, $statusDelete); // "isi": tipos de datos enteros y cadenas

    // Ejecuta la declaración
    $result = $stmt->execute();

    // Cierra la declaración y la conexión
    $stmt->close();
    mysqli_close($conn);

    return $result;
}
// lee todos
public function getAllTBPhotos() {
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn->set_charset('utf8');

    $query = "SELECT * FROM tbphotoowner WHERE tbphotostatus = 1;";
    $result = mysqli_query($conn, $query);

    $photos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $currentPhoto = new Photo($row['tbphotoid'], $row['tbphotourl'], $row['tbphotostatus']);
        array_push($photos, $currentPhoto);
    }

    mysqli_close($conn);
    return $photos;
}

public function updateTBPhoto($photo) {
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn->set_charset('utf8');

    // Obtén los valores
    $id = $photo->getIdTBPhoto();
    $newUrl = mysqli_real_escape_string($conn, $photo->getUrlTBPhoto());
    
    // Actualiza la consulta
    $query = "UPDATE tbphoto SET tbphotourl = '$newUrl' WHERE tbphotoid = $id";

    // Ejecuta la consulta
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error en la consulta de actualización: " . mysqli_error($conn));
    }

    mysqli_close($conn);

    return $result;
}


public function deleteTBPhoto($idPhoto) {
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    $conn->set_charset('utf8');

    $queryUpdate = "UPDATE tbphoto SET tbphotostatus = 0 WHERE tbphotoid = " . $idPhoto . ";";
    $result = mysqli_query($conn, $queryUpdate);

    if (!$result) {
        die("Error en la eliminación: " . mysqli_error($conn));
    }

    mysqli_close($conn);

    return $result;
}
 
} 

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
    $queryGetLastId = "SELECT MAX(tbphotoid) AS idtbphoto FROM tbphoto";
    $idCont = mysqli_query($conn, $queryGetLastId);
    $nextId = 1;

    if ($row = mysqli_fetch_row($idCont)) {
        $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
        $nextId = $lastId + 1;
    }
    
    // Prepara la consulta de inserción
    $queryInsert = "INSERT INTO tbphoto (tbphotoid, tbuphotourl, tbphotostatus) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($queryInsert);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Obtén la URL de la foto y establece el estado como true
    $url = $photo->setUrlTBPhoto(); // Asegúrate de que setUrlTBPhoto devuelva la URL correcta
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

   
} 

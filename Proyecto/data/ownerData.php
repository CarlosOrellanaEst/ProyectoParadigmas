<?php

include_once 'data.php';
include_once '../domain/Owner.php';

class ownerData extends Data {
   
    public function insertTBOwner($owner) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
        }
    
        $conn->set_charset('utf8');
    
        // Obtiene el último id
        $queryGetLastId = "SELECT MAX(tbownerid) AS idtbowner FROM tbowner";
        $idCont = mysqli_query($conn, $queryGetLastId);
        if ($idCont === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Failed to get last ID: ' . $conn->error];
        }
    
        $nextId = 1;
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }
        
        $name = $owner->getName();
        $surnames = $owner->getSurnames();
        $legalIdentification = $owner->getLegalIdentification();
        $phone = $owner->getPhone();
        $email = $owner->getEmail();
        $direction = $owner->getDirectionTBOwner();
        $photoUrl = $owner->getPhotoURLTBOwner();
        $statusDelete = true; 
        
        $existsEmail = $this->getTBOwnerByEmail($email);
        $existsPhone = $phone ? $this->getTBOwnerByPhone($phone): false;
        $existsLegalId = $this->getTBOwnerByLegalId($legalIdentification);
    
        // Validaciones de duplicado
        if ($existsEmail) {
            $isActive = $this->getTBOwnerExistsIsActive($existsEmail); // Obtener el estado de tbownerstatus
            if ($isActive == 1) {
                // Si el estado es 1, no permite registrar
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'El Email ya existe y está activo.'];
            } else {
                // Si el estado es 0, permite actualizar el registro existente
                $queryUpdate = "UPDATE tbowner SET tbownername = ?, tbownersurnames = ?, tbownerlegalidentification = ?, 
                                tbownerphone = ?, tbownerdirection = ?, tbownerphotourl = ?, tbownerstatus = 1 
                                WHERE tbownerid = ?";
                $stmt = $conn->prepare($queryUpdate);
                if ($stmt === false) {
                    mysqli_close($conn);
                    return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
                }
                $stmt->bind_param("ssssssi", $name, $surnames, $legalIdentification, $phone, $direction, $photoUrl, $existsEmail);
                $result = $stmt->execute();
                $stmt->close();
                mysqli_close($conn);

                if ($result) {
                    return ['status' => 'success', 'message' => 'Propietario actualizado correctamente.'];
                } else {
                    return ['status' => 'error', 'message' => 'Falló al actualizar el propietario: ' . $conn->error];
                }
            }
        } elseif ($existsPhone) {
            if ($this->getTBOwnerExistsIsActive($existsPhone)) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'El teléfono ya existe.'];
            } else {
                $queryUpdate = "UPDATE tbowner SET tbownername = ?, tbownersurnames = ?, tbownerlegalidentification = ?,
                tbowneremail = ?, tbownerdirection = ?, tbownerphotourl = ?, tbownerstatus = 1 WHERE tbownerid = ?";
                $stmt = $conn->prepare($queryUpdate);
                if ($stmt === false) {
                    mysqli_close($conn);
                    return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
                }
                $stmt->bind_param("ssssssi", $name, $surnames, $legalIdentification, $email, $direction, $photoUrl, $existsPhone);
                $result = $stmt->execute();
                $stmt->close();
                mysqli_close($conn);
    
                if ($result) {
                    return ['status' => 'success', 'message' => 'Propietario actualizado correctamente.'];
                } else {
                    return ['status' => 'error', 'message' => 'Falló al actualizar el propietario: ' . $conn->error];
                }
            }
        } elseif ($existsLegalId) {
            $isActive = $this->getTBOwnerExistsIsActive($existsLegalId); // Obtener el estado de tbownerstatus
            if ($isActive == 1) {
                // Si el estado es 1, no permite registrar
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'La cédula ya existe y está activa.'];
            } else {
                // Si el estado es 0, permite actualizar el registro existente
                $queryUpdate = "UPDATE tbowner SET tbownername = ?, tbownersurnames = ?, tbownerphone = ?, 
                                tbowneremail = ?, tbownerdirection = ?, tbownerphotourl = ?, tbownerstatus = 1 
                                WHERE tbownerid = ?";
                $stmt = $conn->prepare($queryUpdate);
                if ($stmt === false) {
                    mysqli_close($conn);
                    return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
                }
                $stmt->bind_param("ssssssi", $name, $surnames, $phone, $email, $direction, $photoUrl, $existsLegalId);
                $result = $stmt->execute();
                $stmt->close();
                mysqli_close($conn);

                if ($result) {
                    return ['status' => 'success', 'message' => 'Propietario actualizado correctamente.'];
                } else {
                    return ['status' => 'error', 'message' => 'Falló al actualizar el propietario: ' . $conn->error];
                }
            }
        } else {
            // Inserción
            $queryInsert = "INSERT INTO tbowner (tbownerid, tbownername, tbownersurnames, tbownerlegalidentification, tbownerphone, tbowneremail, tbownerdirection, tbownerphotourl, tbownerstatus) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($queryInsert);
            if ($stmt === false) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
            }
            
            $stmt->bind_param("isssssssi", $nextId, $name, $surnames, $legalIdentification, $phone, $email, $direction, $photoUrl, $statusDelete);
            $result = $stmt->execute();
            $stmt->close();
            mysqli_close($conn);
    
            if ($result) {
                return ['status' => 'success', 'message' => 'Propietario añadido correctamente.'];
            } else {
                return ['status' => 'error', 'message' => 'Falló al agregar el propietario: ' . $conn->error];
            }
        }
    }
    /*public function insertTBOwner($owner) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            return ['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()];
        }
    
        $conn->set_charset('utf8');
    
        // Obtiene el último id
        $queryGetLastId = "SELECT MAX(tbownerid) AS idtbowner FROM tbowner";
        $idCont = mysqli_query($conn, $queryGetLastId);
        if ($idCont === false) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Failed to get last ID: ' . $conn->error];
        }
    
        $nextId = 1;
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }
        
        $name = $owner->getName();
        $surnames = $owner->getSurnames();
        $legalIdentification = $owner->getLegalIdentification();
        $phone = $owner->getPhone();
        $email = $owner->getEmail();
        $direction = $owner->getDirectionTBOwner();
        $photoUrl = $owner->getPhotoURLTBOwner();
        $statusDelete = true; 
        
        $existsEmail = $this->getTBOwnerByEmail($email);
        $existsPhone = $this->getTBOwnerByPhone($phone);
        $existsLegalId = $this->getTBOwnerByLegalId($legalIdentification);
    
        // Validaciones de duplicado
        if ($existsEmail) {
            if ($this->getTBOwnerExistsIsActive($existsEmail)) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'El Email ya existe.'];
            } else {
                $queryUpdate = "UPDATE tbowner SET tbownername = ?, tbownersurnames = ?, tbownerlegalidentification = ?,
                tbownerphone = ?, tbownerdirection = ?, tbownerphotourl = ?, tbownerstatus = 1 WHERE tbownerid = ?";
                $stmt = $conn->prepare($queryUpdate);
                if ($stmt === false) {
                    mysqli_close($conn);
                    return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
                }
                $stmt->bind_param("ssssssi", $name, $surnames, $legalIdentification, $phone, $direction, $photoUrl, $existsEmail);
                $result = $stmt->execute();
                $stmt->close();
                mysqli_close($conn);
    
                if ($result) {
                    return ['status' => 'success', 'message' => 'Propietario actualizado correctamente.'];
                } else {
                    return ['status' => 'error', 'message' => 'Falló al actualizar el propietario: ' . $conn->error];
                }
            }
        } elseif ($existsPhone) {
            if ($this->getTBOwnerExistsIsActive($existsPhone)) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'El teléfono ya existe.'];
            } else {
                $queryUpdate = "UPDATE tbowner SET tbownername = ?, tbownersurnames = ?, tbownerlegalidentification = ?,
                tbowneremail = ?, tbownerdirection = ?, tbownerphotourl = ?, tbownerstatus = 1 WHERE tbownerid = ?";
                $stmt = $conn->prepare($queryUpdate);
                if ($stmt === false) {
                    mysqli_close($conn);
                    return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
                }
                $stmt->bind_param("ssssssi", $name, $surnames, $legalIdentification, $email, $direction, $photoUrl, $existsPhone);
                $result = $stmt->execute();
                $stmt->close();
                mysqli_close($conn);
    
                if ($result) {
                    return ['status' => 'success', 'message' => 'Propietario actualizado correctamente.'];
                } else {
                    return ['status' => 'error', 'message' => 'Falló al actualizar el propietario: ' . $conn->error];
                }
            }
        } elseif ($existsLegalId) {
            if ($this->getTBOwnerExistsIsActive($existsLegalId)) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'La cédula ya existe.'];
            } else {
                $queryUpdate = "UPDATE tbowner SET tbownername = ?, tbownersurnames = ?, tbownerphone = ?,
                tbowneremail = ?, tbownerdirection = ?, tbownerphotourl = ?, tbownerstatus = 1 WHERE tbownerid = ?";
                $stmt = $conn->prepare($queryUpdate);
                if ($stmt === false) {
                    mysqli_close($conn);
                    return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
                }
                $stmt->bind_param("ssssssi", $name, $surnames, $phone, $email, $direction, $photoUrl, $existsLegalId);
                $result = $stmt->execute();
                $stmt->close();
                mysqli_close($conn);
    
                if ($result) {
                    return ['status' => 'success', 'message' => 'Propietario actualizado correctamente.'];
                } else {
                    return ['status' => 'error', 'message' => 'Falló al actualizar el propietario: ' . $conn->error];
                }
            }
        } else {
            // Inserción
            $queryInsert = "INSERT INTO tbowner (tbownerid, tbownername, tbownersurnames, tbownerlegalidentification, tbownerphone, tbowneremail, tbownerdirection, tbownerphotourl, tbownerstatus) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($queryInsert);
            if ($stmt === false) {
                mysqli_close($conn);
                return ['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error];
            }
            
            $stmt->bind_param("isssssssi", $nextId, $name, $surnames, $legalIdentification, $phone, $email, $direction, $photoUrl, $statusDelete);
            $result = $stmt->execute();
            $stmt->close();
            mysqli_close($conn);
    
            if ($result) {
                return ['status' => 'success', 'message' => 'Propietario añadido correctamente.'];
            } else {
                return ['status' => 'error', 'message' => 'Falló al agregar el propietario: ' . $conn->error];
            }
        }
    } */
       // lee todos
       public function getAllTBOwner() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbowner WHERE tbownerstatus = 1;";
        $result = mysqli_query($conn, $query);
    
        $owners = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $currentOwner = new Owner($row['tbownerid'],$row['tbownerdirection'], $row['tbownername'], $row['tbownersurnames'], $row['tbownerlegalidentification'], $row['tbownerphone'], $row['tbowneremail'], $row['tbownerphotourl'], $row['tbownerstatus']);
            array_push($owners, $currentOwner);
        }
    
        mysqli_close($conn);
        return $owners;
    } 

    public function getTBOwner($idTBOwner) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbowner WHERE tbownerid = $idTBOwner";
        $result = mysqli_query($conn, $query);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $ownerReturn = new Owner($row['tbownerid'], $row['tbownername'], $row['tbownersurnames'], $row['tbownerlegalidentification'], $row['tbownerphone'], $row['tbowneremail'], $row['tbownerdirection'], $row['tbownerphotourl'], $row['tbownerstatus']);
        } else {
            $ownerReturn = null;
        }
    
        mysqli_close($conn);
        return $ownerReturn;
    } 

    public function updateTBOwner($owner) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
        
        $id = $owner->getIdTBOwner();
        $newName = mysqli_real_escape_string($conn, $owner->getName());
        $newSurnames = mysqli_real_escape_string($conn, $owner->getSurnames());
        $newLegalIdentification = mysqli_real_escape_string($conn, $owner->getLegalIdentification());
        $newPhone = mysqli_real_escape_string($conn, $owner->getPhone());
        $newEmail = mysqli_real_escape_string($conn, $owner->getEmail());
        $newDirection = mysqli_real_escape_string($conn, $owner->getDirectionTBOwner());
        $newURL = mysqli_real_escape_string($conn, $owner->getPhotoURLTBOwner());
    
        // Verificar duplicados
        $emailQuery = "SELECT * FROM tbowner WHERE tbowneremail = '$newEmail' AND tbownerid != $id";
        $emailResult = mysqli_query($conn, $emailQuery);
        $phoneQuery = "SELECT * FROM tbowner WHERE tbownerphone = '$newPhone' AND tbownerid != $id";
        $phoneResult = mysqli_query($conn, $phoneQuery);
        $legalIdQuery = "SELECT * FROM tbowner WHERE tbownerlegalidentification = '$newLegalIdentification' AND tbownerid != $id";
        $legalIdResult = mysqli_query($conn, $legalIdQuery);
    
        if (mysqli_num_rows($emailResult) > 0) {
            $result = "Email";
        } else if (mysqli_num_rows($phoneResult) > 0) {
            $result = "Phone";
        } else if (mysqli_num_rows($legalIdResult) > 0) {
            $result = "LegalId";
        } else {
            $query = "UPDATE tbowner SET tbownername = '$newName', tbownersurnames = '$newSurnames', tbownerlegalidentification = '$newLegalIdentification', tbownerphone = '$newPhone', tbowneremail = '$newEmail', tbownerdirection = '$newDirection', tbownerphotourl = '$newURL' WHERE tbownerid = $id";
            $result = mysqli_query($conn, $query) ? 1 : "dbError";
        }
    
        mysqli_close($conn);
        return $result;
    }
    

    public function deleteTBOwner($idOwner) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        $conn->set_charset('utf8');

        $queryUpdate = "UPDATE tbowner SET tbownerstatus = 0 where tbownerid=" . $idOwner . ";";
        $result = mysqli_query($conn, $queryUpdate);
        mysqli_close($conn);

        return $result;
    } 

       public function getTBOwnerByEmail($ownerEmail) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbowner WHERE tbowneremail= '$ownerEmail'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $ownerReturn = true : $ownerReturn = false;
    
        mysqli_close($conn);
        return $ownerReturn;
    } 
    public function getTBOwnerByPhone($ownerPhone) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbowner WHERE tbownerphone= '$ownerPhone'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $ownerReturn = true : $ownerReturn = false;
    
        mysqli_close($conn);
        return $ownerReturn;
    } 

    public function getTBOwnerByLegalId($LegalId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbowner WHERE tbownerlegalidentification= '$LegalId'    ";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $ownerReturn = true : $ownerReturn = false;
    
        mysqli_close($conn);
        return $ownerReturn;
    } 

    public function getTBOwnerExistsIsActive($ownerId) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8');
    
        $query = "SELECT * FROM tbowner WHERE tbownerstatus=1 AND tbownerid = $ownerId";
        $result = mysqli_query($conn, $query);
        
        $row = mysqli_fetch_assoc($result);

        $row != null && count($row) > 0 ? $ownerReturn = true : $ownerReturn = false;
    
        mysqli_close($conn);
        return $ownerReturn;
    } 
}

<?php

include_once 'data.php';
include_once '../domain/Activity.php';

class activityData extends Data {

    public function insertActivity($activity) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8mb4');
    

        $tbactivityname = $activity->getNameTBActivity();
        $checkQuery = "SELECT COUNT(*) FROM tbactivity WHERE tbactivityname = ? AND tbactivitystatus = 1";
        $stmtCheck = $conn->prepare($checkQuery);
        if ($stmtCheck === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmtCheck->bind_param("s", $tbactivityname);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();
    

        if ($count > 0) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Ya existe una actividad con el mismo nombre y está activa.'];
        }
    
   
        $queryGetLastId = "SELECT MAX(tbactivityid) AS tbactivityid FROM tbactivity";
        $idCont = mysqli_query($conn, $queryGetLastId);
        $nextId = 1;
        if ($row = mysqli_fetch_row($idCont)) {
            $lastId = $row[0] !== null ? (int)trim($row[0]) : 0;
            $nextId = $lastId + 1;
        }
  
        $queryInsert = "INSERT INTO tbactivity (tbactivityid, tbactivityname, tbservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($queryInsert);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $tbactivityid = $nextId;
        $tbServicesid = $activity->getTbservicecompanyid();
        $tbactivityatributearray = implode(",", $activity->getAttributeTBActivityArray());
        $tbactivitydataarray = implode(",", $activity->getDataAttributeTBActivityArray());
        $imageUrls = is_array($activity->getTbactivityURL()) ? implode(',', $activity->getTbactivityURL()) : $activity->getTbactivityURL();
        $tbactivitystatus = $activity->getStatusTBActivity();
    
        $stmt->bind_param("isisssi", $tbactivityid, $tbactivityname, $tbServicesid, $tbactivityatributearray, $tbactivitydataarray, $imageUrls, $tbactivitystatus);
        $result = $stmt->execute();
    
        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }
    
    
    public function getAllActivities() {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');
    
        $query = "SELECT * FROM tbactivity WHERE tbactivitystatus = 1;";
        $result = mysqli_query($conn, $query);
  
        $activities = array();
    
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
           
            $attributeArray = explode(',', $row['tbactivityatributearray']);
            $dataArray = explode(',', $row['tbactivitydataarray']);
    
            
            if (count($attributeArray) !== count($dataArray)) {
               
                continue;
            }
    

            $activity = new Activity(
                $row['tbactivityid'],
                $row['tbactivityname'],
                $attributeArray, 
                $dataArray,       
                $row['tbactivityurl'],
                $row['tbactivitystatus']
            );
            $photoUrls = explode(',', $row['tbactivityurl']);
            $activity->setTbactivityURL(array_map('trim', $photoUrls)); 
    
            $activities[] = $activity;
        }
    
        mysqli_close($conn);
    
        return $activities;
    }
    
    

    public function deleteActivity($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');

        $query = "UPDATE tbactivity SET tbactivitystatus=0 WHERE tbactivityid=?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $id);

        $result = $stmt->execute();

        $stmt->close();
        mysqli_close($conn);

        return $result;
    }

    public function updateActivity($activity) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $conn->set_charset('utf8mb4');
    
   
        $tbactivityname = $activity->getNameTBActivity();
        $tbactivityid = $activity->getIdTBActivity();
    
        $checkQuery = "SELECT COUNT(*) FROM tbactivity WHERE tbactivityname = ? AND tbactivityid != ? AND tbactivitystatus = 1";
        $stmtCheck = $conn->prepare($checkQuery);
        if ($stmtCheck === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmtCheck->bind_param("si", $tbactivityname, $tbactivityid);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();
    
 
        if ($count > 0) {
            mysqli_close($conn);
            return ['status' => 'error', 'message' => 'Ya existe una actividad con el mismo nombre y está activa.'];
        }
   
        $queryUpdate = "UPDATE tbactivity
                        SET tbactivityname = ?, tbservicecompanyid = ?, tbactivityatributearray = ?, tbactivitydataarray = ?, tbactivityurl = ?, tbactivitystatus = ?
                        WHERE tbactivityid = ?";
        $stmt = $conn->prepare($queryUpdate);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $tbServicesid = $activity->getTbservicecompanyid();
        $tbactivityatributearray = is_array($activity->getAttributeTBActivityArray()) ? implode(",", $activity->getAttributeTBActivityArray()) : '';
        $tbactivitydataarray = is_array($activity->getDataAttributeTBActivityArray()) ? implode(",", $activity->getDataAttributeTBActivityArray()) : '';
        $imageUrls = is_array($activity->getTbactivityURL()) ? implode(',', $activity->getTbactivityURL()) : $activity->getTbactivityURL();
        $tbactivitystatus = $activity->getStatusTBActivity();
   
        $stmt->bind_param("sisssii", $tbactivityname, $tbServicesid, $tbactivityatributearray, $tbactivitydataarray, $imageUrls, $tbactivitystatus, $tbactivityid);
        $result = $stmt->execute();
    
        if (!$result) {
            echo "Execute failed: " . $stmt->error;
        }
    
        $stmt->close();
        mysqli_close($conn);
    
        return $result;
    }
    
    
    
    public function getActivityById($id) {
        $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $conn->set_charset('utf8mb4');
   
        $query = "SELECT tbactivityid, tbactivityname, tbservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus 
                  FROM tbactivity 
                  WHERE tbactivityid = ? AND tbactivitystatus = 1";
    
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
    
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        $stmt->bind_result($tbactivityid, $tbactivityname, $tbservicecompanyid, $tbactivityatributearray, $tbactivitydataarray, $tbactivityurl, $tbactivitystatus);
    
    
        $activity = null;
    
        if ($stmt->fetch()) {

            $attributeArray = explode(',', $tbactivityatributearray);
            $dataArray = explode(',', $tbactivitydataarray);
            $urlArray = explode(',', $tbactivityurl);
    
            if (count($attributeArray) === count($dataArray)) {
                $activity = new Activity(
                    $tbactivityid, 
                    $tbactivityname, 
                    $tbservicecompanyid, 
                    $attributeArray, 
                    $dataArray, 
                    $urlArray, 
                    $tbactivitystatus
                );
            }
        }

        $stmt->close();
        mysqli_close($conn);
    

        return $activity;
    }
    

public function getActivityByName($activityName) {

    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn->set_charset('utf8mb4');


    $query = "SELECT tbactivityid, tbactivityname, tbservicecompanyid, tbactivityatributearray, tbactivitydataarray, tbactivityurl, tbactivitystatus 
              FROM tbactivity 
              WHERE tbactivityname = ?";


    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }


    $stmt->bind_param("s", $activityName);
    $stmt->execute();


    $stmt->bind_result($tbactivityid, $tbactivityname, $tbservicecompanyid, $tbactivityatributearray, $tbactivitydataarray, $tbactivityurl, $tbactivitystatus);

    $activity = null;


    if ($stmt->fetch()) {

        $activity = new Activity($tbactivityid, $tbactivityname, $tbservicecompanyid, $tbactivityatributearray, $tbactivitydataarray, $tbactivityurl, $tbactivitystatus);
    }

    $stmt->close();
    mysqli_close($conn);

    return $activity;
}

public function removeImageFromActivity($activityId, $newImageUrls){
  
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn->set_charset('utf8mb4');

    if (is_array($newImageUrls)) {
        $newImageUrlsString = implode(',', $newImageUrls);
    } else {
     
        $newImageUrlsString = $newImageUrls;
    }

   
    $query = "UPDATE tbactivity SET tbactivityurl = ? WHERE tbactivityid = ?";

 
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("si", $newImageUrlsString, $activityId);
    $result = $stmt->execute();

  
    $stmt->close();
    mysqli_close($conn);


    return $result;
}


public function isImageInUse($imageToDelete){
  
    $conn = mysqli_connect($this->server, $this->user, $this->password, $this->db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn->set_charset('utf8mb4');

    
    $query = "SELECT COUNT(*) FROM tbactivity WHERE tbactivityurl LIKE ?";

    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $imageToDelete);
    $stmt->execute();


    $stmt->bind_result($count);


    $stmt->fetch();


    $stmt->close();
    mysqli_close($conn);

    
    return $count > 0;
}

}

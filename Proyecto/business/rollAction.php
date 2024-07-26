<?php

include './rollBusiness.php';

if (isset($_POST['create'])) {

    if (isset($_POST['rollName']) && isset($_POST['rollDescription'])) {
            
        $name = $_POST['rollName'];
        $description = $_POST['rollDescription'];

        if (strlen($name) > 0 && strlen($description) > 0 ) {
            if (!is_numeric($name) && !is_numeric($description)) {
                $roll = new Roll(0, $name, $description);
                $rollBusiness = new RollBusiness();
                $result = $rollBusiness -> insertTBRoll($roll);

                if ($result == 1) {
                    header("location: ../view/rollView.php?success=inserted");
                } else {
                    header("location: ../view/rollView.php?error=dbError");
                }
            } else {
                header("location: ../view/rollView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/rollView.php?error=emptyField");
        }
    } else {
        header("location: ../view/rollView.php?error=error");
    }
}

if (isset($_POST['update'])) { 

    if (isset($_POST['rollName']) && isset($_POST['rollDescription']) && isset($_POST['rollID'])) {
            
        $name = $_POST['rollName'];
        $description = $_POST['rollDescription'];
        $id = $_POST['rollID'];

        if (strlen($name) > 0 && strlen($description) > 0 ) {
            if (!is_numeric($name) && !is_numeric($description) && is_numeric($id)) {
                $roll = new Roll($id, $name, $description);
                $rollBusiness = new RollBusiness();
                $result = $rollBusiness ->updateTBRoll($roll);

                if ($result == 1) {
                    header("location: ../view/rollView.php?success=inserted");
                } else {
                    header("location: ../view/rollView.php?error=dbError");
                }
            } else {
                header("location: ../view/rollView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/rollView.php?error=emptyField");
        }
    } else {
        header("location: ../view/rollView.php?error=error");
    }
}

if (isset($_POST['delete'])) { 

    if (isset($_POST['rollID'])) {
        $id = $_POST['rollID'];
        $rollBusiness = new RollBusiness();
        $result = $rollBusiness ->deleteTBRoll($id);

        if ($result == 1) {
            header("location: ../view/rollView.php?success=inserted");
        } else {
            header("location: ../view/rollView.php?error=dbError");
        }
    } else {
        header("location: ../view/rollView.php?error=emptyField");
    }
} else {
    header("location: ../view/rollView.php?error=error");
}


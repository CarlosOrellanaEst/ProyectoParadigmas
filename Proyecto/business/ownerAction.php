<?php

include './OwnerBusiness.php';

if (isset($_POST['create'])) {
    if (isset($_POST['ownerName']) && isset($_POST['ownerSurnames']) && isset($_POST['ownerLegalIdentification']) && isset($_POST['ownerPhone']) && isset($_POST['ownerEmail']) && isset($_POST['ownerDirection'] )) {
      
        $name = $_POST['ownerName'];
        $surnames = $_POST['ownerSurnames'];
        $legalIdentification = $_POST['ownerLegalIdentification'];
        $phone = $_POST['ownerPhone'];
        $email = $_POST['ownerEmail'];
        $direction = $_POST['ownerDirection'];

        if (strlen($name) > 0) {
            if (!is_numeric($name) && !is_numeric($surnames)  && ctype_alnum($legalIdentification) && ctype_alnum($phone) && preg_match('/^[\s\S]*$/', $email)) {
                
                $owner = new Owner(0, $direction, $name, $surnames, $legalIdentification, $phone, $email, 1);
                $ownerBusiness = new OwnerBusiness();

                $result = $ownerBusiness->insertTBOwner($owner);

                if ($result == 1) {
                    header("location: ../view/ownerView.php?success=inserted");
                    exit();
                } else if ($result == null) {
                    header("location: ../view/ownerView.php?error=alreadyexists");
                    exit();
                } else {
                    header("location: ../view/ownerView.php?error=dbError");
                    exit();
                }
            } else {
                header("location: ../view/ownerView.php?error=numberFormat");
                exit();
            }
        } else {
            header("location: ../view/ownerView.php?error=emptyField");
            exit();
        }
    } else {
        header("location: ../view/ownerView.php?error=error");
        exit();
    }
}


if (isset($_POST['update'])) {
    if (isset($_POST['ownerName']) && isset($_POST['ownerSurnames']) && isset($_POST['ownerLegalIdentification']) && isset($_POST['ownerPhone']) && isset($_POST['ownerEmail']) && isset($_POST['ownerDirection'])  && isset($_POST['ownerID'])) {
        $name = $_POST['ownerName'];
        $surnames = $_POST['ownerSurnames'];
        $legalIdentification = $_POST['ownerLegalIdentification'];
        $phone = $_POST['ownerPhone'];
        $email = $_POST['ownerEmail'];
        $direction = $_POST['ownerDirection'];
        $id = $_POST['ownerID'];

        if (strlen($name) > 0) {
            if (!is_numeric($name) && !is_numeric($surnames) && ctype_alnum($legalIdentification) && ctype_alnum($phone) && preg_match('/^[\s\S]*$/', $email) && ctype_alnum($direction) && is_numeric($id)) {
                $owner = new Owner($id, $direction, $name, $surnames, $legalIdentification, $phone, $email, 1);
                $ownerBusiness = new OwnerBusiness();
                $result = $ownerBusiness->updateTBOwner($owner);

                if ($result == 1) {
                    header("location: ../view/ownerView.php?success=updated");
                    exit();
                } else {
                    header("location: ../view/ownerView.php?error=dbError");
                    exit();
                }
            } else {
                header("location: ../view/ownerView.php?error=numberFormat");
                exit();
            }
        } else {
            header("location: ../view/ownerView.php?error=emptyField");
            exit();
        }
    } else {
        header("location: ../view/ownerView.php?error=error");
        exit();
    }
}

if (isset($_POST['delete'])) { 

    if (isset($_POST['ownerID'])) {
        $id = $_POST['ownerID'];
        $ownerBusiness = new OwnerBusiness();
        $result = $ownerBusiness ->deleteTBOwner($id);

        if ($result == 1) {
            header("location: ../view/ownerView.php?success=deleted");
        } else {
            header("location: ../view/ownerView.php?error=dbError");
        }
    } else {
        header("location: ../view/ownerView.php?error=emptyField");
    }
} else {
    header("location: ../view/ownerView.php?error=error");
}

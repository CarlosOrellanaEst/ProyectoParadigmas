<?php

include './bullBusiness.php';

if (isset($_POST['update'])) {

    if (isset($_POST['ranch']) && isset($_POST['code']) && isset($_POST['idBull']) && isset($_POST['name']) && isset($_POST['commercialcase'])
            && isset($_POST['buydate']) && isset($_POST['strawsquantity']) && isset($_POST['trawsprice'])) {
            
        $bull = $_POST['idBull'];
        $ranch = $_POST['ranch'];
        $code = $_POST['code'];
        $name = $_POST['name'];
        $commercialcase = $_POST['commercialcase'];
        $buydate = $_POST['buydate'];
        $strawsquantity = $_POST['strawsquantity'];
        $strawsprice = $_POST['strawsprice'];
        

        if (strlen($name) > 0 && strlen($bull) > 0 && strlen($code) > 0 && strlen($ranch) > 0 
                && strlen($commercialcase) > 0 && strlen($buydate) > 0 
                && strlen($strawsquantity) > 0 && strlen($strawsprice) > 0) {
            if (!is_numeric($bullName)) {
                $bull = new Bull($bull, $code, $ranch, $name, $commercialcase, $buydate, $strawsquantity, $strawsprice);

                $bullBusiness = new BullBusiness();

                $result = $bullBusiness->updateTBBull($bull);
                if ($result == 1) {
                    header("location: ../view/bullView.php?success=updated");
                } else {
                    //echo $idSickness." - ".$bullName;
                    header("location: ../view/bullView.php?error=dbError");
                }
            } else {
                header("location: ../view/bullView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/bullView.php?error=emptyField");
        }
    } else {
        header("location: ../view/bullView.php?error=error");
    }
} else if (isset($_POST['delete'])) {

    if (isset($_POST['idBull'])) {

        $idBull = $_POST['idBull'];

        $bullBusiness = new BullBusiness();
        $result = $bullBusiness->deleteTBBull($idBull);

        if ($result == 1) {
            header("location: ../view/bullView.php?success=deleted");
        } else {
            header("location: ../view/bullView.php?error=dbError");
        }
    } else {
        header("location: ../view/bullView.php?error=error");
    }
} else if (isset($_POST['create'])) {

    if (isset($_POST['ranch']) && isset($_POST['code']) && isset($_POST['name']) && isset($_POST['commercialcase'])
            && isset($_POST['buydate']) && isset($_POST['strawsquantity']) && isset($_POST['trawsprice'])) {
            
        $ranch = $_POST['ranch'];
        $code = $_POST['code'];
        $name = $_POST['name'];
        $commercialcase = $_POST['commercialcase'];
        $buydate = $_POST['buydate'];
        $strawsquantity = $_POST['strawsquantity'];
        $strawsprice = $_POST['strawsprice'];
        

        if (strlen($name) > 0 && strlen($code) > 0 && strlen($ranch) > 0 
                && strlen($commercialcase) > 0 && strlen($buydate) > 0 
                && strlen($strawsquantity) > 0 && strlen($strawsprice) > 0) {
            if (!is_numeric($bullName)) {
                $bull = new Bull(0, $code, $ranch, $name, $commercialcase, $buydate, $strawsquantity, $strawsprice);

                $bullBusiness = new BullBusiness();

                $result = $bullBusiness->insertTBBull($bull);

                if ($result == 1) {
                    header("location: ../view/bullView.php?success=inserted");
                } else {
                    header("location: ../view/bullView.php?error=dbError");
                }
            } else {
                header("location: ../view/bullView.php?error=numberFormat");
            }
        } else {
            header("location: ../view/bullView.php?error=emptyField");
        }
    } else {
        header("location: ../view/bullView.php?error=error");
    }
}
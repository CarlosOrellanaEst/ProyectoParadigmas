<?php

include_once './touristCompanyTypeBusiness.php';
include_once '../domain/TouristCompanyType.php';

if (isset($_POST['create'])) {
    if (isset($_POST['name']) && isset($_POST['description'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];

        // trim()
        if (strlen(trim($name)) > 0) {
            if (!is_numeric($name) && !is_numeric($description)) {
                $companyType = new TouristCompanyType(0, $name, $description);
                
                $companyTypeBusiness = new touristCompanyTypeBusiness();

                $result = $companyTypeBusiness->insert($companyType);

                //echo $result;

                if ($result == 1) {
                    header("location: ../view/touristCompanyTypeView.php?success=inserted");
                    exit();
                } else if ($result == null) {
                    header("location: ../view/touristCompanyTypeView.php?error=alreadyexists");
                    exit();
                } else {
                    header("location: ../view/touristCompanyTypeView.php?error=dbError");
                    exit();
                }
            } else {
                header("location: ../view/touristCompanyTypeView.php?error=numberFormat");
                exit();
            }
        } else {
            header("location: ../view/touristCompanyTypeView.php?error=emptyField");
            exit();
        }
    } else {
        header("location: ../view/touristCompanyTypeView.php?error=error");
        exit();
    }
}

if (isset($_POST['delete'])) { 
    if (isset($_POST['tbtouristcompanytypeid'])) {
        $id = $_POST['tbtouristcompanytypeid'];
        $companyTypeBusiness = new touristCompanyTypeBusiness();
        $result = $companyTypeBusiness ->delete($id);

        if ($result == 1) {
            header("location: ../view/touristCompanyTypeView.php?success=deleted");
        } else {
            header("location: ../view/touristCompanyTypeView.php?error=dbError");
        }
    } else {
        header("location: ../view/touristCompanyTypeView.php?error=emptyField");
    }
} 

if (isset($_POST['update'])) {
    if (isset($_POST['name']) && isset($_POST['description'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $id = $_POST['tbtouristcompanytypeid'];

        if (strlen($name) > 0) {
            if (!is_numeric($name) && !is_numeric($description) && is_numeric($id)) {
                $companyType = new TouristCompanyType($id, $name, $description);
                $companyTypeBusiness = new touristCompanyTypeBusiness();
                $result = $companyTypeBusiness->update($companyType);

                if ($result == 1) {
                    header("location: ../view/touristCompanyTypeView.php?success=updated");
                    exit();
                } else {
                    header("location: ../view/touristCompanyTypeView.php?error=dbError");
                    exit();
                }
            } else {
                header("location: ../view/touristCompanyTypeView.php?error=numberFormat");
                exit();
            }
        } else {
            header("location: ../view/touristCompanyTypeView.php?error=emptyField");
            exit();
        }
    } else {
        header("location: ../view/touristCompanyTypeView.php?error=error");
        exit();
    }
}
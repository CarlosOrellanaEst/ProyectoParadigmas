<?php

include_once '../data/touristCompanyBusiness.php';
include_once '../domain/TouristCompany.php';

if(isset($_POST['create'])){
    if(isset($_POST['legalName'])){
        if(isset($_POST['magicName'])){
            if(isset($_POST['owner'])){
                if(isset($_POST['companyType'])){
                    if(isset($_POST['status'])){
                        $legalName = $_POST['legalName'];
                        $magicName = $_POST['magicName'];
                        $owner = $_POST['owner'];
                        $companyType = $_POST['companyType'];
                        $status = $_POST['status'];

                        if(strlen(trim($legalName)) > 0 && strlen(trim($magicName)) > 0 && strlen(trim($owner)) > 0 && $companyType instanceof CompanyType && strlen(trim($status)) > 0){
                            if(!is_numeric($legalName) && !is_numeric($magicName) && !is_numeric($owner) && is_numeric($status)){
                                $touristCompany = new TouristCompany(0, $legalName, $magicName, $owner, $companyType, $status);

                                $touristCompanyBusiness = new TouristCompanyBusiness();

                                $result = $touristCompanyBusiness->insert($touristCompany);

                                if($result == 1){
                                    header("location: ../view/touristCompanyView.php?success=inserted");
                                    exit();
                                }else if($result == null){
                                    header("location: ../view/touristCompanyView.php?error=alreadyexists");
                                    exit();
                                }else{
                                    header("location: ../view/touristCompanyView.php?error=dbError");
                                    exit();
                                }
                            }else{
                                header("location: ../view/touristCompanyView.php?error=numberFormat");
                                exit();
                            }
                        }else{
                            header("location: ../view/touristCompanyView.php?error=emptyField");
                            exit();
                        }
                    }else{
                        header("location: ../view/touristCompanyView.php?error=error");
                        exit();
                    }
                }else{
                    header("location: ../view/touristCompanyView.php?error=errorcompanyType");
                    exit();
                }
            }else{
                header("location: ../view/touristCompanyView.php?error=errorInOwner");
                exit();
            }
        }else{
            header("location: ../view/touristCompanyView.php?error=errorInMagicName");
            exit();
        }
    }else{
        header("location: ../view/touristCompanyView.php?error=errorInLegalName");
        exit();
    }
}



?>

<?php

include_once '../business/touristCompanyBusiness.php';
include_once '../domain/TouristCompany.php';
include_once '../domain/owner.php'; // Asegúrate de incluir el archivo correcto para la clase Owner
include_once '../domain/TouristCompanyType.php'; // Asegúrate de incluir el archivo correcto para la clase CompanyType
include_once '../business/OwnerBusiness.php'; // Asegúrate de incluir el archivo correcto para la clase OwnerBusiness
include_once '../business/touristCompanyTypeBusiness.php'; // Asegúrate de incluir el archivo correcto para la clase touristCompanyTypeBusiness


if(isset($_POST['create'])){
    if(isset($_POST['legalName'])){
        if(isset($_POST['magicName'])){
            if(isset($_POST['ownerId'])){ // Usa 'ownerId' como en el formulario
                if(isset($_POST['companyType'])){
                    if(isset($_POST['status'])){
                        $legalName = $_POST['legalName'];
                        $magicName = $_POST['magicName'];
                        $ownerId = $_POST['ownerId']; // Usa 'ownerId'
                        $companyTypeId = $_POST['companyType']; // Usa 'companyType'
                        $status = $_POST['status'];

                        // Validación de campos
                        if(strlen(trim($legalName)) > 0 && strlen(trim($magicName)) > 0 && is_numeric($ownerId)>0  && is_numeric($companyTypeId)>0 && strlen(trim($status)) >= 0){
                            if(!is_numeric($legalName) && !is_numeric($magicName) && is_numeric($status)){
                                // Aquí deberías obtener instancias de las clases Owner y CompanyType usando los IDs
                                $ownerBusiness = new OwnerBusiness();
                                $owner = $ownerBusiness->getTBOwner($ownerId); // Método para obtener el objeto Owner

                                $touristCompanyTypeBusiness = new touristCompanyTypeBusiness();
                                $companyType = $touristCompanyTypeBusiness->getById($companyTypeId); // Método para obtener el objeto CompanyType

                                if($owner && $companyType) {
                                    $touristCompany = new TouristCompany(0, $legalName, $magicName, $ownerId, $companyTypeId, $status);
                                    $touristCompanyBusiness = new touristCompanyBusiness();
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
                                } else {
                                    header("location: ../view/touristCompanyView.php?error=invalidOwnerOrCompanyType");
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
                        header("location: ../view/touristCompanyView.php?error=errorsInStatus");
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

if (isset($_POST['update'])) {
    if (isset($_POST['ownerId']) && isset($_POST['tbtouristcompanyid']) && isset($_POST['magicName']) && isset($_POST['ownerId']) && isset($_POST['companyType']) && isset($_POST['status'])) {
        
        $id = $_POST['id'];
        $tbtouristcompanyid = $_POST['tbtouristcompanyid'];
        $magicName = $_POST['magicName'];
        $ownerId = $_POST['ownerId'];
        $companyTypeId = $_POST['companyType'];
        $status = $_POST['status'];
        
        // Validación de campos
        if (strlen(trim($tbtouristcompanyid)) > 0 && strlen(trim($magicName)) > 0 && is_numeric($ownerId) && is_numeric($companyTypeId) && is_numeric($status)) {
            if (!is_numeric($tbtouristcompanyid) && !is_numeric($magicName)) {
                $ownerBusiness = new OwnerBusiness();
                $owner = $ownerBusiness->getTBOwner($ownerId);

                $touristCompanyTypeBusiness = new touristCompanyTypeBusiness();
                $companyType = $touristCompanyTypeBusiness->getById($companyTypeId);

                if ($ownerId && $companyType) {
                    $touristCompanyBusiness = new touristCompanyBusiness();
                    $touristCompany = new TouristCompany($id, $tbtouristcompanyid, $magicName, $ownerId, $companyTypeId, $status);
                    $result = $touristCompanyBusiness->update($touristCompany);

                    if ($result == 1) {
                        header("location: ../view/touristCompanyView.php?success=updated");
                        exit();
                    } else {
                        header("location: ../view/touristCompanyView.php?error=updateFailed");
                        exit();
                    }
                } else {
                    header("location: ../view/touristCompanyView.php?error=invalidOwnerOrCompanyType");
                    exit();
                }
            } else {
                header("location: ../view/touristCompanyView.php?error=numberFormat");
                exit();
            }
        } else {
            header("location: ../view/touristCompanyView.php?error=emptyField");
            exit();
        }
    } else {
        header("location: ../view/touristCompanyView.php?error=missingFields");
        exit();
    }
}

if (isset($_POST['delete'])) {
    if (isset($_POST['id'])) {
        
        $id = $_POST['id'];
        
        // Validación de id
        if (is_numeric($id)) {
            $touristCompanyBusiness = new touristCompanyBusiness();
            $result = $touristCompanyBusiness->delete($id);

            if ($result == 1) {
                header("location: ../view/touristCompanyView.php?success=deleted");
                exit();
            } else {
                header("location: ../view/touristCompanyView.php?error=deleteFailed");
                exit();
            }
        } else {
            header("location: ../view/touristCompanyView.php?error=invalidId");
            exit();
        }
    } else {
        header("location: ../view/touristCompanyView.php?error=missingId");
        exit();
    }
}
<?php
class TouristCompanyData {
    private $connection;

    public function __construct() {
        $this->connection = (new Data())->connect(); // Asumiendo que `Data` es la clase que maneja la conexión
    }

    public function insertTouristCompany($touristCompany) {
        $query = "INSERT INTO tourist_company (id, legal_name, magic_name, owner_id, type_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("issii", 
            $touristCompany->getId(), 
            $touristCompany->getLegalName(), 
            $touristCompany->getMagicName(), 
            $touristCompany->getOwner()->getId(), 
            $touristCompany->getCompanyType()->getId()
        );
        $stmt->execute();
        $stmt->close();
    }

    public function getAllTouristCompanies() {
        $query = "SELECT * FROM tourist_company";
        $result = $this->connection->query($query);
        $touristCompanies = array();

        while ($row = $result->fetch_assoc()) {
            $owner = (new OwnerData())->getTBOwner($row['owner_id']);
            $companyType = (new CompanyTypeData())->getCompanyTypeById($row['type_id']);
            $touristCompanies[] = new TouristCompany(
                $row['id'], 
                $row['legal_name'], 
                $row['magic_name'], 
                $owner, 
                $companyType
            );
        }

        return $touristCompanies;
    }

    public function updateTouristCompany($touristCompany) {
        $query = "UPDATE tourist_company SET legal_name = ?, magic_name = ?, owner_id = ?, type_id = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ssiii", 
            $touristCompany->getLegalName(), 
            $touristCompany->getMagicName(), 
            $touristCompany->getOwner()->getId(), 
            $touristCompany->getCompanyType()->getId(), 
            $touristCompany->getId()
        );
        $stmt->execute();
        $stmt->close();
    }

    public function deleteTouristCompany($id) {
        $query = "DELETE FROM tourist_company WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

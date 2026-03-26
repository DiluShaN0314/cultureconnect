<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/config/database.php';

class SME {
    private $conn;
    private $table_name = "smes";

    public $id;
    public $business_name;
    public $contact_email;
    public $phone;
    public $portfolio_link;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY business_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (business_name, contact_email, phone, portfolio_link) 
                  VALUES (:business_name, :contact_email, :phone, :portfolio_link)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":business_name", $this->business_name);
        $stmt->bindParam(":contact_email", $this->contact_email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":portfolio_link", $this->portfolio_link);

        return $stmt->execute();
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET business_name=:business_name, contact_email=:contact_email, phone=:phone, portfolio_link=:portfolio_link 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":business_name", $this->business_name);
        $stmt->bindParam(":contact_email", $this->contact_email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":portfolio_link", $this->portfolio_link);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
?>

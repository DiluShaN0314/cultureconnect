<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/config/database.php';

class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $sme_id;
    public $name;
    public $description;
    public $category;
    public $price_category;
    public $price;
    public $availability;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function readAll($sme_id = null, $search = null, $category = null, $price_limit = null) {
        $query = "SELECT p.*, s.business_name FROM " . $this->table_name . " p 
                  LEFT JOIN smes s ON p.sme_id = s.id WHERE 1=1";
        
        if ($sme_id) {
            $query .= " AND p.sme_id = :sme_id";
        }
        
        if ($search) {
            $query .= " AND (p.name LIKE :search OR p.description LIKE :search)";
        }
        
        if ($category) {
            $query .= " AND p.category = :category";
        }

        if ($price_limit) {
            $query .= " AND p.price < :price_limit";
        }
        
        $query .= " ORDER BY p.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($sme_id) {
            $stmt->bindParam(':sme_id', $sme_id);
        }
        
        if ($search) {
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm);
        }
        
        if ($category) {
            $stmt->bindParam(':category', $category);
        }

        if ($price_limit) {
            $stmt->bindParam(':price_limit', $price_limit);
        }
        
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (sme_id, name, description, category, price_category, price, availability) 
                  VALUES (:sme_id, :name, :description, :category, :price_category, :price, :availability)";
        
        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(":sme_id", $this->sme_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":price_category", $this->price_category);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":availability", $this->availability);

        if($stmt->execute()) {
            return true;
        }
        return false;
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
                  SET name=:name, description=:description, category=:category, price_category=:price_category, price=:price, sme_id=:sme_id 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":price_category", $this->price_category);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":sme_id", $this->sme_id);
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

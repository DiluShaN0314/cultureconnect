<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/config/database.php';

class Vote {
    private $conn;
    private $table_name = "votes";

    public $id;
    public $user_id;
    public $product_id;
    public $vote;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function readAll() {
        $query = "SELECT v.*, u.name as user_name, p.name as product_name 
                  FROM " . $this->table_name . " v 
                  JOIN users u ON v.user_id = u.id 
                  JOIN products p ON v.product_id = p.id 
                  ORDER BY v.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, product_id, vote) 
                  VALUES (:user_id, :product_id, :vote) 
                  ON DUPLICATE KEY UPDATE vote = :vote";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":vote", $this->vote);

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

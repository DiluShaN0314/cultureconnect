<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/config/database.php';

class Resident {
    public $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $age_group;
    public $gender;
    public $area_id;
    public $role;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function readAll() {
        $query = "SELECT u.*, a.name as area_name FROM " . $this->table_name . " u 
                  LEFT JOIN areas a ON u.area_id = a.id 
                  WHERE u.role = 'user' 
                  ORDER BY u.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, email, password, age_group, gender, area_id, role) 
                  VALUES (:name, :email, :password, :age_group, :gender, :area_id, 'user')";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":age_group", $this->age_group);
        $stmt->bindParam(":gender", $this->gender);
        $stmt->bindParam(":area_id", $this->area_id);

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
                  SET name=:name, email=:email, age_group=:age_group, gender=:gender, area_id=:area_id 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":age_group", $this->age_group);
        $stmt->bindParam(":gender", $this->gender);
        $stmt->bindParam(":area_id", $this->area_id);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function getDashboardStats($user_id) {
        $stats = [
            'area_name' => 'Not Set',
            'interests' => [],
            'total_votes' => 0,
            'yes_votes' => 0,
            'recent_activity' => []
        ];

        // 1. Get Area Name
        $query = "SELECT a.name FROM areas a JOIN users u ON u.area_id = a.id WHERE u.id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stats['area_name'] = $row['name'];
        }

        // 2. Get Interest Names
        $query = "SELECT i.name FROM interests i JOIN user_interests ui ON ui.interest_id = i.id WHERE ui.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $stats['interests'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // 3. Vote Counts
        $query = "SELECT COUNT(*) as total, SUM(CASE WHEN vote = 'Yes' THEN 1 ELSE 0 END) as yes_count FROM votes WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_votes'] = $row['total'];
        $stats['yes_votes'] = $row['yes_count'];

        // 4. Recent Activity
        $query = "SELECT v.created_at, p.name as activity, v.vote as status 
                  FROM votes v 
                  JOIN products p ON v.product_id = p.id 
                  WHERE v.user_id = :user_id 
                  ORDER BY v.created_at DESC LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $stats['recent_activity'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }
}
?>

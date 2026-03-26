<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/config/database.php';

class AdminController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function index() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: /cultureconnect/");
            exit;
        }

        $stats = [
            'residents' => $this->getCount('users', "role='user'"),
            'smes' => $this->getCount('smes'),
            'products' => $this->getCount('products'),
            'votes' => $this->getCount('votes'),
            'recent_residents' => $this->getRecent('users', "role='user'"),
            'recent_votes' => $this->getRecentVotes()
        ];

        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/admin/dashboard.php';
    }

    private $table_prefix = ""; // Optional

    private function getCount($table, $where = "1=1") {
        $query = "SELECT COUNT(*) as total FROM $table WHERE $where";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    private function getRecent($table, $where = "1=1", $limit = 5) {
        $query = "SELECT * FROM $table WHERE $where ORDER BY created_at DESC LIMIT $limit";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getRecentVotes($limit = 5) {
        $query = "SELECT v.*, u.name as user_name, p.name as product_name 
                  FROM votes v 
                  JOIN users u ON v.user_id = u.id 
                  JOIN products p ON v.product_id = p.id 
                  ORDER BY v.created_at DESC LIMIT $limit";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addUser() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: /cultureconnect/");
            exit;
        }

        $database = new Database();
        $conn = $database->getConnection();

        // Fetch areas
        $stmtAreas = $conn->query("SELECT id, name FROM areas ORDER BY name ASC");
        $areas = $stmtAreas->fetchAll(PDO::FETCH_ASSOC);

        // Fetch interests
        $stmtInterests = $conn->query("SELECT id, name FROM interests ORDER BY name ASC");
        $interests = $stmtInterests->fetchAll(PDO::FETCH_ASSOC);

        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/admin/add_user.php';
    }

    public function storeUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $database = new Database();
            $conn = $database->getConnection();

            $role = $_POST['role'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            try {
                $conn->beginTransaction();

                $sme_id = null;
                if ($role === 'sme') {
                    $business_name = $_POST['business_name'] ?? $name;
                    $phone = $_POST['phone'] ?? '';
                    $portfolio = $_POST['portfolio_link'] ?? '';
                    
                    $stmtSme = $conn->prepare("INSERT INTO smes (business_name, contact_email, phone, portfolio_link) VALUES (:bn, :ce, :ph, :pl)");
                    $stmtSme->execute([':bn' => $business_name, ':ce' => $email, ':ph' => $phone, ':pl' => $portfolio]);
                    $sme_id = $conn->lastInsertId();
                }

                $age_group = !empty($_POST['age_group']) ? $_POST['age_group'] : null;
                $gender = !empty($_POST['gender']) ? $_POST['gender'] : null;
                $area_id = !empty($_POST['area_id']) ? $_POST['area_id'] : null;

                $sql = "INSERT INTO users (name, email, password, role, age_group, gender, area_id, sme_id) 
                        VALUES (:name, :email, :password, :role, :age_group, :gender, :area_id, :sme_id)";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $password,
                    ':role' => $role,
                    ':age_group' => $age_group,
                    ':gender' => $gender,
                    ':area_id' => $area_id,
                    ':sme_id' => $sme_id
                ]);

                $user_id = $conn->lastInsertId();

                // Handle interests for Residents
                if ($role === 'user' && !empty($_POST['interests'])) {
                    $stmtInterest = $conn->prepare("INSERT INTO user_interests (user_id, interest_id) VALUES (:user_id, :interest_id)");
                    foreach ($_POST['interests'] as $interest_id) {
                        $stmtInterest->execute([':user_id' => $user_id, ':interest_id' => $interest_id]);
                    }
                }

                $conn->commit();
                header("Location: /cultureconnect/residents?success=1");
                exit();
            } catch (Exception $e) {
                $conn->rollBack();
                echo "Error adding user: " . $e->getMessage();
            }
        }
    }
}
?>

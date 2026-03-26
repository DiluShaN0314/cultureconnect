<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/models/Resident.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/models/Area.php';

class ResidentController {

    public function index() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: /cultureconnect/");
            exit;
        }
        $residentModel = new Resident();
        
        // Fetch residents with their interests joined as a string
        $query = "SELECT u.*, a.name as area_name, 
                  (SELECT GROUP_CONCAT(i.name SEPARATOR ', ') 
                   FROM user_interests ui 
                   JOIN interests i ON ui.interest_id = i.id 
                   WHERE ui.user_id = u.id) as interests
                  FROM users u 
                  LEFT JOIN areas a ON u.area_id = a.id 
                  WHERE u.role = 'user' 
                  ORDER BY u.created_at DESC";
        $stmt = $residentModel->conn->prepare($query);
        $stmt->execute();

        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/admin/users.php';
    }

    public function add() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: /cultureconnect/");
            exit;
        }
        $areaModel = new Area();
        $areas = $areaModel->readAll();
        
        // Fetch interests
        $database = new Database();
        $conn = $database->getConnection();
        $stmtInterests = $conn->query("SELECT * FROM interests ORDER BY name ASC");
        $interests = $stmtInterests->fetchAll(PDO::FETCH_ASSOC);

        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/residents/add_resident.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $residentModel = new Resident();
            $residentModel->name = $_POST['name'];
            $residentModel->email = $_POST['email'];
            $residentModel->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $residentModel->age_group = $_POST['age_group'];
            $residentModel->gender = $_POST['gender'];
            $residentModel->area_id = $_POST['area_id'];

            if ($residentModel->create()) {
                $user_id = $residentModel->conn->lastInsertId();
                
                // Save interests
                if (!empty($_POST['interests'])) {
                    $database = new Database();
                    $conn = $database->getConnection();
                    $stmtInterest = $conn->prepare("INSERT INTO user_interests (user_id, interest_id) VALUES (:user_id, :interest_id)");
                    foreach ($_POST['interests'] as $interest_id) {
                        $stmtInterest->bindParam(':user_id', $user_id);
                        $stmtInterest->bindParam(':interest_id', $interest_id);
                        $stmtInterest->execute();
                    }
                }

                header("Location: /cultureconnect/residents");
                exit();
            } else {
                echo "Error adding resident.";
            }
        }
    }

    public function edit() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: /cultureconnect/");
            exit;
        }
        if (isset($_GET['id'])) {
            $residentModel = new Resident();
            $residentModel->id = $_GET['id'];
            $stmt = $residentModel->readOne();
            $resident = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $areaModel = new Area();
            $areas = $areaModel->readAll();

            // Fetch all interests
            $database = new Database();
            $conn = $database->getConnection();
            $stmtInterests = $conn->query("SELECT * FROM interests ORDER BY name ASC");
            $interests = $stmtInterests->fetchAll(PDO::FETCH_ASSOC);

            // Fetch user's current interests
            $stmtUserInterests = $conn->prepare("SELECT interest_id FROM user_interests WHERE user_id = :user_id");
            $stmtUserInterests->bindParam(':user_id', $_GET['id']);
            $stmtUserInterests->execute();
            $user_interests = $stmtUserInterests->fetchAll(PDO::FETCH_COLUMN);
            
            require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/residents/edit_resident.php';
        } else {
            header("Location: /cultureconnect/residents");
            exit();
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $residentModel = new Resident();
            $residentModel->id = $_POST['id'];
            $residentModel->name = $_POST['name'];
            $residentModel->email = $_POST['email'];
            $residentModel->age_group = $_POST['age_group'];
            $residentModel->gender = $_POST['gender'];
            $residentModel->area_id = $_POST['area_id'];

            if ($residentModel->update()) {
                $user_id = $_POST['id'];
                
                // Sync interests (delete then re-insert)
                $database = new Database();
                $conn = $database->getConnection();
                $stmtDelete = $conn->prepare("DELETE FROM user_interests WHERE user_id = :user_id");
                $stmtDelete->bindParam(':user_id', $user_id);
                $stmtDelete->execute();

                if (!empty($_POST['interests'])) {
                    $stmtInsert = $conn->prepare("INSERT INTO user_interests (user_id, interest_id) VALUES (:user_id, :interest_id)");
                    foreach ($_POST['interests'] as $interest_id) {
                        $stmtInsert->bindParam(':user_id', $user_id);
                        $stmtInsert->bindParam(':interest_id', $interest_id);
                        $stmtInsert->execute();
                    }
                }

                header("Location: /cultureconnect/residents");
                exit();
            } else {
                echo "Error updating resident.";
            }
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $residentModel = new Resident();
            $residentModel->id = $_GET['id'];
            if ($residentModel->delete()) {
                header("Location: /cultureconnect/residents");
                exit();
            } else {
                echo "Error deleting resident.";
            }
        }
    }
}
?>

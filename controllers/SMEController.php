<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/models/SME.php';

class SMEController {

    public function dashboard() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'sme') {
            header("Location: /cultureconnect/");
            exit;
        }

        $database = new Database();
        $conn = $database->getConnection();
        $user_id = $_SESSION['user_id'];

        // Fetch SME info linked to this user
        $stmtSme = $conn->prepare("SELECT s.* FROM smes s JOIN users u ON u.sme_id = s.id WHERE u.id = :user_id");
        $stmtSme->execute([':user_id' => $user_id]);
        $sme = $stmtSme->fetch(PDO::FETCH_ASSOC);

        if (!$sme) {
            echo "Error: Linked SME profile not found.";
            exit();
        }

        // Fetch products count
        $stmtProducts = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE sme_id = :sme_id");
        $stmtProducts->execute([':sme_id' => $sme['id']]);
        $total_products = $stmtProducts->fetch(PDO::FETCH_ASSOC)['total'];

        // Fetch vote stats for this SME's products
        $stmtVotes = $conn->prepare("
            SELECT 
                COUNT(*) as total_votes,
                SUM(CASE WHEN vote = 'Yes' THEN 1 ELSE 0 END) as yes_votes
            FROM votes v
            JOIN products p ON v.product_id = p.id
            WHERE p.sme_id = :sme_id
        ");
        $stmtVotes->execute([':sme_id' => $sme['id']]);
        $vote_stats = $stmtVotes->fetch(PDO::FETCH_ASSOC);
        $total_votes = $vote_stats['total_votes'] ?? 0;
        $yes_votes = $vote_stats['yes_votes'] ?? 0;
        $approval_rate = $total_votes > 0 ? round(($yes_votes / $total_votes) * 100) : 0;

        // Fetch recent votes with product names
        $stmtRecent = $conn->prepare("
            SELECT v.*, p.name as product_name 
            FROM votes v
            JOIN products p ON v.product_id = p.id
            WHERE p.sme_id = :sme_id
            ORDER BY v.created_at DESC
            LIMIT 5
        ");
        $stmtRecent->execute([':sme_id' => $sme['id']]);
        $recent_votes = $stmtRecent->fetchAll(PDO::FETCH_ASSOC);

        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/smes/dashboard.php';
    }

    public function index() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: /cultureconnect/");
            exit;
        }
        $smeModel = new SME();
        $stmt = $smeModel->readAll();
        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/smes/list_sme.php';
    }

    public function add() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: /cultureconnect/");
            exit;
        }
        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/smes/add_sme.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            if (empty($_POST['business_name'])) $errors['business_name'] = "Business Name is required.";
            if (empty($_POST['contact_email'])) $errors['contact_email'] = "Contact Email is required.";
            
            $smeModel = new SME();
            $database = new Database();
            $conn = $database->getConnection();

            // Duplicate Business Name Check
            $stmtCheckName = $conn->prepare("SELECT id FROM smes WHERE business_name = :bn LIMIT 1");
            $stmtCheckName->execute([':bn' => $_POST['business_name']]);
            if ($stmtCheckName->rowCount() > 0) {
                $errors['business_name'] = "An SME with this business name already exists.";
            }

            // Duplicate Email Check
            $stmtCheckEmail = $conn->prepare("SELECT id FROM smes WHERE contact_email = :ce LIMIT 1");
            $stmtCheckEmail->execute([':ce' => $_POST['contact_email']]);
            if ($stmtCheckEmail->rowCount() > 0) {
                $errors['contact_email'] = "This contact email is already registered for another SME.";
            }

            // Phone Validation
            if (!empty($_POST['phone']) && !preg_match('/^[0-9\+\s]{7,15}$/', $_POST['phone'])) {
                $errors['phone'] = "Please enter a valid phone number (7-15 digits, spaces and + allowed).";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old_input'] = $_POST;
                header("Location: /cultureconnect/smes/add");
                exit();
            }

            $smeModel = new SME();
            $smeModel->business_name = $_POST['business_name'];
            $smeModel->contact_email = $_POST['contact_email'];
            $smeModel->phone = $_POST['phone'];
            $smeModel->portfolio_link = $_POST['portfolio_link'];

            if ($smeModel->create()) {
                $_SESSION['success'] = "SME added successfully.";
                header("Location: /cultureconnect/smes");
                exit();
            } else {
                $_SESSION['errors'] = ['general' => "Error adding SME."];
                header("Location: /cultureconnect/smes/add");
                exit();
            }
        }
    }

    public function edit() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: /cultureconnect/");
            exit;
        }
        if (isset($_GET['id'])) {
            $smeModel = new SME();
            $smeModel->id = $_GET['id'];
            $stmt = $smeModel->readOne();
            $sme = $stmt->fetch(PDO::FETCH_ASSOC);
            require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/smes/edit_sme.php';
        } else {
            header("Location: /cultureconnect/smes");
            exit();
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $errors = [];
            if (empty($_POST['business_name'])) $errors['business_name'] = "Business Name is required.";
            if (empty($_POST['contact_email'])) $errors['contact_email'] = "Contact Email is required.";

            $smeModel = new SME();
            $database = new Database();
            $conn = $database->getConnection();

            // Duplicate Business Name Check
            $stmtCheckName = $conn->prepare("SELECT id FROM smes WHERE business_name = :bn AND id != :id LIMIT 1");
            $stmtCheckName->execute([':bn' => $_POST['business_name'], ':id' => $id]);
            if ($stmtCheckName->rowCount() > 0) {
                $errors['business_name'] = "Another SME with this business name already exists.";
            }

            // Duplicate Email Check
            $stmtCheckEmail = $conn->prepare("SELECT id FROM smes WHERE contact_email = :ce AND id != :id LIMIT 1");
            $stmtCheckEmail->execute([':ce' => $_POST['contact_email'], ':id' => $id]);
            if ($stmtCheckEmail->rowCount() > 0) {
                $errors['contact_email'] = "This contact email is already registered for another SME.";
            }

            // Phone Validation
            if (!empty($_POST['phone']) && !preg_match('/^[0-9\+\s]{7,15}$/', $_POST['phone'])) {
                $errors['phone'] = "Please enter a valid phone number.";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: /cultureconnect/smes/edit?id=" . $id);
                exit();
            }

            $smeModel = new SME();
            $smeModel->id = $id;
            $smeModel->business_name = $_POST['business_name'];
            $smeModel->contact_email = $_POST['contact_email'];
            $smeModel->phone = $_POST['phone'];
            $smeModel->portfolio_link = $_POST['portfolio_link'];

            if ($smeModel->update()) {
                $_SESSION['success'] = "SME updated successfully.";
                header("Location: /cultureconnect/smes");
                exit();
            } else {
                $_SESSION['errors'] = ['general' => "Error updating SME."];
                header("Location: /cultureconnect/smes/edit?id=" . $id);
                exit();
            }
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $smeModel = new SME();
            $smeModel->id = $_GET['id'];
            if ($smeModel->delete()) {
                $_SESSION['success'] = "SME Profile deleted successfully.";
                header("Location: /cultureconnect/smes");
                exit();
            } else {
                echo "Error deleting SME.";
            }
        }
    }
}
?>

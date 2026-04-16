<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/models/Vote.php';

class VoteController {

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /cultureconnect/login");
            exit;
        }

        $voteModel = new Vote();
        
        if ($_SESSION['role'] === 'admin') {
            $stmt = $voteModel->readAll();
            require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/votes/list_vote.php';
        } elseif ($_SESSION['role'] === 'sme') {
            // For SMEs, fetch votes for THEIR products
            $database = new Database();
            $conn = $database->getConnection();
            $query = "SELECT v.*, p.name as product_name, p.category, u.name as resident_name 
                      FROM votes v 
                      JOIN products p ON v.product_id = p.id 
                      LEFT JOIN users u ON v.user_id = u.id
                      WHERE p.sme_id = :sme_id 
                      ORDER BY v.created_at DESC";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':sme_id', $_SESSION['sme_id']);
            $stmt->execute();
            $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/votes/sme_votes.php';
        } else {
            // For residents, fetch only their votes
            $database = new Database();
            $conn = $database->getConnection();
            $query = "SELECT v.*, p.name as product_name, p.category 
                      FROM votes v 
                      JOIN products p ON v.product_id = p.id 
                      WHERE v.user_id = :user_id 
                      ORDER BY v.created_at DESC";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->execute();
            $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/votes/my_votes.php';
        }
    }

    public function store() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /cultureconnect/login");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $voteModel = new Vote();
            $voteModel->user_id = $_SESSION['user_id'];
            $voteModel->product_id = $_POST['product_id'];
            $voteModel->vote = $_POST['vote']; // 'Yes' or 'No'

            if ($voteModel->create()) {
                // Redirect back to dashboard if coming from there, otherwise products
                $referer = $_SERVER['HTTP_REFERER'] ?? '/cultureconnect/products';
                header("Location: " . $referer);
                exit();
            } else {
                echo "Error casting vote.";
            }
        }
    }

    public function delete() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: /cultureconnect/");
            exit;
        }
        if (isset($_GET['id'])) {
            $voteModel = new Vote();
            $voteModel->id = $_GET['id'];
            if ($voteModel->delete()) {
                $_SESSION['success'] = "Vote removed successfully.";
                header("Location: /cultureconnect/votes");
                exit();
            } else {
                echo "Error deleting vote.";
            }
        }
    }
}
?>

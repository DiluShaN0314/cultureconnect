<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/models/Product.php';

class ProductController {

    // Display products for users
    public function index() {
        $productModel = new Product();
        $search = $_GET['search'] ?? null;
        $category = $_GET['category'] ?? null;
        $price_limit = $_GET['price_limit'] ?? null;
        $stmt = $productModel->readAll(null, $search, $category, $price_limit);
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }

        // Get user's current votes if logged in
        $user_votes = [];
        if (isset($_SESSION['user_id'])) {
            $database = new Database();
            $conn = $database->getConnection();
            $stmtVotes = $conn->prepare("SELECT product_id, vote FROM votes WHERE user_id = :user_id");
            $stmtVotes->bindParam(':user_id', $_SESSION['user_id']);
            $stmtVotes->execute();
            $user_votes = $stmtVotes->fetchAll(PDO::FETCH_KEY_PAIR);
        }

        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/products/index.php';
    }

    // Manage products for admins
    public function list() {
        if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'sme')) {
            header("Location: /cultureconnect/");
            exit;
        }

        $productModel = new Product();
        $sme_id = ($_SESSION['role'] === 'sme') ? $_SESSION['sme_id'] : null;
        $search = $_GET['search'] ?? null;
        $category = $_GET['category'] ?? null;
        $price_limit = $_GET['price_limit'] ?? null;
        $stmt = $productModel->readAll($sme_id, $search, $category, $price_limit);
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = $row;
        }
        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/admin/events.php';
    }

    public function add() {
        if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'sme')) {
            header("Location: /cultureconnect/");
            exit;
        }
        
        $smes = [];
        if ($_SESSION['role'] === 'admin') {
            $database = new Database();
            $conn = $database->getConnection();
            $stmt = $conn->query("SELECT id, business_name FROM smes ORDER BY business_name ASC");
            $smes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/products/add_product.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'sme')) {
                header("Location: /cultureconnect/");
                exit;
            }

            $productModel = new Product();
            
            // Automatic SME ID for SME users
            if ($_SESSION['role'] === 'sme') {
                $productModel->sme_id = $_SESSION['sme_id'];
            } else {
                $productModel->sme_id = !empty($_POST['sme_id']) ? $_POST['sme_id'] : null;
            }

            $productModel->name = $_POST['name'];

            // Exclusivity Check: Each product/service can be registered only once per business
            $database = new Database();
            $conn = $database->getConnection();
            $stmtCheck = $conn->prepare("SELECT id FROM products WHERE name = :name AND sme_id = :sme_id");
            $stmtCheck->bindParam(':name', $_POST['name']);
            $stmtCheck->bindParam(':sme_id', $productModel->sme_id);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() > 0) {
                echo "<script>alert('Error: This product is already registered for this business.'); window.history.back();</script>";
                exit;
            }

            $productModel->description = $_POST['description'];
            $productModel->category = $_POST['category'];
            $productModel->price_category = $_POST['price_category'];
            $productModel->price = $_POST['price'];
            $productModel->availability = isset($_POST['availability']) ? 1 : 1;

            if ($productModel->create()) {
                header("Location: /cultureconnect/events");
                exit();
            } else {
                echo "Error adding product.";
            }
        }
    }

    public function edit() {
        if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'sme')) {
            header("Location: /cultureconnect/");
            exit;
        }
        if (isset($_GET['id'])) {
            $productModel = new Product();
            $productModel->id = $_GET['id'];
            $stmt = $productModel->readOne();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                header("Location: /cultureconnect/events");
                exit();
            }

            // Ownership check for SMEs
            if ($_SESSION['role'] === 'sme' && $product['sme_id'] != $_SESSION['sme_id']) {
                header("Location: /cultureconnect/events");
                exit();
            }

            $smes = [];
            if ($_SESSION['role'] === 'admin') {
                $database = new Database();
                $conn = $database->getConnection();
                $stmtSmes = $conn->query("SELECT id, business_name FROM smes ORDER BY business_name ASC");
                $smes = $stmtSmes->fetchAll(PDO::FETCH_ASSOC);
            }

            require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/products/edit_product.php';
        } else {
            header("Location: /cultureconnect/events");
            exit();
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'sme')) {
                header("Location: /cultureconnect/");
                exit;
            }

            $productModel = new Product();
            $productModel->id = $_POST['id'];

            // Ownership check
            $stmt = $productModel->readOne();
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$existing || ($_SESSION['role'] === 'sme' && $existing['sme_id'] != $_SESSION['sme_id'])) {
                header("Location: /cultureconnect/events");
                exit();
            }

            $productModel->name = $_POST['name'];
            $productModel->description = $_POST['description'];
            $productModel->category = $_POST['category'];
            $productModel->price_category = $_POST['price_category'];
            $productModel->price = $_POST['price'];
            
            // Allow admin to change SME ID
            if ($_SESSION['role'] === 'admin' && isset($_POST['sme_id'])) {
                $productModel->sme_id = $_POST['sme_id'];
            } else {
                $productModel->sme_id = $existing['sme_id'];
            }

            if ($productModel->update()) {
                header("Location: /cultureconnect/events");
                exit();
            } else {
                echo "Error updating product.";
            }
        }
    }

    public function delete() {
        if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'sme')) {
            header("Location: /cultureconnect/");
            exit;
        }

        if (isset($_GET['id'])) {
            $productModel = new Product();
            $productModel->id = $_GET['id'];

            // Ownership check
            $stmt = $productModel->readOne();
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$existing || ($_SESSION['role'] === 'sme' && $existing['sme_id'] != $_SESSION['sme_id'])) {
                header("Location: /cultureconnect/events");
                exit();
            }

            if ($productModel->delete()) {
                header("Location: /cultureconnect/events");
                exit();
            } else {
                echo "Error deleting product.";
            }
        }
    }

    // Legacy methods for compatibility if needed (can be removed later)
    public function getProducts() {
        $product = new Product();
        $stmt = $product->readAll();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduct($id) {
        $product = new Product();
        $product->id = $id;
        $stmt = $product->readOne();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

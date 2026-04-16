<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/models/Resident.php';

class UserController {

    public function dashboard(){

        if(!isset($_SESSION['user_id'])){
            header("Location: /cultureconnect/login");
            exit();
        }

        $residentModel = new Resident();
        $stats = $residentModel->getDashboardStats($_SESSION['user_id']);

        // Fetch products for voting
        require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/models/Product.php';
        $productModel = new Product();
        $stmt = $productModel->readAll();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get user's current votes to show status
        $database = new Database();
        $conn = $database->getConnection();
        $stmtVotes = $conn->prepare("SELECT product_id, vote FROM votes WHERE user_id = :user_id");
        $stmtVotes->bindParam(':user_id', $_SESSION['user_id']);
        $stmtVotes->execute();
        $user_votes = $stmtVotes->fetchAll(PDO::FETCH_KEY_PAIR);

        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/user/dashboard.php';
    }

    public function profile(){
        if(!isset($_SESSION['user_id'])){
            header("Location: /cultureconnect/login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $database = new Database();
        $conn = $database->getConnection();

        // Fetch user basic info
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $sme = null;
        if ($_SESSION['role'] === 'sme') {
            $stmtSme = $conn->prepare("SELECT * FROM smes WHERE id = :sme_id");
            $stmtSme->execute([':sme_id' => $_SESSION['sme_id']]);
            $sme = $stmtSme->fetch(PDO::FETCH_ASSOC);
        }

        if (!$user) {
            session_destroy();
            header("Location: /cultureconnect/login?error=user_not_found");
            exit();
        }

        // Fetch areas
        require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/models/Area.php';
        $areaModel = new Area();
        $areas_stmt = $areaModel->readAll();
        $areas = $areas_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch all interests
        $stmtInterests = $conn->query("SELECT * FROM interests ORDER BY name ASC");
        $interests_list = $stmtInterests->fetchAll(PDO::FETCH_ASSOC);

        // Fetch user's current interests
        $stmtUserInterests = $conn->prepare("SELECT interest_id FROM user_interests WHERE user_id = :user_id");
        $stmtUserInterests->bindParam(':user_id', $user_id);
        $stmtUserInterests->execute();
        $user_interests = $stmtUserInterests->fetchAll(PDO::FETCH_COLUMN);

        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/user/profile.php';
    }

    public function updateProfile(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!isset($_SESSION['user_id'])){
                header("Location: /cultureconnect/login");
                exit();
            }

            $errors = [];
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            
            if (empty($name)) $errors['name'] = "Name is required.";
            if (empty($email)) $errors['email'] = "Email is required.";
            if ($_SESSION['role'] === 'user' && empty($_POST['area_id'])) $errors['area_id'] = "Area is required.";

            $user_id = $_SESSION['user_id'];
            $database = new Database();
            $conn = $database->getConnection();

            // Duplicate Email Check
            $stmtCheckEmail = $conn->prepare("SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1");
            $stmtCheckEmail->execute([':email' => $email, ':id' => $user_id]);
            if ($stmtCheckEmail->rowCount() > 0) {
                $errors['email'] = "Another user with this email address already exists.";
            }

            // Duplicate Name (Username) Check
            $stmtCheckName = $conn->prepare("SELECT id FROM users WHERE name = :name AND id != :id LIMIT 1");
            $stmtCheckName->execute([':name' => $name, ':id' => $user_id]);
            if ($stmtCheckName->rowCount() > 0) {
                $errors['name'] = "This name/username is already taken by another user.";
            }

            if ($_SESSION['role'] === 'sme' && isset($_SESSION['sme_id'])) {
                // Duplicate Business Name Check
                $stmtCheckSme = $conn->prepare("SELECT id FROM smes WHERE business_name = :bn AND id != :id LIMIT 1");
                $stmtCheckSme->execute([':bn' => $_POST['business_name'], ':id' => $_SESSION['sme_id']]);
                if ($stmtCheckSme->rowCount() > 0) {
                    $errors['business_name'] = "Another business with this name already exists.";
                }

                // Phone Validation
                if (!empty($_POST['phone']) && !preg_match('/^[0-9\+\s]{7,15}$/', $_POST['phone'])) {
                    $errors['phone'] = "Please enter a valid phone number.";
                }
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: /cultureconnect/profile");
                exit();
            }
            
            // Basic update info
            $sql = "UPDATE users SET name = :name, email = :email";
            $params = [':name' => $name, ':email' => $email, ':id' => $user_id];

            // Only update Resident-specific fields if provided (mostly for Residents)
            if ($_SESSION['role'] === 'user') {
                $sql .= ", age_group = :age_group, gender = :gender, area_id = :area_id";
                $params[':age_group'] = $_POST['age_group'] ?? null;
                $params[':gender'] = $_POST['gender'] ?? null;
                $params[':area_id'] = $_POST['area_id'] ?? null;
            }

            // Handle password if provided
            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $sql .= ", password = :password";
                $params[':password'] = $output_password = $password; // using local var for clarity
            }

            $sql .= " WHERE id = :id";

            $stmt = $conn->prepare($sql);
            if ($stmt->execute($params)) {
                // If SME, update the smes table too
                if ($_SESSION['role'] === 'sme' && isset($_SESSION['sme_id'])) {
                    $sqlSme = "UPDATE smes SET 
                                business_name = :btn, 
                                contact_email = :ce, 
                                phone = :ph, 
                                portfolio_link = :pl 
                               WHERE id = :sme_id";
                    $stmtSme = $conn->prepare($sqlSme);
                    $stmtSme->execute([
                        ':btn' => $_POST['business_name'] ?? $name,
                        ':ce' => $_POST['contact_email'] ?? $email,
                        ':ph' => $_POST['phone'] ?? null,
                        ':pl' => $_POST['portfolio_link'] ?? null,
                        ':sme_id' => $_SESSION['sme_id']
                    ]);
                }

                // Sync interests
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

                $_SESSION['username'] = $name; // Update session name
                $_SESSION['success'] = "Profile updated successfully.";
                header("Location: /cultureconnect/profile");
                exit();
            } else {
                $_SESSION['errors'] = ["Error updating profile."];
                header("Location: /cultureconnect/profile");
                exit();
            }
        }
    }
}
?>
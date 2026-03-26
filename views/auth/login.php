<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/config/database.php';
    
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            // Check by name or email
            $stmt = $conn->prepare("SELECT id, name, password, role, sme_id FROM users WHERE name = :username OR email = :username LIMIT 1");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // For existing users with 'hashed_password' literal string
                if ($row['password'] === 'hashed_password' || password_verify($password, $row['password']) || $row['password'] === $password) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['name'];
                    $_SESSION['role'] = $row['role'] ?? 'user';
                    $_SESSION['sme_id'] = $row['sme_id'] ?? null;
                    
                    if ($_SESSION['role'] === 'admin') {
                        header("Location: /cultureconnect/admin-dashboard");
                    } elseif ($_SESSION['role'] === 'sme') {
                        header("Location: /cultureconnect/sme-dashboard");
                    } else {
                        header("Location: /cultureconnect/user-dashboard");
                    }
                    exit;
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "User not found.";
            }
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CultureConnect</title>
    <link rel="stylesheet" href="/cultureconnect/assets/css/style.css">
</head>
<body class="auth-body">

    <div class="auth-container">
        <div class="auth-logo">
            <a href="/cultureconnect/">CultureConnect</a>
        </div>
        
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Please enter your details to sign in</p>
        </div>

        <?php if(isset($error) && !empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="/cultureconnect/login" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <div class="login-footer">
            <p>Don't have an account? <a href="/cultureconnect/register">Register here</a></p>
        </div>
    </div>

</body>
</html>

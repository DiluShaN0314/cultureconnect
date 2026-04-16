<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];
$success = '';

require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/config/database.php';
$database = new Database();
$conn = $database->getConnection();

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Invalid or missing token.");
}

// Verify token
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = :token AND reset_expires > NOW() LIMIT 1");
$stmt->bindParam(':token', $token);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    die("Invalid or expired password reset link.");
}

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    if (empty($errors)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $updateStmt = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_expires = NULL WHERE id = :id");
            $updateStmt->execute([
                ':password' => $hashed_password,
                ':id' => $user['id']
            ]);

            $success = "Your password has been successfully reset. You can now login.";
        } catch (PDOException $e) {
            $errors['general'] = "Database Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - CultureConnect</title>
    <link rel="stylesheet" href="/cultureconnect/assets/css/style.css">
    <script src="/cultureconnect/assets/js/script.js" defer></script>
</head>
<body class="auth-body">

    <div class="auth-container">
        <div class="auth-logo">
            <a href="/cultureconnect/">CultureConnect</a>
        </div>
        
        <div class="auth-header">
            <h2>Set New Password</h2>
            <p>Please enter your new password below.</p>
        </div>

        <?php if (!empty($success)): ?>
            <div id="success-banner" class="success-banner">
                <?php echo htmlspecialchars($success); ?>
            </div>
            <script>
                setTimeout(() => { document.getElementById('success-banner').classList.add('show'); }, 100);
            </script>
            <div style="text-align: center; margin-top: 30px;">
                <a href="/cultureconnect/login" class="btn btn-primary">Go to Login</a>
            </div>
        <?php else: ?>

            <?php if (!empty($errors['general'])): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($errors['general']); ?>
                </div>
            <?php endif; ?>

            <form action="/cultureconnect/reset-password?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                
                <div class="form-group">
                    <label for="password">New Password <span class="asterisk">*</span></label>
                    <input type="password" id="password" name="password" placeholder="Create a new password" data-required="true">
                    <?php if (isset($errors['password'])): ?><span class="error-text"><?php echo $errors['password']; ?></span><?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password <span class="asterisk">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your new password" data-required="true">
                    <?php if (isset($errors['confirm_password'])): ?><span class="error-text"><?php echo $errors['confirm_password']; ?></span><?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            </form>
        <?php endif; ?>

    </div>

</body>
</html>

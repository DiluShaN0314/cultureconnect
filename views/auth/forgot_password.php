<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/config/database.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/utils/EmailHelper.php';

    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $errors['email'] = "Email Address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($errors)) {
        try {
            $database = new Database();
            $conn = $database->getConnection();

            // Check if email exists
            $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Generate a unique token
                $token = bin2hex(random_bytes(32));
                // Set expiry time to 1 hour from now
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Save token to database
                $updateStmt = $conn->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE id = :id");
                $updateStmt->execute([
                    ':token' => $token,
                    ':expires' => $expires,
                    ':id' => $user['id']
                ]);

                // Send Email
                $mailSent = EmailHelper::sendPasswordResetEmail($email, $user['name'], $token);

                if ($mailSent) {
                    $success = "A password reset link has been sent to your email address.";
                } else {
                    $errors['general'] = "Failed to send reset email. Please try again later.";
                }
            } else {
                // To prevent email enumeration, show the same success message even if email doesn't exist
                $success = "If the email is registered, a password reset link has been sent.";
            }

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
    <title>Forgot Password - CultureConnect</title>
    <link rel="stylesheet" href="/cultureconnect/assets/css/style.css">
    <script src="/cultureconnect/assets/js/script.js" defer></script>
</head>
<body class="auth-body">

    <div class="auth-container">
        <div class="auth-logo">
            <a href="/cultureconnect/">CultureConnect</a>
        </div>
        
        <div class="auth-header">
            <h2>Reset Password</h2>
            <p>Enter your email address to receive a reset link.</p>
        </div>

        <?php if (!empty($success)): ?>
            <div id="success-banner" class="success-banner">
                <?php echo htmlspecialchars($success); ?>
            </div>
            <script>
                setTimeout(() => { document.getElementById('success-banner').classList.add('show'); }, 100);
            </script>
        <?php endif; ?>

        <?php if (!empty($errors['general'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($errors['general']); ?>
            </div>
        <?php endif; ?>

        <form action="/cultureconnect/forgot-password" method="POST">
            <div class="form-group">
                <label for="email">Email Address <span class="asterisk">*</span></label>
                <input type="email" id="email" name="email" placeholder="Enter your registered email" data-required="true" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <?php if (isset($errors['email'])): ?><span class="error-text"><?php echo $errors['email']; ?></span><?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
        </form>

        <div class="login-footer" style="margin-top: 20px;">
            <p>Remembered your password? <a href="/cultureconnect/login">Back to Login</a></p>
        </div>
    </div>

</body>
</html>

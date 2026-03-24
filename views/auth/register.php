<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/config/database.php';
$database = new Database();
$conn = $database->getConnection();

$areas = [];
$error = '';
$success = '';

// Load areas for the dropdown
try {
    if ($conn) {
        $stmtAreas = $conn->prepare("SELECT id, name FROM areas ORDER BY name ASC");
        $stmtAreas->execute();
        $areas = $stmtAreas->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error = "Failed to load areas.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $age_group = trim($_POST['age_group'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $area_id = trim($_POST['area_id'] ?? '');
    
    if (empty($name) || empty($email) || empty($password) || empty($age_group) || empty($gender) || empty($area_id)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!$conn) {
        $error = "Database connection failed.";
    } else {
        try {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $error = "Email is already registered.";
            } else {
                // Insert user
                $role = 'user';
                $sql = "INSERT INTO users (name, email, password, age_group, gender, area_id, role) VALUES (:name, :email, :password, :age_group, :gender, :area_id, :role)";
                $stmt = $conn->prepare($sql);
                
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':age_group', $age_group);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':area_id', $area_id);
                $stmt->bindParam(':role', $role);
                
                if ($stmt->execute()) {
                    $success = "Registration successful! You can now login.";
                } else {
                    $error = "Something went wrong. Please try again.";
                }
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
    <title>Register - CultureConnect</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            box-sizing: border-box;
            margin: 20px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-header h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 28px;
        }

        .register-header p {
            color: #7f8c8d;
            margin-top: 5px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: bold;
            font-size: 14px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: white;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #3498db;
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .btn-register:hover {
            background-color: #2980b9;
        }

        .register-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }

        .register-footer a {
            color: #3498db;
            text-decoration: none;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #e74c3c;
            background-color: #fadbd8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        
        .success-message {
            color: #27ae60;
            background-color: #d5f5e3;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="register-header">
            <h2>Create an Account</h2>
            <p>Join CultureConnect today</p>
        </div>

        <?php if(isset($error) && !empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($success) && !empty($success)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form action="/cultureconnect/register" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Choose a username" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="age_group">Age Group</label>
                <select id="age_group" name="age_group" required>
                    <option value="">Select Age Group</option>
                    <option value="18-25">18-25</option>
                    <option value="26-35">26-35</option>
                    <option value="36-45">36-45</option>
                    <option value="46-60">46-60</option>
                    <option value="60+">60+</option>
                </select>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                    <option value="Prefer Not to Say">Prefer Not to Say</option>
                </select>
            </div>

            <div class="form-group">
                <label for="area_id">Area</label>
                <select id="area_id" name="area_id" required>
                    <option value="">Select Area</option>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?php echo htmlspecialchars($area['id']); ?>">
                            <?php echo htmlspecialchars($area['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create a strong password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>

            <button type="submit" class="btn-register">Register</button>
        </form>

        <div class="register-footer">
            <p>Already have an account? <a href="/cultureconnect/login">Login here</a></p>
        </div>
    </div>

</body>
</html>

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

// Load areas and interests
try {
    if ($conn) {
        $stmtAreas = $conn->prepare("SELECT id, name FROM areas ORDER BY name ASC");
        $stmtAreas->execute();
        $areas = $stmtAreas->fetchAll(PDO::FETCH_ASSOC);

        $stmtInterests = $conn->prepare("SELECT id, name FROM interests ORDER BY name ASC");
        $stmtInterests->execute();
        $interests_list = $stmtInterests->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error = "Failed to load dynamic data.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? 'user';
    $name = trim($_POST['username'] ?? ''); // This will be Contact Person for SME
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Common validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        try {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $error = "Email is already registered.";
            } else {
                $conn->beginTransaction();
                
                $sme_id = null;
                if ($role === 'sme') {
                    $business_name = trim($_POST['business_name'] ?? '');
                    $phone = trim($_POST['phone'] ?? '');
                    $portfolio = trim($_POST['portfolio_link'] ?? '');
                    
                    if (empty($business_name)) {
                        throw new Exception("Business Name is required for SME registration.");
                    }
                    
                    $stmtSme = $conn->prepare("INSERT INTO smes (business_name, contact_email, phone, portfolio_link) VALUES (:bn, :ce, :ph, :pl)");
                    $stmtSme->execute([':bn' => $business_name, ':ce' => $email, ':ph' => $phone, ':pl' => $portfolio]);
                    $sme_id = $conn->lastInsertId();
                }

                // Insert user
                $age_group = !empty($_POST['age_group']) ? trim($_POST['age_group']) : null;
                $gender = !empty($_POST['gender']) ? trim($_POST['gender']) : null;
                $area_id = !empty($_POST['area_id']) ? trim($_POST['area_id']) : null;

                $sql = "INSERT INTO users (name, email, password, age_group, gender, area_id, role, sme_id) 
                        VALUES (:name, :email, :password, :age_group, :gender, :area_id, :role, :sme_id)";
                $stmt = $conn->prepare($sql);
                
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $hashed_password,
                    ':age_group' => $age_group,
                    ':gender' => $gender,
                    ':area_id' => $area_id,
                    ':role' => $role,
                    ':sme_id' => $sme_id
                ]);
                
                $user_id = $conn->lastInsertId();

                // Save interests for Residents
                if ($role === 'user' && !empty($_POST['interests'])) {
                    $stmtInterest = $conn->prepare("INSERT INTO user_interests (user_id, interest_id) VALUES (:user_id, :interest_id)");
                    foreach ($_POST['interests'] as $int_id) {
                        $stmtInterest->execute([':user_id' => $user_id, ':interest_id' => $int_id]);
                    }
                }

                $conn->commit();
                $success = "Registration successful! You can now login.";
            }
        } catch (Exception $e) {
            $conn->rollBack();
            $error = $e->getMessage();
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
    <link rel="stylesheet" href="/cultureconnect/assets/css/style.css">
</head>
<body class="auth-body">

    <div class="auth-container" style="max-width: 700px;">
        <div class="auth-logo">
            <a href="/cultureconnect/">CultureConnect</a>
        </div>

        <div class="auth-header">
            <h2>Create an Account</h2>
            <p>Join our cultural community today</p>
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

        <form action="/cultureconnect/register" method="POST" id="register-form">
            <div class="form-group">
                <label for="role">Sign up as</label>
                <select name="role" id="role-selector" required onchange="toggleFields()">
                    <option value="user">Resident (Community Member)</option>
                    <option value="sme">Small Business / Creative SME</option>
                </select>
            </div>

            <div class="grid-2">
                <div class="form-group" id="name-group">
                    <label for="username" id="name-label">Full Name</label>
                    <input type="text" id="username" name="username" placeholder="Enter your name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <!-- SME Fields -->
                <div id="sme-fields" style="display: none; grid-column: span 2;">
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="business_name">Business Name</label>
                            <input type="text" id="business_name" name="business_name" placeholder="Enter business name">
                        </div>
                        <div class="form-group">
                            <label for="phone">Contact Phone</label>
                            <input type="text" id="phone" name="phone" placeholder="Contact number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="portfolio_link">Portfolio / Website Link</label>
                        <input type="url" id="portfolio_link" name="portfolio_link" placeholder="https://example.com">
                    </div>
                </div>

                <!-- Resident Fields -->
                <div id="resident-fields" style="display: contents;">
                    <div class="form-group">
                        <label for="age_group">Age Group</label>
                        <select id="age_group" name="age_group">
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
                        <select id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                            <option value="Prefer Not to Say">Prefer Not to Say</option>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label for="area_id">Residing Area</label>
                        <select id="area_id" name="area_id">
                            <option value="">Select your area</option>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?php echo htmlspecialchars($area['id']); ?>">
                                    <?php echo htmlspecialchars($area['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label>Areas of Interest</label>
                        <div class="activity p-15 br-8 bg-light" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                            <?php foreach($interests_list as $interest): ?>
                                <label class="cursor-pointer fs-14 flex items-center">
                                    <input type="checkbox" name="interests[]" value="<?php echo $interest['id']; ?>" class="mr-10">
                                    <?php echo htmlspecialchars($interest['name']); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-15">Create Account</button>
        </form>

        <div class="register-footer">
            <p>Already have an account? <a href="/cultureconnect/login">Login here</a></p>
        </div>
    </div>

    <script>
    function toggleFields() {
        const role = document.getElementById('role-selector').value;
        const smeFields = document.getElementById('sme-fields');
        const residentFields = document.getElementById('resident-fields');
        const nameLabel = document.getElementById('name-label');
        
        if (role === 'sme') {
            smeFields.style.display = 'block';
            residentFields.style.display = 'none';
            nameLabel.textContent = 'Contact Person Name';
            document.getElementById('business_name').required = true;
            document.getElementById('age_group').required = false;
            document.getElementById('gender').required = false;
            document.getElementById('area_id').required = false;
        } else {
            smeFields.style.display = 'none';
            residentFields.style.display = 'contents';
            nameLabel.textContent = 'Full Name';
            document.getElementById('business_name').required = false;
            document.getElementById('age_group').required = true;
            document.getElementById('gender').required = true;
            document.getElementById('area_id').required = true;
        }
    }
    // Initial call
    toggleFields();
    </script>

</body>
</html>

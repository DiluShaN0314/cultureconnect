<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/utils/EmailHelper.php';
$database = new Database();
$conn = $database->getConnection();

$areas = [];
$errors = [];
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
    $errors[] = "Failed to load dynamic data.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? 'user';
    $name = trim($_POST['username'] ?? ''); // This will be Contact Person for SME
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Common validation
    if (empty($name)) $errors['username'] = "Full Name is required.";
    if (empty($email)) $errors['email'] = "Email Address is required.";
    if (empty($password)) $errors['password'] = "Password is required.";
    if ($password !== $confirm_password) $errors['confirm_password'] = "Passwords do not match.";

    if (empty($errors)) {
        try {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $errors['email'] = "Email is already registered.";
            }

            // Check if name (username) already exists
            $stmtName = $conn->prepare("SELECT id FROM users WHERE name = :name");
            $stmtName->bindParam(':name', $name);
            $stmtName->execute();
            if ($stmtName->rowCount() > 0) {
                $errors['username'] = "This name/username is already taken.";
            }

            $business_name = '';
            if ($role === 'sme') {
                $business_name = trim($_POST['business_name'] ?? '');
                $phone = trim($_POST['phone'] ?? '');
                $portfolio = trim($_POST['portfolio_link'] ?? '');
                
                if (empty($business_name)) {
                    $errors['business_name'] = "Business Name is required for SME registration.";
                } else {
                    $stmtBiz = $conn->prepare("SELECT id FROM smes WHERE business_name = :bn");
                    $stmtBiz->execute([':bn' => $business_name]);
                    if ($stmtBiz->rowCount() > 0) {
                        $errors['business_name'] = "An SME with this business name already exists.";
                    }
                }
            }

            if (empty($errors)) {
                $conn->beginTransaction();
                
                $sme_id = null;
                if ($role === 'sme') {
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
                
                // Send Welcome Email
                EmailHelper::sendWelcomeEmail($email, $name, $role);
                
                $success = "Registration successful! You can now login.";
            }
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            $errors['general'] = $e->getMessage();
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
    <script src="/cultureconnect/assets/js/script.js" defer></script>
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

        <?php if (!empty($errors['general'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($errors['general']); ?>
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
                <select name="role" id="role-selector" data-required="true" onchange="toggleFields()">
                    <option value="user">Resident (Community Member)</option>
                    <option value="sme">Small Business / Creative SME</option>
                </select>
                <?php if (isset($errors['role'])): ?><span class="error-text"><?php echo $errors['role']; ?></span><?php endif; ?>
            </div>

            <div class="grid-2">
                <div class="form-group" id="name-group">
                    <label for="username" id="name-label">Full Name <span class="asterisk">*</span></label>
                    <input type="text" id="username" name="username" placeholder="Enter your name" data-required="true" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    <?php if (isset($errors['username'])): ?><span class="error-text"><?php echo $errors['username']; ?></span><?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email Address <span class="asterisk">*</span></label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" data-required="true" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    <?php if (isset($errors['email'])): ?><span class="error-text"><?php echo $errors['email']; ?></span><?php endif; ?>
                </div>

                <!-- SME Fields -->
                <div id="sme-fields" style="display: none; grid-column: span 2;">
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="business_name">Business Name <span class="asterisk">*</span></label>
                            <input type="text" id="business_name" name="business_name" placeholder="Enter business name" value="<?php echo htmlspecialchars($_POST['business_name'] ?? ''); ?>">
                            <?php if (isset($errors['business_name'])): ?><span class="error-text"><?php echo $errors['business_name']; ?></span><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="phone">Contact Phone</label>
                            <input type="text" id="phone" name="phone" placeholder="Contact number" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="portfolio_link">Portfolio / Website Link</label>
                        <input type="url" id="portfolio_link" name="portfolio_link" placeholder="https://example.com" value="<?php echo htmlspecialchars($_POST['portfolio_link'] ?? ''); ?>">
                    </div>
                </div>

                <!-- Resident Fields -->
                <div id="resident-fields" style="display: contents;">
                    <div class="form-group">
                        <label for="age_group">Age Group <span class="asterisk">*</span></label>
                        <select id="age_group" name="age_group" data-required="true">
                            <option value="">Select Age Group</option>
                            <option value="18-25" <?php echo (isset($_POST['age_group']) && $_POST['age_group'] == '18-25') ? 'selected' : ''; ?>>18-25</option>
                            <option value="26-35" <?php echo (isset($_POST['age_group']) && $_POST['age_group'] == '26-35') ? 'selected' : ''; ?>>26-35</option>
                            <option value="36-45" <?php echo (isset($_POST['age_group']) && $_POST['age_group'] == '36-45') ? 'selected' : ''; ?>>36-45</option>
                            <option value="46-60" <?php echo (isset($_POST['age_group']) && $_POST['age_group'] == '46-60') ? 'selected' : ''; ?>>46-60</option>
                            <option value="60+" <?php echo (isset($_POST['age_group']) && $_POST['age_group'] == '60+') ? 'selected' : ''; ?>>60+</option>
                        </select>
                        <?php if (isset($errors['age_group'])): ?><span class="error-text"><?php echo $errors['age_group']; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender <span class="asterisk">*</span></label>
                        <select id="gender" name="gender" data-required="true">
                            <option value="">Select Gender</option>
                            <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                            <option value="Prefer Not to Say" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Prefer Not to Say') ? 'selected' : ''; ?>>Prefer Not to Say</option>
                        </select>
                        <?php if (isset($errors['gender'])): ?><span class="error-text"><?php echo $errors['gender']; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label for="area_id">Residing Area <span class="asterisk">*</span></label>
                        <select id="area_id" name="area_id" data-required="true">
                            <option value="">Select your area</option>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?php echo htmlspecialchars($area['id']); ?>" <?php echo (isset($_POST['area_id']) && $_POST['area_id'] == $area['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($area['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['area_id'])): ?><span class="error-text"><?php echo $errors['area_id']; ?></span><?php endif; ?>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label for="interests">Areas of Interest</label>
                        <div class="custom-multiselect" id="interests-multiselect">
                            <div class="multiselect-trigger">
                                <span class="trigger-text">Select Options...</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="multiselect-dropdown">
                                <?php foreach($interests_list as $interest): ?>
                                    <div class="multiselect-option">
                                        <input type="checkbox" name="interests[]" value="<?php echo $interest['id']; ?>">
                                        <span><?php echo htmlspecialchars($interest['name']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="asterisk">*</span></label>
                    <input type="password" id="password" name="password" placeholder="Create a password" data-required="true">
                    <?php if (isset($errors['password'])): ?><span class="error-text"><?php echo $errors['password']; ?></span><?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password <span class="asterisk">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" data-required="true">
                    <?php if (isset($errors['confirm_password'])): ?><span class="error-text"><?php echo $errors['confirm_password']; ?></span><?php endif; ?>
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
            nameLabel.innerHTML = 'Contact Person Name <span class="asterisk">*</span>';
            document.getElementById('business_name').dataset.required = "true";
            document.getElementById('age_group').dataset.required = "false";
            document.getElementById('gender').dataset.required = "false";
            document.getElementById('area_id').dataset.required = "false";
        } else {
            smeFields.style.display = 'none';
            residentFields.style.display = 'contents';
            nameLabel.innerHTML = 'Full Name <span class="asterisk">*</span>';
            document.getElementById('business_name').dataset.required = "false";
            document.getElementById('age_group').dataset.required = "true";
            document.getElementById('gender').dataset.required = "true";
            document.getElementById('area_id').dataset.required = "true";
        }
    }
    // Initial call
    toggleFields();
    </script>

</body>
</html>

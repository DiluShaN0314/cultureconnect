<?php
require_once __DIR__ . '/config/database.php';

$database = new Database();
$conn = $database->getConnection();

echo "Starting Database Seeding...\n";

// Password is 'password123'
$hashedPassword = password_hash('password123', PASSWORD_DEFAULT);

try {
    $conn->beginTransaction();

    // 1. DUMMY SMEs
    $smeData = [
        ['Apex Designs', 'contact@apexdesigns.com', '07800111222', 'https://apexdesigns.com'],
        ['Green Thumb Nursery', 'info@greenthumb.com', '07800222333', 'https://greenthumb.com'],
        ['Urban Bites Kitchen', 'hello@urbanbites.co.uk', '07800333444', 'https://urbanbites.co.uk'],
        ['TechNova Solutions', 'support@technova.com', '07800444555', 'https://technova.com'],
        ['Local Loom Textiles', 'hello@localloom.com', '07800555666', 'https://localloom.com']
    ];

    $stmtSme = $conn->prepare("INSERT INTO smes (business_name, contact_email, phone, portfolio_link) VALUES (?, ?, ?, ?)");
    $insertedSmes = [];
    foreach ($smeData as $sme) {
        $stmtSme->execute($sme);
        $insertedSmes[] = $conn->lastInsertId();
    }
    echo "Inserted " . count($insertedSmes) . " SMEs.\n";

    // 2. DUMMY PRODUCTS
    $productData = [
        [$insertedSmes[0], 'Logo Design Package', 'Complete branding and logo design for startups.', 'Design', 'Premium', 499.99],
        [$insertedSmes[0], 'Social Media Templates', 'Editable templates for Instagram and Facebook.', 'Design', 'Affordable', 25.00],
        [$insertedSmes[1], 'Indoor Monstera Plant', 'Lush, healthy Monstera perfect for homes.', 'Home & Garden', 'Moderate', 45.00],
        [$insertedSmes[1], 'Herb Garden Starter Kit', 'Everything you need to grow your own herbs.', 'Home & Garden', 'Affordable', 15.50],
        [$insertedSmes[2], 'Artisan Bread Loaf', 'Freshly baked sourdough bread.', 'Food', 'Affordable', 5.50],
        [$insertedSmes[2], 'Gourmet Dinner Box', 'Meal kit for two with premium ingredients.', 'Food', 'Premium', 55.00],
        [$insertedSmes[3], 'Website Audit', 'Comprehensive SEO and UI/UX audit.', 'Technology', 'Premium', 250.00],
        [$insertedSmes[4], 'Handwoven Scarf', 'Merino wool scarf made locally.', 'Fashion', 'Moderate', 65.00]
    ];

    $stmtProduct = $conn->prepare("INSERT INTO products (sme_id, name, description, category, price_category, price, availability) VALUES (?, ?, ?, ?, ?, ?, 1)");
    $insertedProducts = [];
    foreach ($productData as $product) {
        $stmtProduct->execute($product);
        $insertedProducts[] = $conn->lastInsertId();
    }
    echo "Inserted " . count($insertedProducts) . " Products.\n";

    // 3. DUMMY USERS (RESIDENTS & SME OWNERS)
    // Fetch some area IDs to assign
    $stmtAreas = $conn->prepare("SELECT id FROM areas LIMIT 3");
    $stmtAreas->execute();
    $areas = $stmtAreas->fetchAll(PDO::FETCH_COLUMN);
    $area1 = $areas[0] ?? 1;
    $area2 = $areas[1] ?? 1;

    $userData = [
        ['Alice Walker', 'alice@example.com', '26-35', 'Female', $area1, null, 'user'],
        ['Bob Miller', 'bob@example.com', '18-25', 'Male', $area2, null, 'user'],
        ['Charlie Davis', 'charlie@example.com', '36-45', 'Male', $area1, null, 'user'],
        ['Diana Prince', 'diana@example.com', '46-60', 'Female', $area2, null, 'user'],
        // SME Owners
        ['Evan Apex', 'evan@apexdesigns.com', '26-35', 'Male', $area1, $insertedSmes[0], 'sme'],
        ['Fiona Green', 'fiona@greenthumb.com', '36-45', 'Female', $area2, $insertedSmes[1], 'sme']
    ];

    $stmtUser = $conn->prepare("INSERT INTO users (name, email, password, age_group, gender, area_id, sme_id, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insertedUsers = [];
    foreach ($userData as $user) {
        // add password directly
        $u = [
            $user[0], $user[1], $hashedPassword, $user[2], $user[3], $user[4], $user[5], $user[6]
        ];
        $stmtUser->execute($u);
        $insertedUsers[] = $conn->lastInsertId();
    }
    echo "Inserted " . count($insertedUsers) . " Users.\n";

    // 4. DUMMY VOTES (Only Residents vote on Products)
    $residentIds = array_slice($insertedUsers, 0, 4); // First 4 are users
    $voteOptions = ['Yes', 'Yes', 'Yes', 'No']; // Bias towards Yes

    $stmtVote = $conn->prepare("INSERT IGNORE INTO votes (user_id, product_id, vote) VALUES (?, ?, ?)");
    $voteCount = 0;
    foreach ($residentIds as $r_id) {
        foreach ($insertedProducts as $p_id) {
            // Randomly decide if they voted
            if (rand(1, 100) > 40) { // 60% chance to vote
                $vote = $voteOptions[array_rand($voteOptions)];
                $stmtVote->execute([$r_id, $p_id, $vote]);
                $voteCount++;
            }
        }
    }
    echo "Inserted " . $voteCount . " random Votes.\n";

    $conn->commit();
    echo "\nDatabase seeding completed successfully!\n";
    echo "Test user credentials:\n";
    echo "Email: alice@example.com / Password: password123\n";

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo "Seeding failed: " . $e->getMessage() . "\n";
}

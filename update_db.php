<?php
require_once __DIR__ . '/config/database.php';

$database = new Database();
$conn = $database->getConnection();

try {
    $conn->exec("ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) NULL AFTER password, ADD COLUMN reset_expires DATETIME NULL AFTER reset_token");
    echo "Successfully added reset_token and reset_expires columns to users table.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Columns already exist.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

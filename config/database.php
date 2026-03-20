<?php

class Database {
    private $host = "localhost";
    private $db_name = "cultureconnect"; //  DB name
    private $username = "root"; // default XAMPP user
    private $password = ""; // default XAMPP password
    public $conn;

    // Get DB Connection
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );

            // Set error mode
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}


/*// Create database object and get connection
$database = new Database();
$conn = $database->getConnection();

// Sample SELECT query using PDO
$sql = "SELECT id, name, age_group, area_id FROM users";

try {
    $stmt = $conn->prepare($sql); // Prepare the query
    $stmt->execute();              // Execute the query

    // Check if rows exist
    if ($stmt->rowCount() > 0) {
        // Fetch all rows as associative array
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            echo "ID: " . $row["id"] . " | Name: " . $row["name"] . " | Age: " . $row["age_group"] . " | Area ID: " . $row["area_id"] . "<br>";
        }
    } else {
        echo "0 results found";
    }
} catch (PDOException $e) {
    echo "Query error: " . $e->getMessage();
}

// Close connection (optional for PDO, but you can unset it)
$conn = null;
?>*/
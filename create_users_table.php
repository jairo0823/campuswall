<?php
// Include the PDO configuration file
require_once 'pdo_config.php';

try {
    // SQL statement to create the users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        birthdate DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // Execute the query
    $pdo->exec($sql);

    echo "Users table created successfully.";
} catch (PDOException $e) {
    echo "Error creating users table: " . $e->getMessage();
}
?>

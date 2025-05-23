<?php
// PDO configuration file

$host = 'localhost'; // Change if your DB host is different
$dbname = 'campuswall'; // Change to your database name
$username = 'root'; // Change to your DB username
$password = ''; // Change to your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die("Database connection failed: " . $e->getMessage());
}
?>

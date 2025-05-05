<?php
$host = 'localhost'; // Change if your database is hosted elsewhere
$db = 'database_schema'; // Updated database name
$user = 'root'; // Default MySQL username for XAMPP
$pass = ''; // Default MySQL password for XAMPP (empty by default)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
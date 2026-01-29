<?php
/* =========================
   DATABASE CONFIGURATION
   ========================= */

$host = "localhost";
$db   = "quiz_master";   // database name
$user = "root";          // XAMPP default user
$pass = "";              // XAMPP default password

/* =========================
   PDO CONNECTION
   ========================= */

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
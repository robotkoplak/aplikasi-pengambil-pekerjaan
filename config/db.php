<?php
$host = 'localhost';
$dbname = 'sistem_tiket';
$username = 'sistem_tiket_user';
$password = 'password_database_anda';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("Database connection successful");
} catch(PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Koneksi database gagal: " . $e->getMessage());
}
?>

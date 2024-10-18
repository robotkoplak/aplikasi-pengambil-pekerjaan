<?php
require_once 'config/db.php';

$sql = file_get_contents('database_structure.sql');

try {
    $pdo->exec($sql);
    echo "Database berhasil disetup.";
} catch(PDOException $e) {
    die("Error setting up database: " . $e->getMessage());
}

// Tambahkan role 'reviewer'
try {
    $pdo->exec("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teknisi', 'reviewer') NOT NULL");
    echo "Role 'reviewer' berhasil ditambahkan.";
} catch(PDOException $e) {
    die("Error menambahkan role 'reviewer': " . $e->getMessage());
}
?>

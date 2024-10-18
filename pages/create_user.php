<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $role = $_POST['role'] ?? '';

    if (empty($username) || empty($password) || empty($nama_lengkap) || empty($role)) {
        $error = "Semua field harus diisi.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$username, $hashed_password, $nama_lengkap, $role]);

            if ($result) {
                $success = "User berhasil ditambahkan.";
            } else {
                $error = "Gagal menambahkan user.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Redirect kembali ke dashboard admin dengan pesan sukses atau error
if (isset($success)) {
    header("Location: admin_dashboard.php?success=" . urlencode($success));
} elseif (isset($error)) {
    header("Location: admin_dashboard.php?error=" . urlencode($error));
}
exit();
?>

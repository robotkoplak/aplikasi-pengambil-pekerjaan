<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $role = $_POST['role'];

    if (createUser($pdo, $username, $password, $nama_lengkap, $role)) {
        $success = "User berhasil dibuat.";
    } else {
        $error = "Gagal membuat user.";
    }
}

$pageTitle = 'Buat User Baru';
include '../includes/header.php';
?>

<h1 class="mb-4">Buat User Baru</h1>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form action="create_user.php" method="POST" class="mb-4">
    <div class="mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="nama_lengkap" class="form-label">Nama Lengkap:</label>
        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Role:</label>
        <select class="form-select" id="role" name="role" required>
            <option value="admin">Admin</option>
            <option value="teknisi">Teknisi</option>
            <option value="reviewer">Reviewer</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Buat User</button>
</form>

<a href="admin_dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>

<?php include '../includes/footer.php'; ?>

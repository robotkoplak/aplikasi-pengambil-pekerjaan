<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    if (createTicket($pdo, $judul, $deskripsi)) {
        $success = "Tiket berhasil dibuat.";
    } else {
        $error = "Gagal membuat tiket.";
    }
}

$pageTitle = 'Buat Tiket Baru';
include '../includes/header.php';
?>

<h1 class="mb-4">Buat Tiket Baru</h1>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form action="create_ticket.php" method="POST" class="mb-4">
    <div class="mb-3">
        <label for="judul" class="form-label">Judul:</label>
        <input type="text" class="form-control" id="judul" name="judul" required>
    </div>
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi:</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Buat Tiket</button>
</form>

<a href="admin_dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>

<?php include '../includes/footer.php'; ?>

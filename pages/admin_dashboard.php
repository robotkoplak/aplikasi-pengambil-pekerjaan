<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$tickets = getAllTickets($pdo);

$pageTitle = 'Admin Dashboard';
include '../includes/header.php';
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<h1 class="mb-4">Dashboard Admin</h1>

<div class="row">
    <div class="col-md-6">
        <h2>Buat Tiket Baru</h2>
        <form action="create_ticket.php" method="POST" class="mb-4">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul:</label>
                <input type="text" class="form-control" id="judul" name="judul" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi:</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Buat Tiket</button>
        </form>
    </div>
    <div class="col-md-6">
        <h2>Buat User Baru</h2>
        <form method="POST" action="create_user.php">
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
    </div>
</div>

<h2>Daftar Tiket</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Status</th>
            <th>Teknisi</th>
            <th>Tanggal Dibuat</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tickets as $ticket): ?>
        <tr>
            <td><?php echo htmlspecialchars($ticket['id']); ?></td>
            <td><?php echo htmlspecialchars($ticket['judul']); ?></td>
            <td><?php echo htmlspecialchars($ticket['deskripsi']); ?></td>
            <td><?php echo htmlspecialchars($ticket['status']); ?></td>
            <td><?php echo $ticket['teknisi_id'] ? getTechnicianName($pdo, $ticket['teknisi_id']) : 'Belum diambil'; ?></td>
            <td><?php echo htmlspecialchars($ticket['created_at']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="edit_profile.php" class="btn btn-info">Edit Profil</a>

<?php include '../includes/footer.php'; ?>

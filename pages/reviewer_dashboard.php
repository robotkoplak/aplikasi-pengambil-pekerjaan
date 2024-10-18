<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'reviewer') {
    header("Location: ../login.php");
    exit();
}

$active_tickets = getActiveTickets($pdo);

$pageTitle = 'Dashboard Reviewer';
include '../includes/header.php';
?>

<h1 class="mb-4">Dashboard Reviewer</h1>

<h2>Tiket Aktif</h2>
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
        <?php foreach ($active_tickets as $ticket): ?>
        <tr>
            <td><?php echo htmlspecialchars($ticket['id']); ?></td>
            <td><?php echo htmlspecialchars($ticket['judul']); ?></td>
            <td><?php echo htmlspecialchars($ticket['deskripsi']); ?></td>
            <td><?php echo htmlspecialchars($ticket['status']); ?></td>
            <td><?php echo getTechnicianName($pdo, $ticket['teknisi_id']); ?></td>
            <td><?php echo htmlspecialchars($ticket['created_at']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>

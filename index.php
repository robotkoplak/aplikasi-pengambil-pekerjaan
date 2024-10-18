<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$tickets = getTickets($pdo);

// Di awal file index.php
$uploadDir = __DIR__ . '/uploads';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Tiket Teknisi</title>
</head>
<body>
    <h1>Selamat datang di Sistem Tiket Teknisi</h1>
    <nav>
        <ul>
            <li><a href="pages/create_ticket.php">Buat Tiket Baru</a></li>
            <li><a href="pages/view_tickets.php">Lihat Semua Tiket</a></li>
            <li><a href="pages/create_daily_report.php">Buat Laporan Harian</a></li>
            <li><a href="pages/view_daily_reports.php">Lihat Laporan Harian</a></li>
            <li><a href="pages/technician_report.php">Laporan Pemasangan Teknisi</a></li>
            <li><a href="logout.php">Keluar</a></li>
        </ul>
    </nav>
    
    <h2>Tiket Terbaru</h2>
    <ul>
    <?php foreach ($tickets as $ticket): ?>
        <li>
            <?php echo htmlspecialchars($ticket['judul']); ?> - 
            Status: <?php echo htmlspecialchars($ticket['status']); ?>
        </li>
    <?php endforeach; ?>
    </ul>
</body>
</html>

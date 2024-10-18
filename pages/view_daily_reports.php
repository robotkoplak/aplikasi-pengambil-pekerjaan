<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$reports = getDailyReports($pdo);

$pageTitle = 'Laporan Harian';
include '../includes/header.php';
?>

<h1 class="mb-4">Laporan Harian</h1>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nama Pelanggan</th>
            <th>No Layanan</th>
            <th>Alamat</th>
            <th>Durasi Waktu</th>
            <th>Teknisi</th>
            <th>Foto</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reports as $report): ?>
        <tr>
            <td><?php echo htmlspecialchars($report['tanggal']); ?></td>
            <td><?php echo htmlspecialchars($report['nama_pelanggan']); ?></td>
            <td><?php echo htmlspecialchars($report['no_layanan']); ?></td>
            <td><?php echo htmlspecialchars($report['alamat']); ?></td>
            <td><?php echo htmlspecialchars($report['durasi_waktu']); ?></td>
            <td><?php echo htmlspecialchars($report['teknisi']); ?></td>
            <td>
                <?php if ($report['foto_sn_ont']): ?>
                    <a href="../uploads/<?php echo $report['foto_sn_ont']; ?>" target="_blank" class="btn btn-sm btn-info">SN ONT</a>
                <?php endif; ?>
                <?php if ($report['foto_ktp_pelanggan']): ?>
                    <a href="../uploads/<?php echo $report['foto_ktp_pelanggan']; ?>" target="_blank" class="btn btn-sm btn-info">KTP Pelanggan</a>
                <?php endif; ?>
                <?php if ($report['foto_teknisi_pelanggan']): ?>
                    <a href="../uploads/<?php echo $report['foto_teknisi_pelanggan']; ?>" target="_blank" class="btn btn-sm btn-info">Teknisi & Pelanggan</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>

<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'tanggal' => $_POST['tanggal'],
        'nama_pelanggan' => $_POST['nama_pelanggan'],
        'no_layanan' => $_POST['no_layanan'],
        'alamat' => $_POST['alamat'],
        'durasi_waktu' => $_POST['durasi_waktu'],
        'teknisi' => $_POST['teknisi']
    ];

    if (createDailyReport($pdo, $data, $_FILES)) {
        $success = "Laporan harian berhasil dibuat!";
    } else {
        $error = "Gagal membuat laporan harian.";
    }
}

$pageTitle = 'Buat Laporan Harian';
include '../includes/header.php';
?>

<h1 class="mb-4">Buat Laporan Harian</h1>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="mb-4">
    <div class="mb-3">
        <label for="tanggal" class="form-label">Tanggal:</label>
        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
    </div>
    <div class="mb-3">
        <label for="nama_pelanggan" class="form-label">Nama Pelanggan:</label>
        <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
    </div>
    <div class="mb-3">
        <label for="no_layanan" class="form-label">No Layanan:</label>
        <input type="text" class="form-control" id="no_layanan" name="no_layanan" required>
    </div>
    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat:</label>
        <textarea class="form-control" id="alamat" name="alamat" required></textarea>
    </div>
    <div class="mb-3">
        <label for="durasi_waktu" class="form-label">Durasi Waktu:</label>
        <input type="text" class="form-control" id="durasi_waktu" name="durasi_waktu" required>
    </div>
    <div class="mb-3">
        <label for="teknisi" class="form-label">Teknisi:</label>
        <input type="text" class="form-control" id="teknisi" name="teknisi" required>
    </div>
    <div class="mb-3">
        <label for="foto_sn_ont" class="form-label">Foto SN ONT:</label>
        <input type="file" class="form-control" id="foto_sn_ont" name="foto_sn_ont" accept="image/*">
    </div>
    <div class="mb-3">
        <label for="foto_ktp_pelanggan" class="form-label">Foto KTP Pelanggan:</label>
        <input type="file" class="form-control" id="foto_ktp_pelanggan" name="foto_ktp_pelanggan" accept="image/*">
    </div>
    <div class="mb-3">
        <label for="foto_teknisi_pelanggan" class="form-label">Foto Teknisi & Pelanggan:</label>
        <input type="file" class="form-control" id="foto_teknisi_pelanggan" name="foto_teknisi_pelanggan" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Buat Laporan</button>
</form>

<a href="../index.php" class="btn btn-secondary">Kembali ke Beranda</a>

<?php include '../includes/footer.php'; ?>

<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$technicians = getTechniciansInstallationCount($pdo, $month, $year);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemasangan Teknisi</title>
</head>
<body>
    <h1>Laporan Pemasangan Teknisi</h1>
    
    <form method="GET">
        <label for="month">Bulan:</label>
        <select name="month" id="month">
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?php echo sprintf("%02d", $i); ?>" <?php echo $month == sprintf("%02d", $i) ? 'selected' : ''; ?>>
                    <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                </option>
            <?php endfor; ?>
        </select>

        <label for="year">Tahun:</label>
        <select name="year" id="year">
            <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                <option value="<?php echo $i; ?>" <?php echo $year == $i ? 'selected' : ''; ?>>
                    <?php echo $i; ?>
                </option>
            <?php endfor; ?>
        </select>

        <button type="submit">Tampilkan</button>
    </form>

    <table border="1">
        <tr>
            <th>Nama Teknisi</th>
            <th>Jumlah Pemasangan</th>
        </tr>
        <?php foreach ($technicians as $technician): ?>
        <tr>
            <td><?php echo htmlspecialchars($technician['teknisi']); ?></td>
            <td><?php echo htmlspecialchars($technician['installation_count']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="../index.php">Kembali ke Beranda</a>
</body>
</html>


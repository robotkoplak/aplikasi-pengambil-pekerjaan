<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teknisi') {
    header("Location: ../login.php");
    exit();
}

$ticket_id = isset($_GET['id']) ? $_GET['id'] : null;
$technician_id = $_SESSION['user_id'];

if (!$ticket_id) {
    header("Location: technician_dashboard.php");
    exit();
}

$ticket = getTicketById($pdo, $ticket_id);

if (!$ticket || $ticket['teknisi_id'] != $technician_id) {
    header("Location: technician_dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'];
    if (updateTicketStatus($pdo, $ticket_id, $new_status)) {
        header("Location: technician_dashboard.php");
        exit();
    }
}

$pageTitle = 'Update Tiket';
include '../includes/header.php';
?>

<h1 class="mb-4">Update Tiket #<?php echo htmlspecialchars($ticket_id); ?></h1>

<form method="POST" class="mb-4">
    <div class="mb-3">
        <label for="status" class="form-label">Status:</label>
        <select name="status" id="status" class="form-select">
            <option value="diproses" <?php echo $ticket['status'] === 'diproses' ? 'selected' : ''; ?>>Diproses</option>
            <option value="selesai" <?php echo $ticket['status'] === 'selesai' ? 'selected' : ''; ?>>Selesai</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update Status</button>
</form>

<a href="technician_dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>

<?php include '../includes/footer.php'; ?>

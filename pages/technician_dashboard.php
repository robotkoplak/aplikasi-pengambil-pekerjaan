<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teknisi') {
    header("Location: ../login.php");
    exit();
}

$technician_id = $_SESSION['user_id'];
$available_tickets = getAvailableTickets($pdo);
$assigned_tickets = getAssignedTickets($pdo, $technician_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['take_ticket'])) {
    $ticket_id = $_POST['ticket_id'];
    if (assignTicket($pdo, $ticket_id, $technician_id)) {
        header("Location: technician_dashboard.php");
        exit();
    }
}

$pageTitle = 'Dashboard Teknisi';
include '../includes/header.php';
?>

<h1 class="mb-4">Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>

<div class="row">
    <div class="col-md-6">
        <h2>Tiket Tersedia</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($available_tickets as $ticket): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ticket['id']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['judul']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['deskripsi']); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                            <button type="submit" name="take_ticket" class="btn btn-primary btn-sm">Ambil Tugas</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h2>Tugas Saya</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assigned_tickets as $ticket): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ticket['id']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['judul']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['deskripsi']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['status']); ?></td>
                    <td>
                        <a href="update_ticket.php?id=<?php echo $ticket['id']; ?>" class="btn btn-secondary btn-sm">Update</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<?php
function createTicket($pdo, $judul, $deskripsi) {
    $sql = "INSERT INTO tickets (judul, deskripsi, status) VALUES (?, ?, 'baru')";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$judul, $deskripsi]);
}

function getAllTickets($pdo) {
    $sql = "SELECT * FROM tickets ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTechnicianName($pdo, $technician_id) {
    $sql = "SELECT nama_lengkap FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$technician_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['nama_lengkap'] : 'Unknown';
}

function updateTicketStatus($pdo, $ticket_id, $status) {
    $sql = "UPDATE tickets SET status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$status, $ticket_id]);
}

function createDailyReport($pdo, $data, $files) {
    $uploadDir = __DIR__ . '/../uploads/';
    
    // Handle file uploads
    $fileFields = ['foto_sn_ont', 'foto_ktp_pelanggan', 'foto_teknisi_pelanggan'];
    foreach ($fileFields as $field) {
        if (isset($files[$field]) && $files[$field]['error'] == 0) {
            $fileName = uniqid() . '_' . basename($files[$field]['name']);
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($files[$field]['tmp_name'], $targetFile)) {
                $data[$field] = $fileName;
            } else {
                // Handle upload error
                return false;
            }
        } else {
            $data[$field] = null;
        }
    }

    $sql = "INSERT INTO daily_reports (tanggal, nama_pelanggan, no_layanan, alamat, durasi_waktu, teknisi, foto_sn_ont, foto_ktp_pelanggan, foto_teknisi_pelanggan) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['tanggal'],
        $data['nama_pelanggan'],
        $data['no_layanan'],
        $data['alamat'],
        $data['durasi_waktu'],
        $data['teknisi'],
        $data['foto_sn_ont'],
        $data['foto_ktp_pelanggan'],
        $data['foto_teknisi_pelanggan']
    ]);
}

function getDailyReports($pdo) {
    $sql = "SELECT * FROM daily_reports ORDER BY tanggal DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createUser($pdo, $username, $password, $nama_lengkap, $role) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$username, $hashed_password, $nama_lengkap, $role]);
}

function getUserByUsername($pdo, $username) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("getUserByUsername result: " . ($user ? json_encode($user) : 'null'));
        return $user;
    } catch (Exception $e) {
        error_log("Error in getUserByUsername: " . $e->getMessage());
        return null;
    }
}

function getAllUsers($pdo) {
    $sql = "SELECT id, username, nama_lengkap, role FROM users ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function isAdmin($pdo, $user_id) {
    $sql = "SELECT role FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user && $user['role'] === 'admin';
}

function getTechniciansInstallationCount($pdo, $month, $year) {
    $sql = "SELECT teknisi, COUNT(*) as installation_count 
            FROM daily_reports 
            WHERE strftime('%m', tanggal) = ? AND strftime('%Y', tanggal) = ?
            GROUP BY teknisi 
            ORDER BY installation_count DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$month, $year]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAvailableTickets($pdo) {
    $sql = "SELECT * FROM tickets WHERE teknisi_id IS NULL AND status = 'baru' ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAssignedTickets($pdo, $technician_id) {
    $sql = "SELECT * FROM tickets WHERE teknisi_id = ? ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$technician_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function assignTicket($pdo, $ticket_id, $technician_id) {
    $sql = "UPDATE tickets SET teknisi_id = ?, status = 'diproses' WHERE id = ? AND teknisi_id IS NULL";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$technician_id, $ticket_id]);
}

function getActiveTickets($pdo) {
    $sql = "SELECT * FROM tickets WHERE status IN ('diproses', 'baru') ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

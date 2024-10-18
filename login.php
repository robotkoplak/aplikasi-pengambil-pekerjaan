<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

error_log("Login page accessed");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    error_log("Login attempt: username=$username");

    try {
        $user = getUserByUsername($pdo, $username);
        error_log("User found: " . ($user ? 'yes' : 'no'));

        if ($user) {
            error_log("Stored password hash: " . $user['password']);
            $verify_result = password_verify($password, $user['password']);
            error_log("Password verify result: " . ($verify_result ? 'true' : 'false'));

            if ($verify_result) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                error_log("Login successful. Redirecting to dashboard.");
                header("Location: pages/admin_dashboard.php");
                exit();
            } else {
                $error = "Password salah.";
                error_log("Login failed: incorrect password");
            }
        } else {
            $error = "Username tidak ditemukan.";
            error_log("Login failed: username not found");
        }
    } catch (Exception $e) {
        error_log("Error during login: " . $e->getMessage());
        $error = "Terjadi kesalahan saat login.";
    }
}

// Tambahkan ini untuk debugging
if (isset($_GET['debug'])) {
    phpinfo();
    exit;
}

// Kode untuk membuat user baru
if (isset($_GET['create_test_user'])) {
    $new_password = '123456';
    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
    error_log("New hash created for test user: " . $new_hash);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute(['testuser', $new_hash, 'Test User', 'admin']);
        echo $result ? "Test user created successfully" : "Failed to create test user";
    } catch (PDOException $e) {
        echo "Error creating test user: " . $e->getMessage();
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Tiket</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-form {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h2 class="text-center mb-4">Login</h2>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    error_log("Login attempt: username=$username, password=$password");

    try {
        $user = getUserByUsername($pdo, $username);
        error_log("User found: " . ($user ? json_encode($user) : 'no'));

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
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
    } catch (Exception $e) {
        error_log("Error during login: " . $e->getMessage());
        $error = "Terjadi kesalahan saat login.";
    }
}

$pageTitle = 'Login';
include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="mb-4">Login</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

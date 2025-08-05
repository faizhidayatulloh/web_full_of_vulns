<?php
require 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Generate simple CSRF token for login page
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = base64_encode(uniqid());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $password = $_POST['password'];
    $token = $_POST['csrf_token'];

    if (!isset($token) || $token !== $_SESSION['csrf_token']) {
        die("CSRF token tidak valid.");
    }

    $stmt = $conn->prepare("SELECT * FROM data WHERE nama = ?");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if ($password === $user['password']) {
            $_SESSION['user'] = $user;
            $_SESSION['csrf_token'] = md5($user['nama']);
    
        // Set logout cookie with SameSite=Lax
            $logout_token = bin2hex(random_bytes(16));
            setcookie('logout_token', $logout_token, [
                'expires' => time() + 86400, // 1 day
                'path' => '/',
                'domain' => '',
                'secure' => false, // true jika menggunakan HTTPS
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            $_SESSION['logout_token'] = $logout_token;
    
            header("Location: dashboard.php");
            exit;
        }
    }
    $error = "Login gagal!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="text" name="nama" placeholder="Nama" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
<?php
require 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$error = $success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi CSRF token (double submit cookie)
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_COOKIE['csrf_cookie']) {
        die("CSRF token tidak valid.");
    }

    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    $id = $_SESSION['user']['id'];

    if ($new !== $confirm) {
        $error = "Konfirmasi password tidak cocok.";
    } elseif (strlen($new) < 6) {
        $error = "Password baru harus minimal 6 karakter.";
    } else {
        $stmt = $conn->prepare("UPDATE data SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new, $id);
        if ($stmt->execute()) {
            $success = "Password berhasil diubah.";
            $_SESSION['user']['password'] = $new;
        } else {
            $error = "Gagal mengubah password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ubah Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Ubah Password</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="password" name="new_password" placeholder="Password Baru" required minlength="6">
        <input type="password" name="confirm_password" placeholder="Konfirmasi Password Baru" required minlength="6">
        <button type="submit">Ubah Password</button>
    </form>
    <a href="dashboard.php">⬅️ Kembali ke Dashboard</a>
</div>
<script>
// Set CSRF cookie when page loads
document.cookie = "csrf_cookie=<?= $_SESSION['csrf_token'] ?>";
</script>
</body>
</html>
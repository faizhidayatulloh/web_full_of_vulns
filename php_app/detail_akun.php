<?php
require 'config.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Akun</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Detail Akun</h2>
    <div class="account-details">
        <p><strong>ID:</strong> <?= htmlspecialchars($user['id']) ?></p>
        <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama']) ?></p>
        <p><strong>Saldo:</strong> Rp <?= number_format($user['jumlah_uang'], 0, ',', '.') ?></p>
        <p><strong>Password:</strong> 
            <a href="ubah_password.php" class="change-password">Ubah Password</a>
        </p>
    </div>
    <a href="dashboard.php">⬅️ Kembali ke Dashboard</a>
</div>
</body>
</html>
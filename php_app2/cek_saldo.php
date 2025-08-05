
<?php
require 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$saldo = $user['jumlah_uang'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cek Saldo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Informasi Saldo</h2>
    <div class="balance-info">
        <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama']) ?></p>
        <p><strong>Saldo Anda:</strong> Rp <?= number_format($saldo, 0, ',', '.') ?></p>
    </div>
    <div class="action-buttons">
        <a href="tambah_saldo.php" class="btn">Tambah Saldo</a>
        <a href="tarik_saldo.php" class="btn">Tarik Saldo</a>
    </div>
    <a href="dashboard.php" class="back-link">⬅️ Kembali ke Dashboard</a>
</div>
</body>
</html>
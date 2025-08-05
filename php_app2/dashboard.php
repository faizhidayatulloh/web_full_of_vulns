<?php
require 'config.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$nama = $_SESSION['user']['nama'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Selamat datang, <?= htmlspecialchars($nama) ?></h2>
    <a href="cek_saldo.php">💰 Cek Saldo</a><br>
    <a href="tambah_saldo.php">➕ Tambah Saldo</a><br>
    <a href="tarik_saldo.php">➖ Tarik Saldo</a><br>
    <a href="detail_akun.php">👤 Detail Akun</a><br>
    <a href="ubah_password.php">🔒 Ubah Password</a><br>
    <a href="logout.php">🚪 Logout</a>
</div>
</body>
</html>
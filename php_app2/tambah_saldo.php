<?php
require 'config.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'];
    if (!isset($token) || $token !== $_COOKIE['csrf_cookie']) {
        die("CSRF token tidak valid.");
    }

    $tambah = (int)$_POST['tambah'];
    $id = $_SESSION['user']['id'];

    if ($tambah <= 0) {
        $error = "Jumlah harus lebih dari 0.";
    } else {
        $conn->query("UPDATE data SET jumlah_uang = jumlah_uang + $tambah WHERE id = $id");
        $_SESSION['user']['jumlah_uang'] += $tambah;
        header("Location: cek_saldo.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Saldo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Tambah Saldo</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="number" name="tambah" placeholder="Masukkan jumlah" required min="1">
        <button type="submit">Tambah</button>
    </form>
    <a href="dashboard.php">⬅️ Kembali ke Dashboard</a>
</div>
<script>
document.cookie = "csrf_cookie=<?= $_SESSION['csrf_token'] ?>";
</script>
</body>
</html>
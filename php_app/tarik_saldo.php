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

    $tarik = (int)$_POST['tarik'];
    $id = $_SESSION['user']['id'];

    if ($tarik <= 0) {
        $error = "Jumlah tidak valid.";
    } elseif ($_SESSION['user']['jumlah_uang'] >= $tarik) {
        $conn->query("UPDATE data SET jumlah_uang = jumlah_uang - $tarik WHERE id = $id");
        $_SESSION['user']['jumlah_uang'] -= $tarik;
        header("Location: cek_saldo.php");
        exit;
    } else {
        $error = "Saldo tidak cukup!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tarik Saldo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Tarik Saldo</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="number" name="tarik" placeholder="Masukkan jumlah" required min="1">
        <button type="submit">Tarik</button>
    </form>
    <a href="dashboard.php">⬅️ Kembali ke Dashboard</a>
</div>
<script>
document.cookie = "csrf_cookie=<?= $_SESSION['csrf_token'] ?>";
</script>
</body>
</html>
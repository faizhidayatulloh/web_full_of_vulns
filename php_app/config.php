<?php
$host = "localhost";
$user = "faiz";
$pass = "alinda";
$db   = "bank";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Session cookie settings with SameSite=Lax
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => false, // true jika menggunakan HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

// Generate CSRF token from username if user is logged in
if (isset($_SESSION['user'])) {
    $_SESSION['csrf_token'] = base64_encode($_SESSION['user']['nama']);
}
?>
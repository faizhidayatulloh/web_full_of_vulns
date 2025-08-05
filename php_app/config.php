<?php
$host = "localhost";
$user = "faiz";
$pass = "alinda";
$db   = "bank";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cookie settings
$cookie_params = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => $cookie_params["lifetime"],
    'path' => $cookie_params["path"],
    'domain' => $cookie_params["domain"],
    'secure' => false,    // Set to true if using HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

// Generate CSRF token from username if user is logged in
if (isset($_SESSION['user'])) {
    $_SESSION['csrf_token'] = base64_encode($_SESSION['user']['nama']);
}
?>
<?php
$host = "localhost";
$user = "faiz";
$pass = "alinda";
$db   = "bank";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
session_start();

// Generate CSRF token from username if user is logged in
if (isset($_SESSION['user'])) {
    $_SESSION['csrf_token'] = base64_encode($_SESSION['user']['nama']);
}
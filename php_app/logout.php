<?php
require 'config.php';

// Verify logout token
if (isset($_COOKIE['logout_token']) && isset($_SESSION['logout_token'])) {
    if ($_COOKIE['logout_token'] === $_SESSION['logout_token']) {
        // Clear the cookie with SameSite=Lax
        setcookie('logout_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        unset($_SESSION['logout_token']);
    }
}

session_destroy();
header("Location: index.php");
exit;
?>
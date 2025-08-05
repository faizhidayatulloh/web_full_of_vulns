<?php
require 'config.php';

// Verify logout token
if (isset($_COOKIE['logout_token']) && isset($_SESSION['logout_token'])) {
    if ($_COOKIE['logout_token'] === $_SESSION['logout_token']) {
        // Clear the cookie
        setcookie('logout_token', '', time() - 3600, "/", "", false, true);
        unset($_SESSION['logout_token']);
    }
}

session_destroy();
header("Location: index.php");
exit;
?>
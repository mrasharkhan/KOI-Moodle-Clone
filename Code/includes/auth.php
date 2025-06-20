<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user']);
}

// Return current logged in user
function current_user() {
    return $_SESSION['user'] ?? null;
}

// Redirect to login if not logged in
function require_login() {
    if (!is_logged_in()) {
        header("Location: ../views/login.php");
        exit();
    }
}

// Destroy session and redirect to home
function logout() {
    $_SESSION = [];
    session_destroy();
    header("Location: ../index.php");
    exit();
}

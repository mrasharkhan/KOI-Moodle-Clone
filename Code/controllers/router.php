<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

$action = $_GET['action'] ?? '';

if ($action === 'logout') {
    session_destroy();
    header("Location: /views/login.php");
    exit;
}

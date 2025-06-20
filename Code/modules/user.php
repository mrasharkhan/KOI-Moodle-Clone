<?php
include_once __DIR__ . '/../includes/db.php';

// Get user by ID
function get_user_by_id($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Update user email or password
function update_user($id, $email, $password = null) {
    global $conn;
    if ($password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $email, $hashed, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $email, $id);
    }
    return $stmt->execute();
}

<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
require_login();

$user = current_user();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $newPassword = $_POST['password'] ?? '';
    $userId = $user['id'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format.";
    } else {
        // Update email
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
    }
    if (!filter_var($full_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $msg = "Invalid Name format.";
    } else {
        // Update name
        $stmt = $conn->prepare("UPDATE users SET full_name = ? WHERE id = ?");
        $stmt->bind_param("si", $full_name, $userId);
        $stmt->execute();
    }
        // Update password if provided
        if (!empty($newPassword)) {
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed, $userId);
            $stmt->execute();
        }

        $_SESSION['user']['email'] = $email;
        $msg = "Profile updated successfully.";
    }

?>

<?php include '../templates/header.php'; ?>
<h2>Update Profile</h2>
<form method="POST">
    <label>New Email:</label>
    <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required><br>
    <label>New Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

    <label>New Password (leave blank to keep current):</label>
    <input type="password" name="password"><br>

    <button type="submit">Update</button>
</form>
<p><?= $msg ?></p>
<?php include '../templates/footer.php'; ?>

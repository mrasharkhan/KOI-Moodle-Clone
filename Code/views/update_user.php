<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_login();
$user = current_user();
if ($user['role'] !== 'admin') header("Location: dashboard.php");

$id = $_GET['id'] ?? null;
$error = '';
$success = '';

// On form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $role      = $_POST['role'];
    if (!$full_name || !$email) {
        $error = "Name and email are required.";
    } else {
        if ($id) {
            $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, role=? WHERE id=?");
            $stmt->bind_param("sssi", $full_name, $email, $role, $id);
        } else {
            // New user, default password = 'password'
            $passHash = password_hash('password', PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (full_name,email,password,role,created_at) VALUES(?,?,?,?,NOW())");
            $stmt->bind_param("ssss", $full_name, $email, $passHash, $role);
        }
        $stmt->execute();
        $success = $id ? "User updated." : "User created.";
        if (!$id) $id = $stmt->insert_id;
    }
}

// Fetch for edit
if ($id && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $row = $conn->query("SELECT * FROM users WHERE id = ".intval($id))->fetch_assoc();
    $full_name = $row['full_name'];
    $email     = $row['email'];
    $role      = $row['role'];
}

include '../templates/header.php';
?>

<div class="dashboard-container">
  <h2><?= $id ? "Edit User" : "Add New User" ?></h2>
  <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
  <?php if ($success): ?><p style="color:green;"><?= $success ?></p><?php endif; ?>

  <form method="POST">
    <label>Full Name:</label><br>
    <input type="text" name="full_name" value="<?= htmlspecialchars($full_name ?? '') ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required><br><br>

    <label>Role:</label><br>
    <select name="role">
      <?php foreach (['admin','lecturer','student'] as $r): ?>
        <option value="<?= $r ?>" <?= ($role ?? '') === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
      <?php endforeach; ?>
    </select><br><br>

    <button type="submit"><?= $id ? "Update" : "Create" ?></button>
  </form>
</div>

<?php include '../templates/footer.php'; ?>

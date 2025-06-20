<?php
session_start();
require_once '../includes/db.php';

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $reset = $stmt->get_result()->fetch_assoc();

        if ($reset) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed, $reset['email']);
            $stmt->execute();

            $conn->query("DELETE FROM password_resets WHERE email = '{$reset['email']}'");

            $success = "Password has been reset. You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Invalid or expired reset token.";
        }
    }
}
?>

<?php include '../templates/header.php'; ?>

<main class="container">
  <section class="form-container">
    <h2>Set New Password</h2>

    <?php if ($success): ?>
      <p style="color: green;"><?= $success ?></p>
    <?php elseif ($error): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

      <label for="password">New Password:</label>
      <input type="password" name="password" id="password" required>

      <label for="confirm">Confirm Password:</label>
      <input type="password" name="confirm" id="confirm" required>

      <button type="submit">Reset Password</button>
    </form>
    <?php endif; ?>
  </section>
</main>

<?php include '../templates/footer.php'; ?>

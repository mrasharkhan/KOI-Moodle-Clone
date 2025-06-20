<?php
session_start();
require_once '../includes/db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        // Generate token
        $token = bin2hex(random_bytes(16));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Delete old reset if exists
        $conn->query("DELETE FROM password_resets WHERE email = '$email'");

        // Insert new token
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expires);
        $stmt->execute();

        // Simulated reset link
        $reset_link = "http://localhost/KOI_MOODLE_Site/views/reset_password.php?token=$token";
        $message = "Reset link (demo): <a href='$reset_link'>$reset_link</a>";
    } else {
        $error = "No account found with that email.";
    }
}
?>

<?php include '../templates/header.php'; ?>

<main class="container">
  <section class="form-container">
    <h2>Reset Your Password</h2>

    <?php if ($message): ?>
      <p style="color: green;"><?= $message ?></p>
    <?php elseif ($error): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" novalidate>
      <label for="email">Enter your email:</label>
      <input type="email" name="email" id="email" required>

      <button type="submit">Send Reset Link</button>
    </form>
  </section>
</main>

<?php include '../templates/footer.php'; ?>

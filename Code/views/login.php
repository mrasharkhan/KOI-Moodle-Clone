<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        header("Location: ../views/dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<?php include '../templates/header.php'; ?>

<main class="container">
  <section class="form-container">
    <h2>Login to Your Account</h2>
    <?php if (!empty($error)): ?>
      <p class="error" style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" novalidate>
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required />

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required />

      <button type="submit">Login</button>
    </form>

    <p><a href="reset_request.php">Forgot password?</a></p>
  </section>
</main>

<?php include '../templates/footer.php'; ?>

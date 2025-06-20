<?php
require_once '../includes/db.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $email, $password, $role);

    if ($stmt->execute()) {
        $success = "Registered successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<?php include '../templates/header.php'; ?>
<main class="container register-page">
  <h2>Register</h2>
  <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
  <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

  <form method="POST">
    <label>Full Name:</label>
    <input type="text" name="full_name" required>
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <label>Role:</label>
    <select name="role">
      <option value="student">Student</option>
      <option value="lecturer">Lecturer</option>
    </select>
    <button type="submit">Register</button>
  </form>
</main>
<?php include '../templates/footer.php'; ?>

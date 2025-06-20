<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>KOI Moodle</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <script src="../assets/js/main.js" defer></script>
</head>
<body>
<header class="site-header">
  <div class="container">
    <h1>KOI Moodle</h1>
    <nav>
      <?php if (!$user): ?>
        <a href="../index.php">Home</a>
        <a href="../views/about.php">About</a>
        <a href="../views/courses.php">Courses</a>
        <a href="../views/register.php">Register</a>
        <a href="../views/login.php" id="openLogin">Login</a>
      <?php else: ?>
        <a href="../views/dashboard.php">Dashboard</a>
        <a href="../views/update_profile.php">Update Profile</a>

        <?php if ($user['role'] === 'admin'): ?>
          <a href="../views/admin_dashboard.php">Admin Panel</a>
        <?php elseif ($user['role'] === 'student'): ?>
          <a href="../views/student_dashboard.php">Student Portal</a>
        <?php elseif ($user['role'] === 'lecturer'): ?>
          <a href="../views/lecturer_dashboard.php">Lecturer Portal</a>
        <?php endif; ?>

        <a href="../views/logout.php">Logout</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">

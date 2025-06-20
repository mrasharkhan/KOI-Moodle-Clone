<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_login();

$user = current_user();
if ($user['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

include '../templates/header.php';
?>

<div class="dashboard-container">
  <div class="dashboard-header">
    <h2>Welcome, <?= htmlspecialchars($user['full_name']) ?> (Admin)</h2>
    <p>Use the options below to manage users, courses, and site data.</p>
  </div>

  <div class="dashboard-section">
    <div class="dashboard-card">
      <h4>👥 User Management</h4>
      <a href="manage_users.php" class="btn-link">Manage Users</a>
    </div>

    <div class="dashboard-card">
      <h4>📚 Course Management</h4>
      <a href="manage_courses.php" class="btn-link">Manage Courses</a>
    </div>

<?php include '../templates/footer.php'; ?>

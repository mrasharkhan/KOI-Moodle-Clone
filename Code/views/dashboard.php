<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
require_login();

$user = current_user();

// Re-fetch user details from DB to show up-to-date info
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$details = $stmt->get_result()->fetch_assoc();

include '../templates/header.php';
?>

<div class="dashboard-container">
  <div class="dashboard-header">
    <h2>Welcome, <?= htmlspecialchars($details['full_name']) ?>!</h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($details['email']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($details['role']) ?></p>
  </div>

  <div class="dashboard-section">
    <div class="dashboard-card">
      <h4>Update Your Details</h4>
      <a href="update_profile.php" class="btn-link">Update Profile</a>
    </div>

    <?php if ($details['role'] === 'student'): ?>
      <div class="dashboard-card">
        <h4>Student Tools</h4>
        <a href="student_dashboard.php" class="btn-link">Go to Student Dashboard</a>
      </div>
    <?php elseif ($details['role'] === 'lecturer'): ?>
      <div class="dashboard-card">
        <h4>Lecturer Tools</h4>
        <a href="lecturer_dashboard.php" class="btn-link">Go to Lecturer Dashboard</a>
      </div>
    <?php elseif ($details['role'] === 'admin'): ?>
      <div class="dashboard-card">
        <h4>Admin Tools</h4>
        <a href="admin_dashboard.php" class="btn-link">Go to Admin Dashboard</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include '../templates/footer.php'; ?>

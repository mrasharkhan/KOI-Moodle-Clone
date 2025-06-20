<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_login();
$user = current_user();
if ($user['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delStmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delStmt->bind_param("i", $_GET['delete_id']);
    $delStmt->execute();
    header("Location: manage_users.php");
    exit;
}

include '../templates/header.php';

// Fetch all users
$result = $conn->query("SELECT id, full_name, email, role, created_at FROM users");

?>

<div class="dashboard-container">
  <div class="dashboard-header">
    <h2>Manage Users</h2>
    <p><a href="update_user.php" class="btn-link">＋ Add New User</a></p>
  </div>

  <?php if ($result && $result->num_rows): ?>
    <table class="submission-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Joined</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($u = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['full_name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td><?= htmlspecialchars($u['created_at']) ?></td>
            <td>
              <a href="update_user.php?id=<?= $u['id'] ?>" class="btn-link">Edit</a>
              <a href="manage_users.php?delete_id=<?= $u['id'] ?>"
                 onclick="return confirm('Delete user <?= htmlspecialchars($u['full_name']) ?>?');"
                 class="btn-link" style="margin-left:1rem;color:red;">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No users found.</p>
  <?php endif; ?>
</div>

<?php include '../templates/footer.php'; ?>

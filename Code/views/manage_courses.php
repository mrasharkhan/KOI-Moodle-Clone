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
    $del = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $del->bind_param("i", $_GET['delete_id']);
    $del->execute();
    header("Location: manage_courses.php");
    exit;
}

include '../templates/header.php';

// Fetch courses + lecturer
$sql = "
  SELECT c.id, c.title, c.description, u.full_name AS lecturer
  FROM courses c
  LEFT JOIN users u ON c.lecturer_id = u.id
";
$result = $conn->query($sql);
?>

<div class="dashboard-container">
  <div class="dashboard-header">
    <h2>Manage Courses</h2>
    <p><a href="update_course.php" class="btn-link">＋ Add New Course</a></p>
  </div>

  <?php if ($result && $result->num_rows): ?>
    <table class="submission-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Lecturer</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($c = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['title']) ?></td>
            <td><?= htmlspecialchars($c['description']) ?></td>
            <td><?= htmlspecialchars($c['lecturer'] ?? 'TBA') ?></td>
            <td>
              <a href="update_course.php?id=<?= $c['id'] ?>" class="btn-link">Edit</a>
              <a href="manage_courses.php?delete_id=<?= $c['id'] ?>"
                 onclick="return confirm('Delete course <?= htmlspecialchars($c['title']) ?>?');"
                 class="btn-link" style="margin-left:1rem;color:red;">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No courses found.</p>
  <?php endif; ?>
</div>

<?php include '../templates/footer.php'; ?>

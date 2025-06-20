<?php
require_once '../includes/auth.php';
require_once '../modules/course.php';
require_once '../includes/db.php';

require_login();
$user = current_user();

if ($user['role'] !== 'lecturer') {
    header("Location: dashboard.php");
    exit;
}

$courses = get_courses_by_lecturer($user['id']);

include '../templates/header.php';
?>

<div class="dashboard-container">
  <div class="dashboard-header">
    <h2>Welcome, <?= htmlspecialchars($user['full_name']) ?> (Lecturer)</h2>
    <p>Manage your units, assignments, and student grades from here.</p>
  </div>

  <div class="dashboard-section">
    <h3>Your Units</h3>

    <?php if (count($courses) > 0): ?>
      <?php foreach ($courses as $course): ?>
        <div class="dashboard-card">
          <h4><?= htmlspecialchars($course['title']) ?></h4>
          <a href="add_assignment.php?course_id=<?= $course['id'] ?>" class="btn-link">➕ Add Assignment</a>
          <a href="grade_students.php?course_id=<?= $course['id'] ?>" class="btn-link" style="margin-left: 1rem;">📝 Grade Students</a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>You are not assigned to any units.</p>
    <?php endif; ?>
  </div>
</div>

<?php include '../templates/footer.php'; ?>

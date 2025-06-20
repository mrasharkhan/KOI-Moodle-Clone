<?php
require_once '../includes/auth.php';
require_once '../modules/course.php';
require_once '../includes/db.php';
require_once '../modules/assignment.php';

require_login();
$user = current_user();

if ($user['role'] !== 'student') {
    header("Location: dashboard.php");
    exit;
}

$courses = get_courses_by_student($user['id']);
$grades = get_grades_by_student($user['id']);

include '../templates/header.php';
?>

<div class="dashboard-container">
  <div class="dashboard-header">
    <h2>Welcome, <?= htmlspecialchars($user['full_name']) ?> (Student)</h2>
    <p>Here's an overview of your enrolled courses, grades, and submissions.</p>
  </div>

  <div class="dashboard-section">
    <h3>Your Courses & Assignments</h3>
    <?php if (count($courses) > 0): ?>
      <?php foreach ($courses as $course): ?>
        <div class="dashboard-card">
          <h4><?= htmlspecialchars($course['title']) ?></h4>
          <ul>
            <?php
              $assignments = get_assignments_by_course($course['id']);
              if (count($assignments) > 0):
                foreach ($assignments as $a):
            ?>
              <li>
                <?= htmlspecialchars($a['title']) ?>
                <a href="submit_assignment.php?assignment_id=<?= $a['id'] ?>" class="btn-link" style="margin-left: 10px;">📤 Submit</a>
              </li>
            <?php endforeach; else: ?>
              <li>No assignments for this course.</li>
            <?php endif; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>You are not enrolled in any units.</p>
    <?php endif; ?>
  </div>

  <div class="dashboard-section">
    <h3>Your Grades</h3>
    <?php if (count($grades) > 0): ?>
      <ul class="dashboard-list">
        <?php foreach ($grades as $g): ?>
          <li>
            <strong><?= htmlspecialchars($g['course_name']) ?></strong> –
            <?= htmlspecialchars($g['title']) ?>:
            <span class="grade"><?= htmlspecialchars($g['grade']) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>No grades available yet.</p>
    <?php endif; ?>
  </div>

  <div class="dashboard-section">
    <a href="register_course.php" class="btn-link">📚 Register for a Course</a>
  </div>
</div>

<?php include '../templates/footer.php'; ?>

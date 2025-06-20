<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_login();

$user = current_user();
if ($user['role'] !== 'lecturer') {
    header("Location: dashboard.php");
    exit;
}

$course_id = $_GET['course_id'] ?? null;
$success_msg = "";

// Fetch submissions for this lecturer's course
$submissions = [];
if ($course_id) {
    $sql = "
        SELECT 
            s.id AS submission_id,
            u.full_name AS student_name,
            a.title AS assignment_title,
            c.title AS course_title,
            s.grade,
            s.submission_file,
            u.id AS student_id,
            a.id AS assignment_id
        FROM submissions s
        JOIN users u ON s.user_id = u.id
        JOIN assignments a ON s.assignment_id = a.id
        JOIN courses c ON a.course_id = c.id
        WHERE c.id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $submissions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Handle grade update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submission_id = $_POST['submission_id'];
    $grade = $_POST['grade'];

    $stmt = $conn->prepare("UPDATE submissions SET grade = ? WHERE id = ?");
    $stmt->bind_param("si", $grade, $submission_id);
    $stmt->execute();

    $success_msg = "✅ Grade updated successfully.";
    // Refresh the page to reload updated data
    header("Location: grade_students.php?course_id=$course_id&success=1");
    exit;
}

if (isset($_GET['success'])) {
    $success_msg = "✅ Grade updated successfully.";
}

include '../templates/header.php';
?>

<h2>Grade Students</h2>

<?php if ($success_msg): ?>
  <p style="color: green;"><?= $success_msg ?></p>
<?php endif; ?>

<?php if (empty($submissions)): ?>
  <p>No student submissions found for this course.</p>
<?php else: ?>
  <table border="1" cellpadding="8" cellspacing="0">
    <thead>
      <tr>
        <th>Course</th>
        <th>Assignment</th>
        <th>Student</th>
        <th>File</th>
        <th>Grade</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($submissions as $submission): ?>
        <tr>
          <td><?= htmlspecialchars($submission['course_title']) ?></td>
          <td><?= htmlspecialchars($submission['assignment_title']) ?></td>
          <td><?= htmlspecialchars($submission['student_name']) ?></td>
          <td>
            <?php if (!empty($submission['submission_file'])): ?>
              <a href="../uploads/<?= htmlspecialchars($submission['submission_file']) ?>" download>Download</a>
            <?php else: ?>
              No file
            <?php endif; ?>
          </td>
          <td>
            <form method="POST" style="display: inline-block;">
              <input type="hidden" name="submission_id" value="<?= $submission['submission_id'] ?>">
              <input type="text" name="grade" value="<?= htmlspecialchars($submission['grade']) ?>" required style="width: 50px;">
          </td>
          <td>
              <button type="submit">Update</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?php include '../templates/footer.php'; ?>

<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

require_login();
$user = current_user();

if ($user['role'] !== 'student') {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'];

    // Prevent duplicate enrollments
    $stmt = $conn->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->bind_param("ii", $user['id'], $course_id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();

    if (!$existing) {
        $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user['id'], $course_id);
        $stmt->execute();
        $success = "Successfully registered!";
    } else {
        $error = "Already registered in this course.";
    }
}

// Get available courses
$courses = $conn->query("SELECT * FROM courses")->fetch_all(MYSQLI_ASSOC);

include '../templates/header.php';
?>

<h2>Register for a Unit</h2>

<?php if (!empty($success)): ?><p style="color: green;"><?= $success ?></p><?php endif; ?>
<?php if (!empty($error)): ?><p style="color: red;"><?= $error ?></p><?php endif; ?>

<form method="POST">
  <label>Select a course:</label>
  <select name="course_id" required>
    <?php foreach ($courses as $course): ?>
      <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
    <?php endforeach; ?>
  </select>
  <br><br>
  <button type="submit">Register</button>
</form>

<?php include '../templates/footer.php'; ?>

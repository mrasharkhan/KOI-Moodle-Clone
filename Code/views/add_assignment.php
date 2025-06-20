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
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = $_POST['due_date'];

    if (!$title || !$description || !$due_date) {
        $errors[] = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO assignments (title, description, course_id, due_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $title, $description, $course_id, $due_date);
        if ($stmt->execute()) {
            header("Location: lecturer_dashboard.php");
            exit;
        } else {
            $errors[] = "Failed to add assignment.";
        }
    }
}

include '../templates/header.php';
?>

<h2>Add Assignment</h2>
<?php if (!empty($errors)): ?>
  <div class="error"><?= implode('<br>', $errors) ?></div>
<?php endif; ?>
<form method="POST">
  <label>Title:</label><br>
  <input type="text" name="title" required><br>

  <label>Description:</label><br>
  <textarea name="description" required></textarea><br>

  <label>Due Date:</label><br>
  <input type="date" name="due_date" required><br><br>

  <button type="submit">Add Assignment</button>
</form>

<?php include '../templates/footer.php'; ?>

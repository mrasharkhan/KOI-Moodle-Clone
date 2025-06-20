<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

require_login();
$user = current_user();

if ($user['role'] !== 'student') {
    header("Location: dashboard.php");
    exit;
}

$assignment_id = $_GET['assignment_id'] ?? null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $uploadDir = '../assets/doc/';
    $filename = basename($file['name']);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $stmt = $conn->prepare("INSERT INTO submissions (user_id, assignment_id, submission_file) 
                                VALUES (?, ?, ?) 
                                ON DUPLICATE KEY UPDATE submission_file = VALUES(submission_file)");
        $stmt->bind_param("iis", $user['id'], $assignment_id, $targetPath);
        $stmt->execute();
        $success = true;
    }
}

include '../templates/header.php';
?>

<div class="form-container">
  <h2>Submit Assignment</h2>
  <?php if ($success): ?>
    <p class="success">Assignment submitted successfully!</p>
  <?php endif; ?>
  <form method="POST" enctype="multipart/form-data">
    <label for="file">Upload your file (PDF, DOC, etc.):</label><br>
    <input type="file" name="file" required><br><br>
    <button type="submit">Submit</button>
  </form>
</div>

<?php include '../templates/footer.php'; ?>

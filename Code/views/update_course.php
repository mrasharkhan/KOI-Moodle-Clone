<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_login();
$user = current_user();
if ($user['role'] !== 'admin') header("Location: dashboard.php");

$id = $_GET['id'] ?? null;
$error = '';
$success = '';

// Fetch lecturers for dropdown
$lecturers = $conn->query("SELECT id, full_name FROM users WHERE role='lecturer'")->fetch_all(MYSQLI_ASSOC);

// On submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $desc  = trim($_POST['description']);
    $lect  = $_POST['lecturer_id'] ?: null;

    if (!$title) {
        $error = "Title is required.";
    } else {
        if ($id) {
            $stmt = $conn->prepare("UPDATE courses SET title=?, description=?, lecturer_id=? WHERE id=?");
            $stmt->bind_param("ssii", $title, $desc, $lect, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO courses (title, description, lecturer_id) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $title, $desc, $lect);
        }
        $stmt->execute();
        $success = $id ? "Course updated." : "Course created.";
        if (!$id) $id = $stmt->insert_id;
    }
}

// Load existing
if ($id && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $row = $conn->query("SELECT * FROM courses WHERE id=".intval($id))->fetch_assoc();
    $title = $row['title'];
    $desc  = $row['description'];
    $lect  = $row['lecturer_id'];
}

include '../templates/header.php';
?>

<div class="dashboard-container">
  <h2><?= $id ? "Edit Course" : "Add New Course" ?></h2>
  <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
  <?php if ($success): ?><p style="color:green;"><?= $success ?></p><?php endif; ?>

  <form method="POST">
    <label>Title:</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($title ?? '') ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="description"><?= htmlspecialchars($desc ?? '') ?></textarea><br><br>

    <label>Lecturer:</label><br>
    <select name="lecturer_id">
      <option value="">-- none --</option>
      <?php foreach ($lecturers as $l): ?>
        <option value="<?= $l['id'] ?>" <?= ($lect ?? '') == $l['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($l['full_name']) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <button type="submit"><?= $id ? "Update" : "Create" ?></button>
  </form>
</div>

<?php include '../templates/footer.php'; ?>

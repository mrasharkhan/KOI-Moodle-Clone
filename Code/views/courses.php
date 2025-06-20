<?php
require_once '../includes/db.php';
include_once '../templates/header.php';

// Fetch courses with lecturer name
$sql = "SELECT courses.*, users.full_name AS lecturer_name 
        FROM courses 
        LEFT JOIN users ON courses.lecturer_id = users.id";

$result = $conn->query($sql);
?>

<h2>Available Courses</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <ul class="course-list">
        <?php while ($course = $result->fetch_assoc()): ?>
            <li>
                <strong><?= htmlspecialchars($course['title']) ?></strong><br>
                <?= nl2br(htmlspecialchars($course['description'])) ?><br>
                <em>Lecturer:</em> <?= htmlspecialchars($course['lecturer_name'] ?? 'TBA') ?>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>No courses found.</p>
<?php endif; ?>

<?php include_once '../templates/footer.php'; ?>

<?php
include_once __DIR__ . '/../includes/db.php';

function get_all_courses() {
    global $conn;
    $result = $conn->query("SELECT * FROM courses");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_courses_by_student($student_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT c.* FROM courses c
        JOIN enrollments e ON c.id = e.course_id
        WHERE e.user_id = ?
    ");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


function enroll_student($student_id, $course_id) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO student_units (student_id, course_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $student_id, $course_id);
    return $stmt->execute();
}
function get_courses_by_lecturer($lecturer_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM courses WHERE lecturer_id = ?");
    $stmt->bind_param("i", $lecturer_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
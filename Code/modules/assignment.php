<?php
include_once __DIR__ . '/../includes/db.php';

function get_assignments_by_course($course_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM assignments WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function submit_grade($assignment_id, $student_id, $grade) {
    global $conn;
    $stmt = $conn->prepare("REPLACE INTO grades (assignment_id, student_id, grade) VALUES (?, ?, ?)");
    $stmt->bind_param("iid", $assignment_id, $student_id, $grade);
    return $stmt->execute();
}

function get_grades_by_student($student_id) {
    global $conn;

    $sql = "SELECT c.title AS course_name, a.title, s.grade
            FROM submissions s
            JOIN assignments a ON s.assignment_id = a.id
            JOIN courses c ON a.course_id = c.id
            WHERE s.user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


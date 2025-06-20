<?php
// setup_db.php
// Run this once to (re)create koi_moodle and its tables.

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'koi_moodle';

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Drop and recreate database
$conn->query("DROP DATABASE IF EXISTS `$dbname`");
$conn->query("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($dbname);

// Use InnoDB for FK support
$sql = "
-- users must come first (referenced by courses, enrollments, submissions)
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','lecturer','student') DEFAULT 'student',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- courses references users
CREATE TABLE `courses` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `lecturer_id` INT(11),
  FOREIGN KEY (`lecturer_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- assignments references courses
CREATE TABLE `assignments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` INT(11),
  `title` VARCHAR(255),
  `description` TEXT,
  `due_date` DATE,
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- submissions references users and assignments
CREATE TABLE `submissions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11),
  `assignment_id` INT(11),
  `submission_file` VARCHAR(255),
  `grade` DECIMAL(5,2),
  `submitted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`assignment_id`) REFERENCES `assignments`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- enrollments references users and courses
CREATE TABLE `enrollments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11),
  `course_id` INT(11),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- password_resets standalone
CREATE TABLE `password_resets` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(100),
  `token` VARCHAR(100),
  `expires_at` DATETIME
) ENGINE=InnoDB;
";
// Insert sample users
$conn->query("INSERT INTO users (full_name, email, password, role) VALUES
    ('Admin User', 'admin@koi.edu.au', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'admin'),
    ('John Lecturer', 'lecturer@koi.edu.au', '" . password_hash('lect123', PASSWORD_DEFAULT) . "', 'lecturer'),
    ('Alice Student', 'alice@student.koi.edu.au', '" . password_hash('alice123', PASSWORD_DEFAULT) . "', 'student'),
    ('Bob Student', 'bob@student.koi.edu.au', '" . password_hash('bob123', PASSWORD_DEFAULT) . "', 'student')
");

// Get lecturer ID
$lecturer_id = $conn->insert_id - 2;

// Insert course
$conn->query("INSERT INTO courses (title, description, lecturer_id) VALUES
    ('Web Development 101', 'Intro course on HTML, CSS, PHP', $lecturer_id)
");

// Get course ID
$course_id = $conn->insert_id;

// Enroll students
$conn->query("INSERT INTO enrollments (user_id, course_id) VALUES
    (3, $course_id), (4, $course_id)
");

// Insert assignment
$conn->query("INSERT INTO assignments (course_id, title, description, due_date) VALUES
    ($course_id, 'Assignment 1: Build a Login Page', 'Use HTML, CSS and PHP to build a login page.', '2025-06-01')
");

// Insert submissions
$conn->query("INSERT INTO submissions (user_id, assignment_id, submission_file, grade) VALUES
    (3, 1, '\assets\doc\alice_assignment.pdf', 85.5),
    (4, 1, '\assets\doc\bob_assignment.pdf', 78.0)
");


if ($conn->multi_query($sql)) {
    // consume all results
    do {
        $conn->store_result();
    } while ($conn->more_results() && $conn->next_result());
    echo "Database and tables created successfully.";
} else {
    echo "Error creating tables: " . $conn->error;
}

$conn->close();
?>


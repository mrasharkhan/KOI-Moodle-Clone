<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>KOI LMS</title>
  <link rel="stylesheet" href="./assets/css/style.css"/>
</head>
<body>

  <!-- Header -->
  <header class="site-header">
    <div class="container">
      <h1>KOI Learning Management System</h1>
      <nav>
        <a href="index.php">Home</a>
        <a href="./views/about.php">About</a>
        <a href="./views/courses.php">Courses</a>
        <a href="./views/register.php">Register</a>
        <a href="./views/login.php">Login</a>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <h2>Empowering IT Education at KOI</h2>
      <p>Access course materials, submit assignments, participate in discussions and more.</p>
      <div class="hero-buttons">
        <a href="./views/login.php" class="btn-primary">Login</a>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section class="about container">
    <h3>Welcome to KOI's Moodle-Style LMS</h3>
    <p>This platform is designed to support IT students and educators with course content, scheduling, announcements, and grading all in one place.</p>
  </section>

  <!-- Features Section -->
  <section class="features container">
    <h3>Key Features</h3>
    <ul>
      <li>Secure student and admin logins</li>
      <li>Course content management</li>
      <li>Assignment uploads and grading</li>
      <li>Email password resets</li>
      <li>Mobile responsive design</li>
    </ul>
  </section>

  <!-- Footer -->
  
   <?php include './templates/footer.php'; ?>
 

</body>
</html>

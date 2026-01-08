<?php
declare(strict_types=1);
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/csrf.php';

$student = $_SESSION['student'] ?? null;
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Student Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">Student Portal</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <?php if ($student): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="grades.php">Grades</a></li>
          <li class="nav-item"><a class="nav-link" href="courses.php">Courses</a></li>
          <li class="nav-item"><a class="nav-link" href="registrations.php">Registrations</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>

      <ul class="navbar-nav">
        <?php if ($student): ?>
          <li class="nav-item"><span class="nav-link">Xin chào, <?= htmlspecialchars((string)($student['full_name'] ?? $student['student_code'] ?? '')) ?></span></li>
          <li class="nav-item">
            <form method="post" action="logout.php" class="d-inline">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
              <button class="btn btn-link nav-link" type="submit" style="display:inline;padding:0;">Logout</button>
            </form>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
  <?php if ($m = get_flash('success')): ?>
    <div class="alert alert-success"><?= htmlspecialchars($m) ?></div>
  <?php endif; ?>
  <?php if ($m = get_flash('error')): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($m) ?></div>
  <?php endif; ?>
  <?php if ($m = get_flash('info')): ?>
    <div class="alert alert-info"><?= htmlspecialchars($m) ?></div>
  <?php endif; ?>
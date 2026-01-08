<?php
// student/profile.php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_login();

$student = current_student();

require_once __DIR__ . '/../includes/header.php';
?>
<h1>Hồ sơ sinh viên</h1>
<table class="table">
  <tr><th>Mã SV</th><td><?= htmlspecialchars($student['student_code'] ?? '') ?></td></tr>
  <tr><th>Họ tên</th><td><?= htmlspecialchars($student['full_name'] ?? '') ?></td></tr>
  <tr><th>Lớp</th><td><?= htmlspecialchars($student['class_name'] ?? '') ?></td></tr>
  <tr><th>Email</th><td><?= htmlspecialchars($student['email'] ?? '') ?></td></tr>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
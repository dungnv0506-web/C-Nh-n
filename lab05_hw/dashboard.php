<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/flash.php';
require_once __DIR__ . '/includes/data.php'; // if you need data helpers
if (session_status() === PHP_SESSION_NONE) session_start();

require_login();

$student = current_student(); // guaranteed array by current_student()
$displayName = (string)($student['full_name'] ?? $student['student_code'] ?? '');

require_once __DIR__ . '/includes/header.php';
?>
<h1>Dashboard</h1>
<p>Xin chào, <strong><?= htmlspecialchars($displayName) ?></strong></p>

<p><a href="student/profile.php" class="btn btn-sm btn-primary">Xem hồ sơ</a></p>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
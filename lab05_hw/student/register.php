<?php
// student/register.php (POST)
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/csrf.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify($_POST['csrf'] ?? null)) {
    http_response_code(400); exit('Bad Request');
}

$student = current_student();
$code = $student['student_code'] ?? '';
$courseCode = trim($_POST['course_code'] ?? '');

$enrollments = read_json('enrollments.json', []);
foreach ($enrollments as $e) {
    if (($e['student_code'] ?? '') === $code && ($e['course_code'] ?? '') === $courseCode) {
        set_flash('error', 'Bạn đã đăng ký học phần này.');
        header('Location: courses.php'); exit;
    }
}

$enrollments[] = [
    'student_code' => $code,
    'course_code' => $courseCode,
    'created_at' => date('Y-m-d H:i:s')
];
write_json('enrollments.json', $enrollments);
set_flash('success', 'Đăng ký học phần thành công.');
header('Location: student/registrations.php');
exit;
?>
<?php
// student/unregister.php (POST)
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

$grades = read_json('grades.json', []);
foreach ($grades as $g) {
    if (($g['student_code'] ?? '') === $code && ($g['course_code'] ?? '') === $courseCode) {
        set_flash('error', 'Không thể hủy: học phần đã có điểm.');
        header('Location: registrations.php'); exit;
    }
}

$enrollments = read_json('enrollments.json', []);
$enrollments = array_values(array_filter($enrollments, function($e) use ($code, $courseCode) {
    return !(($e['student_code'] ?? '') === $code && ($e['course_code'] ?? '') === $courseCode);
}));
write_json('enrollments.json', $enrollments);
set_flash('info', 'Đã hủy đăng ký học phần.');
header('Location: registrations.php');
exit;
?>
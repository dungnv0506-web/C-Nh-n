<?php
declare(strict_types=1);
// includes/auth.php
// Không đ��nh nghĩa lại data_path() ở đây. Nếu cần hàm đọc/ghi json thì require_once 'data.php'.
// Sử dụng require_once để tránh include nhiều lần.

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/data.php';

if (!function_exists('require_login')) {
    function require_login(string $redirect = 'login.php'): void {
        if (empty($_SESSION['auth'])) {
            header('Location: ' . $redirect);
            exit;
        }
    }
}

if (!function_exists('current_student')) {
    function current_student(): array {
        return $_SESSION['student'] ?? [];
    }
}

if (!function_exists('login_as_student')) {
    function login_as_student(array $student): void {
        $_SESSION['auth'] = true;
        $_SESSION['student'] = [
            'student_code' => $student['student_code'] ?? '',
            'full_name' => $student['full_name'] ?? '',
            'class_name' => $student['class_name'] ?? '',
            'email' => $student['email'] ?? '',
        ];
    }
}
?>
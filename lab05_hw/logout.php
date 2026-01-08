<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/flash.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify($_POST['csrf'] ?? null)) {
    http_response_code(400);
    echo 'Bad Request';
    exit;
}

session_unset();
session_destroy();
session_start();
set_flash('info', 'Bạn đã đăng xuất.');
header('Location: login.php');
exit;
?>
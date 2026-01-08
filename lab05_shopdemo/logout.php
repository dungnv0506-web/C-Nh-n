<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/flash.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify($_POST['csrf'] ?? null)) {
    http_response_code(400);
    echo 'Bad Request';
    exit;
}

// If user has remember_token cookie, remove it from tokens.json
$username = $_SESSION['user']['username'] ?? null;
if (!empty($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $file = __DIR__ . '/data/tokens.json';
    if (is_file($file)) {
        $json = @file_get_contents($file);
        $data = $json ? json_decode($json, true) : [];
        if (is_array($data) && isset($data[$token])) {
            unset($data[$token]);
            @file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }
    setcookie('remember_token', '', time() - 3600, '/');
}

// Clear remember_username cookie
setcookie('remember_username', '', time() - 3600, '/');

// Log logout
$logFile = __DIR__ . '/data/log.txt';
if ($username) {
    $line = date('c') . " - logout - {$username}\n";
    @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

// Destroy session
session_unset();
session_destroy();

// Start new session to set flash
session_start();
set_flash('info', 'Bạn đã đăng xuất.');
header('Location: login.php');
exit;
?>
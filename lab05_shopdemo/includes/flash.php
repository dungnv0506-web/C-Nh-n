<?php
declare(strict_types=1);
// includes/flash.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function set_flash(string $key, string $message): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'][$key] = $message;
}

function get_flash(string $key): ?string {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['flash'][$key])) return null;
    $msg = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $msg;
}
?>
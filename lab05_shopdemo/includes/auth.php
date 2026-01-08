<?php
declare(strict_types=1);
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/users.php';

// Try automatic login using remember_token (advanced remember me)
function try_remember_token_login(): void {
    global $users;
    if (!empty($_SESSION['auth'])) return;
    if (empty($_COOKIE['remember_token'])) return;

    $token = $_COOKIE['remember_token'];
    $file = __DIR__ . '/../data/tokens.json';
    if (!is_file($file)) return;
    $json = @file_get_contents($file);
    if ($json === false) return;
    $data = json_decode($json, true);
    if (empty($data[$token])) return;
    $rec = $data[$token];
    if (!empty($rec['expires']) && $rec['expires'] < time()) {
        // expired
        unset($data[$token]);
        @file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        setcookie('remember_token', '', time() - 3600, '/');
        return;
    }
    $username = $rec['username'];
    if (empty($users[$username])) return;
    // perform login
    $_SESSION['auth'] = true;
    $_SESSION['user'] = ['username' => $username, 'role' => $users[$username]['role']];
}

function is_logged_in(): bool {
    return !empty($_SESSION['auth']);
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function require_login(string $redirect = 'login.php'): void {
    if (!is_logged_in()) {
        header("Location: {$redirect}");
        exit;
    }
}
?>
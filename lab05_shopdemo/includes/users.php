<?php
declare(strict_types=1);
// includes/users.php
// Define users here. We generate password_hash() at runtime (so no plaintext stored).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$users = [
    'admin' => [
        'hash' => password_hash('admin123', PASSWORD_DEFAULT),
        'role' => 'admin'
    ],
    'student' => [
        'hash' => password_hash('student123', PASSWORD_DEFAULT),
        'role' => 'user'
    ],
];
?>
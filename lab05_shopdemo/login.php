<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/users.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/flash.php';
require_once __DIR__ . '/includes/csrf.php';

// If already logged in, go to dashboard
try_remember_token_login();
if (!empty($_SESSION['auth'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$prefill = '';
if (!empty($_COOKIE['remember_username'])) {
    $prefill = $_COOKIE['remember_username'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    $remember = !empty($_POST['remember']);
    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ username và password.';
    } else {
        if (!empty($users[$username]) && password_verify($password, $users[$username]['hash'])) {
            // login ok
            session_regenerate_id(true);
            $_SESSION['auth'] = true;
            $_SESSION['user'] = ['username' => $username, 'role' => $users[$username]['role']];

            // Remember username cookie (basic)
            if ($remember) {
                setcookie('remember_username', $username, time() + 7*24*60*60, '/');
            } else {
                setcookie('remember_username', '', time() - 3600, '/');
            }

            // Advanced remember_token: create and store token -> data/tokens.json
            if ($remember) {
                $token = bin2hex(random_bytes(16));
                $file = __DIR__ . '/data/tokens.json';
                $data = [];
                if (is_file($file)) {
                    $json = @file_get_contents($file);
                    $data = $json ? json_decode($json, true) : [];
                    if (!is_array($data)) $data = [];
                }
                $data[$token] = ['username' => $username, 'expires' => time() + 7*24*60*60];
                @file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                setcookie('remember_token', $token, time() + 7*24*60*60, '/');
            }

            // Log login
            $logFile = __DIR__ . '/data/log.txt';
            $line = date('c') . " - login - {$username}\n";
            @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);

            set_flash('success', 'Đăng nhập thành công.');
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Sai tài khoản hoặc mật khẩu.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Đăng nhập</h2>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="login.php">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input class="form-control" name="username" value="<?= htmlspecialchars($_POST['username'] ?? $prefill) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input class="form-control" type="password" name="password">
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember" <?= !empty($_POST['remember']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="remember">Remember me</label>
      </div>
      <button class="btn btn-primary" type="submit">Login</button>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
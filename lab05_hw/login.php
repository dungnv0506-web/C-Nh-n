<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/flash.php';
require_once __DIR__ . '/includes/auth.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$students = read_json('students.json', []);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim((string)($_POST['student_code'] ?? ''));
    $pass = (string)($_POST['password'] ?? '');
    if ($code === '' || $pass === '') {
        $error = 'Vui lòng nhập đầy đủ mã SV và mật khẩu.';
    } else {
        $found = null;
        foreach ($students as $s) {
            if (($s['student_code'] ?? '') === $code) { $found = $s; break; }
        }
        if ($found && password_verify($pass, $found['password_hash'] ?? '')) {
            // set session using helper
            login_as_student($found);
            set_flash('success', 'Đăng nhập thành công.');
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Sai mã SV hoặc mật khẩu.';
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
        <label class="form-label">Mã SV</label>
        <input class="form-control" name="student_code" value="<?= htmlspecialchars($_POST['student_code'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Mật khẩu</label>
        <input class="form-control" type="password" name="password">
      </div>
      <button class="btn btn-primary" type="submit">Login</button>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/flash.php';

// Nếu dùng remember token, gọi thử auto-login trước
if (function_exists('try_remember_token_login')) {
    try_remember_token_login();
}

require_login();

$user = current_user() ?? [];

// đảm bảo $user['username'] là string khi hiển thị
$username = (string)($user['username'] ?? ($user['student_code'] ?? ''));

require_once __DIR__ . '/includes/header.php';
?>
<h1>Dashboard</h1>
<p>Xin chào, <strong><?= htmlspecialchars($username) ?></strong></p>

<h4>Mini Cart</h4>
<?php $items = cart_get_items(); ?>
<?php if (empty($items)): ?>
  <p>Giỏ hàng rỗng.</p>
<?php else: ?>
  <ul class="list-group mb-3">
    <?php foreach ($items as $it): ?>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <?= htmlspecialchars($it['name']) ?> x <?= (int)$it['qty'] ?>
        <span><?= number_format($it['subtotal']) ?> VND</span>
      </li>
    <?php endforeach; ?>
  </ul>
  <p><strong>Total: <?= number_format(cart_total()) ?> VND</strong></p>
  <p><a href="cart.php" class="btn btn-sm btn-primary">Xem giỏ hàng</a></p>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
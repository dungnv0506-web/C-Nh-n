<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/flash.php';
require_once __DIR__ . '/includes/csrf.php';

try_remember_token_login();
require_login();

$products = get_products();

// Handle POST actions: update, remove, clear
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf'] ?? null)) {
        http_response_code(400);
        echo 'Bad Request';
        exit;
    }
    $action = $_POST['action'] ?? '';
    if ($action === 'update') {
        // Expect qty[] with keys productId => qty
        $qtys = $_POST['qty'] ?? [];
        foreach ($qtys as $pid => $q) {
            $pid = (int)$pid;
            $q = (int)$q;
            cart_update($pid, $q);
        }
        set_flash('success', 'Cập nhật giỏ hàng thành công.');
    } elseif ($action === 'remove') {
        $pid = (int)($_POST['product_id'] ?? 0);
        cart_remove($pid);
        set_flash('success', 'Xóa sản phẩm khỏi giỏ hàng.');
    } elseif ($action === 'clear') {
        cart_clear();
        set_flash('info', 'Đã xóa toàn bộ giỏ hàng.');
    }
    header('Location: cart.php');
    exit;
}

require_once __DIR__ . '/includes/header.php';
$items = cart_get_items();
?>
<h1>Giỏ hàng</h1>
<?php if (empty($items)): ?>
  <p>Giỏ hàng rỗng.</p>
<?php else: ?>
  <form method="post" action="cart.php">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
    <input type="hidden" name="action" value="update">
    <table class="table">
      <thead>
        <tr><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th><th>Subtotal</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($items as $it): ?>
          <tr>
            <td><?= htmlspecialchars($it['name']) ?></td>
            <td><?= number_format($it['price']) ?></td>
            <td style="width:140px;">
              <input class="form-control" type="number" name="qty[<?= (int)$it['id'] ?>]" value="<?= (int)$it['qty'] ?>" min="0">
            </td>
            <td><?= number_format($it['subtotal']) ?></td>
            <td>
              <form method="post" action="cart.php" style="display:inline;">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="product_id" value="<?= (int)$it['id'] ?>">
                <button class="btn btn-sm btn-danger" type="submit">Xóa</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p><strong>Tổng: <?= number_format(cart_total()) ?> VND</strong></p>
    <div class="mb-3">
      <button class="btn btn-primary" type="submit">Cập nhật</button>
      <form method="post" action="cart.php" style="display:inline;">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
        <input type="hidden" name="action" value="clear">
        <button class="btn btn-warning" type="submit">Xóa toàn bộ</button>
      </form>
    </div>
  </form>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
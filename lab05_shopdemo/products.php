<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/flash.php';
require_once __DIR__ . '/includes/csrf.php';

try_remember_token_login();
require_login();

$products = get_products();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add to cart
    if (!csrf_verify($_POST['csrf'] ?? null)) {
        http_response_code(400);
        echo 'Bad Request';
        exit;
    }
    $pid = (int)($_POST['product_id'] ?? 0);
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    if (isset($products[$pid])) {
        cart_add($pid, $qty);
        set_flash('success', 'Đã thêm vào giỏ hàng.');
    }
    header('Location: products.php');
    exit;
}

require_once __DIR__ . '/includes/header.php';
?>
<h1>Products</h1>
<div class="row">
  <?php foreach ($products as $p): ?>
    <div class="col-md-4 mb-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
          <p class="card-text">Price: <?= number_format($p['price']) ?> VND</p>
          <form method="post" action="products.php">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
            <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
            <div class="mb-2">
              <input type="number" name="qty" value="1" min="1" class="form-control form-control-sm" style="width:100px;">
            </div>
            <button class="btn btn-primary btn-sm">Add to cart</button>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
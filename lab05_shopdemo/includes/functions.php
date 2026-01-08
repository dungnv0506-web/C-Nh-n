<?php
declare(strict_types=1);
// includes/functions.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Products: simulated array
function get_products(): array {
    return [
        1 => ['id' => 1, 'name' => 'Áo thun', 'price' => 150000],
        2 => ['id' => 2, 'name' => 'Quần jeans', 'price' => 300000],
        3 => ['id' => 3, 'name' => 'Giày thể thao', 'price' => 800000],
    ];
}

// Cart helpers: $_SESSION['cart'] => [productId => qty]
function cart_add(int $id, int $qty = 1): void {
    if ($qty < 1) $qty = 1;
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
}

function cart_update(int $id, int $qty): void {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if ($qty <= 0) {
        unset($_SESSION['cart'][$id]);
    } else {
        $_SESSION['cart'][$id] = $qty;
    }
}

function cart_remove(int $id): void {
    if (!isset($_SESSION['cart'])) return;
    unset($_SESSION['cart'][$id]);
}

function cart_clear(): void {
    unset($_SESSION['cart']);
}

function cart_get_items(): array {
    $products = get_products();
    $items = [];
    foreach ($_SESSION['cart'] ?? [] as $pid => $qty) {
        if (isset($products[$pid])) {
            $p = $products[$pid];
            $items[] = [
                'id' => $pid,
                'name' => $p['name'],
                'price' => $p['price'],
                'qty' => $qty,
                'subtotal' => $p['price'] * $qty,
            ];
        }
    }
    return $items;
}

function cart_total(): int {
    $total = 0;
    foreach (cart_get_items() as $it) $total += $it['subtotal'];
    return $total;
}
?>
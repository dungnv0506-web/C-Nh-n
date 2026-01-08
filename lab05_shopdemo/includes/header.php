<?php
declare(strict_types=1);
// includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/csrf.php';
try_remember_token_login(); // note: function name is try_remember_token_login in auth.php
$user = current_user();
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Shop Demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">Shop Demo</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <?php if (!empty($user)): ?>
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
          <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
          <?php if (($user['role'] ?? '') === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="#">Admin Panel</a></li>
          <?php endif; ?>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if (!empty($user)): ?>
          <li class="nav-item"><span class="nav-link">Xin chào, <?= htmlspecialchars($user['username']) ?></span></li>
          <li class="nav-item">
            <form method="post" action="logout.php" class="d-inline">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
              <button class="btn btn-link nav-link" type="submit" style="display:inline;padding:0;">Logout</button>
            </form>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
  <?php if ($m = get_flash('success')): ?>
    <div class="alert alert-success"><?= htmlspecialchars($m) ?></div>
  <?php endif; ?>
  <?php if ($m = get_flash('info')): ?>
    <div class="alert alert-info"><?= htmlspecialchars($m) ?></div>
  <?php endif; ?>
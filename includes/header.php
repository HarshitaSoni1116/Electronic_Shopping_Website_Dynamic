<?php
require_once __DIR__ . '/../config.php';
$current = basename($_SERVER['PHP_SELF']);
function nav_active($page) { global $current; return $current === $page ? 'active' : ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QuickBuy | <?= e($pageTitle ?? 'Home') ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">Quick<span>Buy</span></div>
      <ul class="nav-links">
        <li><a href="homepage.php" class="<?= nav_active('homepage.php') ?>">🏠Home</a></li>
        <li><a href="products.php" class="<?= nav_active('products.php') ?>">📱Products</a></li>
        <li><a href="cart.php" class="<?= nav_active('cart.php') ?>">🛒Cart (<?= cart_count() ?>)</a></li>
        <li><a href="search.php" class="<?= nav_active('search.php') ?>">🔎Search</a></li>
        <?php if (!empty($_SESSION['user_id'])): ?>
          <li><a href="logout.php">🚪Logout (<?= e($_SESSION['user_name']) ?>)</a></li>
        <?php else: ?>
          <li><a href="login.php" class="<?= nav_active('login.php') ?>">🔑Login</a></li>
          <li><a href="register.php" class="<?= nav_active('register.php') ?>">📄Register</a></li>
        <?php endif; ?>
        <li><a href="contact.php" class="<?= nav_active('contact.php') ?>">📞Contact</a></li>
      </ul>
    </nav>
  </header>
  <?php if (!empty($_SESSION['flash'])): [$t, $m] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="alert <?= e($t) ?>"><?= e($m) ?></div>
  <?php endif; ?>

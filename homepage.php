<?php
$pageTitle = 'Home';
require 'includes/header.php';
$featured = $pdo->query('SELECT * FROM products ORDER BY id LIMIT 3')->fetchAll();
?>
  <section class="hero">
    <div class="hero-content">
      <h1 class="hero-title">Welcome to <span>QuickBuy</span></h1>
      <p class="hero-subtitle">Discover the best products at amazing prices!</p>
      <a href="products.php" class="btn hero-btn">Shop Now</a>
    </div>
    <div class="hero-image">
      <img src="https://i.pinimg.com/originals/e0/73/39/e07339b97fcba2872a07874e1e6a1828.gif" alt="Shopping Hero" class="animate-float" height="200">
    </div>
  </section>

  <section class="featured">
    <h2 class="section-title">Featured products</h2>
    <div class="product-grid">
      <?php foreach ($featured as $p) include 'includes/product_card.php'; ?>
    </div>
  </section>
<?php require 'includes/footer.php'; ?>

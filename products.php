<?php
$pageTitle = 'Products';
require 'includes/header.php';
$products = $pdo->query('SELECT * FROM products ORDER BY id')->fetchAll();
?>
  <section class="products-page">
    <h2 class="section-title">Our Products</h2>
    <div class="product-grid">
      <?php foreach ($products as $p) include 'includes/product_card.php'; ?>
    </div>
  </section>
<?php require 'includes/footer.php'; ?>

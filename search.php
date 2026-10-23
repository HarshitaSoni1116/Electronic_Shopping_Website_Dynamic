<?php
$pageTitle = 'Search';
require 'includes/header.php';
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY name');
    $like = '%' . $q . '%';
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query('SELECT * FROM products ORDER BY name');
}
$products = $stmt->fetchAll();
?>
  <section class="search-page">
    <h2 class="section-title">Find Your Products</h2>
    <div class="search-container animate-card">
      <form method="get" class="search-form">
        <input type="text" id="searchBar" name="q" value="<?= e($q) ?>" placeholder="Search for products...">
        <button type="submit">Search</button>
      </form>
      <div class="product-grid">
        <?php foreach ($products as $p) include 'includes/product_card.php'; ?>
      </div>
      <?php if (!$products): ?><p class="empty-msg">No products found.</p><?php endif; ?>
    </div>
  </section>
<?php require 'includes/footer.php'; ?>

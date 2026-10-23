<div class="product-card animate-card" data-name="<?= e($p['name']) ?>">
  <img src="<?= e($p['image_url']) ?>" alt="<?= e($p['name']) ?>">
  <h3><?= e($p['name']) ?></h3>
  <p>₹<?= number_format($p['price']) ?></p>
  <form method="post" action="cart.php">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
    <button type="submit" class="btn add-to-cart">Add to Cart</button>
  </form>
</div>

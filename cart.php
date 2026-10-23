<?php
require_once 'config.php';

// Handle actions (add / update / remove) BEFORE any output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['product_id'] ?? 0);
    $_SESSION['cart'] = $_SESSION['cart'] ?? [];

    if ($action === 'add' && $id > 0) {
        $chk = $pdo->prepare('SELECT name FROM products WHERE id = ?');
        $chk->execute([$id]);
        if ($row = $chk->fetch()) {
            $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
            flash('success', $row['name'] . ' added to cart!');
        }
        redirect($_SERVER['HTTP_REFERER'] ?? 'products.php');
    }
    if ($action === 'update' && $id > 0) {
        $qty = max(1, (int)($_POST['qty'] ?? 1));
        if (isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = $qty;
    }
    if ($action === 'remove') unset($_SESSION['cart'][$id]);
    redirect('cart.php');
}

$pageTitle = 'Cart';
require 'includes/header.php';

$items = []; $subtotal = 0;
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $in  = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
    $stmt->execute($ids);
    foreach ($stmt->fetchAll() as $p) {
        $p['qty'] = $_SESSION['cart'][$p['id']];
        $p['line'] = $p['qty'] * $p['price'];
        $subtotal += $p['line'];
        $items[] = $p;
    }
}
$shipping = $subtotal > 0 ? SHIPPING_CHARGE : 0;
?>
  <section class="cart-page">
    <h1 class="section-title">Your Shopping Cart</h1>
    <div class="cart-container">
      <?php if (!$items): ?>
        <p class="empty-msg">Your cart is empty</p>
      <?php endif; ?>
      <?php foreach ($items as $it): ?>
        <div class="cart-card">
          <img src="<?= e($it['image_url']) ?>" alt="<?= e($it['name']) ?>">
          <h3><?= e($it['name']) ?></h3>
          <p>₹<?= number_format($it['price']) ?></p>
          <form method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="product_id" value="<?= (int)$it['id'] ?>">
            <input type="number" name="qty" value="<?= (int)$it['qty'] ?>" min="1">
            <button type="submit">Update</button>
          </form>
          <p>Total: ₹<?= number_format($it['line']) ?></p>
          <form method="post">
            <input type="hidden" name="action" value="remove">
            <input type="hidden" name="product_id" value="<?= (int)$it['id'] ?>">
            <button type="submit" class="remove-btn">Remove</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="cart-summary">
      <h2>Cart Summary</h2>
      <p><strong>Subtotal:</strong> ₹<?= number_format($subtotal) ?></p>
      <p><strong>Shipping:</strong> ₹<?= number_format($shipping) ?></p>
      <p><strong>Total:</strong> ₹<?= number_format($subtotal + $shipping) ?></p>
      <?php if ($items): ?>
        <a href="checkout.php" class="btn checkout-btn">Proceed to Checkout</a>
      <?php endif; ?>
    </div>
  </section>
<?php require 'includes/footer.php'; ?>

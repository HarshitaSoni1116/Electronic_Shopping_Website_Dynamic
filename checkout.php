<?php
require_once 'config.php';
if (empty($_SESSION['user_id'])) {
    flash('error', 'Please login to checkout.');
    redirect('login.php');
}
if (empty($_SESSION['cart'])) redirect('cart.php');

// Load cart products
$ids = array_keys($_SESSION['cart']);
$in  = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
$stmt->execute($ids);
$items = []; $subtotal = 0;
foreach ($stmt->fetchAll() as $p) {
    $p['qty'] = $_SESSION['cart'][$p['id']];
    $subtotal += $p['qty'] * $p['price'];
    $items[] = $p;
}
$total = $subtotal + SHIPPING_CHARGE;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    if ($address === '') {
        flash('error', 'Please enter a delivery address.');
        redirect('checkout.php');
    }
    try {
        $pdo->beginTransaction();
        $pdo->prepare('INSERT INTO orders (user_id, subtotal, shipping, total, address) VALUES (?,?,?,?,?)')
            ->execute([$_SESSION['user_id'], $subtotal, SHIPPING_CHARGE, $total, $address]);
        $orderId = $pdo->lastInsertId();
        $ins = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_name, price, qty) VALUES (?,?,?,?,?)');
        foreach ($items as $it) {
            $ins->execute([$orderId, $it['id'], $it['name'], $it['price'], $it['qty']]);
        }
        $pdo->commit();
        unset($_SESSION['cart']);
        flash('success', "Order #$orderId placed successfully!");
        redirect('homepage.php');
    } catch (Exception $ex) {
        $pdo->rollBack();
        flash('error', 'Could not place order. Try again.');
        redirect('checkout.php');
    }
}

$pageTitle = 'Checkout';
require 'includes/header.php';
?>
  <section class="cart-page">
    <h1 class="section-title">Checkout</h1>
    <div class="order-box">
      <table>
        <tr><th>Product</th><th>Qty</th><th>Price</th></tr>
        <?php foreach ($items as $it): ?>
          <tr><td><?= e($it['name']) ?></td><td><?= (int)$it['qty'] ?></td><td>₹<?= number_format($it['price'] * $it['qty']) ?></td></tr>
        <?php endforeach; ?>
        <tr><td colspan="2">Shipping</td><td>₹<?= SHIPPING_CHARGE ?></td></tr>
        <tr><th colspan="2">Total</th><th>₹<?= number_format($total) ?></th></tr>
      </table>
      <form method="post" class="contact-form">
        <textarea name="address" rows="3" placeholder="Delivery address" required style="width:100%;padding:10px;margin-bottom:10px;"></textarea>
        <button type="submit" class="btn checkout-btn" style="background:#ffdd59;padding:12px 25px;border-radius:50px;font-weight:bold;">Place Order</button>
      </form>
    </div>
  </section>
<?php require 'includes/footer.php'; ?>

<?php
require_once 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([trim($_POST['email'] ?? '')]);
    $u = $stmt->fetch();
    if ($u && password_verify($_POST['password'] ?? '', $u['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $u['id'];
        $_SESSION['user_name'] = $u['name'];
        flash('success', 'Welcome back, ' . $u['name'] . '!');
        redirect('homepage.php');
    }
    $error = 'Invalid email or password.';
}
$pageTitle = 'Login';
require 'includes/header.php';
?>
  <section class="register-page">
    <div class="register-container animate-card">
      <h2>Login</h2>
      <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
      <form method="post">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn">Login</button>
      </form>
      <p>New here? </p><p><a href="register.php">Create an account</a></p>
    </div>
  </section>
<?php require 'includes/footer.php'; ?>

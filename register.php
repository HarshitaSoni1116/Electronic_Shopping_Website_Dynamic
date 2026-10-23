<?php
require_once 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $conf  = $_POST['confirmPassword'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid name and email.';
    } elseif (strlen($pass) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($pass !== $conf) {
        $error = 'Passwords do not match.';
    } else {
        $chk = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $chk->execute([$email]);
        if ($chk->fetch()) {
            $error = 'This email is already registered.';
        } else {
            $ins = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
            $ins->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT)]);
            flash('success', 'Registration successful! Please login.');
            redirect('login.php');
        }
    }
}
$pageTitle = 'Register';
require 'includes/header.php';
?>
  <section class="register-page">
    <div class="register-container animate-card">
      <h2>Create Your Account</h2>
      <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
      <form method="post">
        <input type="text" name="name" placeholder="Full Name" value="<?= e($_POST['name'] ?? '') ?>" required>
        <input type="email" name="email" placeholder="Email Address" value="<?= e($_POST['email'] ?? '') ?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
        <button type="submit" class="btn">Register</button>
      </form>
      <p>Already have an account? </p><p><a href="login.php">Login here</a></p>
    </div>
  </section>
<?php require 'includes/footer.php'; ?>

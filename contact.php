<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $msg = trim($_POST['message'] ?? '');
    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $msg) {
        $pdo->prepare('INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)')
            ->execute([$name, $email, $msg]);
        flash('success', 'Message sent successfully!');
    } else {
        flash('error', 'Please fill all fields with valid data.');
    }
    redirect('contact.php');
}
$pageTitle = 'Contact';
require 'includes/header.php';
?>
  <section class="contact-page">
    <h2 class="section-title">Get in Touch</h2>
    <div class="contact-container animate-card">
      <div class="contact-info">
        <h2>Contact Info</h2><br>
        <p><strong>Address:</strong> 123 Market Street, City, Country</p>
        <p><strong>Email:</strong> support@quickbuy.com</p>
        <p><strong>Phone:</strong> +91 12345 67890</p>
      </div>
      <div class="contact-form">
        <h2>Send a Message</h2><br>
        <form method="post">
          <input type="text" name="name" placeholder="Full Name" required>
          <input type="email" name="email" placeholder="Email Address" required>
          <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
          <button type="submit" class="btn">Send Message</button>
        </form>
      </div>
    </div>
    <div class="map-container animate-card">
      <iframe src="https://maps.google.com/maps?q=New%20Delhi&t=&z=13&ie=UTF8&iwloc=&output=embed"
        frameborder="0" style="border:0;width:100%;height:300px;" allowfullscreen></iframe>
    </div>
  </section>
<?php require 'includes/footer.php'; ?>

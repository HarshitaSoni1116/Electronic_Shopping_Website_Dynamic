<?php

// ---------- Database settings (change these) ----------
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');         // XAMPP default is empty
define('SHIPPING_CHARGE', 100); // same as your old script.js

session_start();

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed.' . $e->getMessage());
}

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function flash($type, $msg) { $_SESSION['flash'] = [$type, $msg]; }

function cart_count() {
    return array_sum($_SESSION['cart'] ?? []);
}

function redirect($url) { header("Location: $url"); exit; }

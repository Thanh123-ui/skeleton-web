<?php
// config/db.php
// Kết nối MySQL bằng PDO - dễ bảo mật + prepared statements

$DB_HOST = "localhost";
$DB_NAME = "Booking-php";
$DB_USER = "root";
$DB_PASS = ""; // XAMPP mặc định rỗng

try {
  $pdo = new PDO(
    "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
  );
} catch (Exception $e) {
  die("DB connection failed: " . $e->getMessage());
}

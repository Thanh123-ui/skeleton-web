<?php
// actions/admin_login.php
session_start();
require_once __DIR__ . "/../config/db.php";

function back_err($msg){
  header("Location: ../admin.php?err=" . urlencode($msg));
  exit;
}

if($_SERVER["REQUEST_METHOD"] !== "POST") back_err("Invalid request.");

$username = trim($_POST["username"] ?? "");
$password = trim($_POST["password"] ?? "");

if($username === "" || $password === "") back_err("Thiếu thông tin.");

$stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch();

if(!$admin) back_err("Sai tài khoản hoặc mật khẩu.");

if(!password_verify($password, $admin["password"])) back_err("Sai tài khoản hoặc mật khẩu.");

$_SESSION["admin_id"] = $admin["id"];
$_SESSION["admin_username"] = $admin["username"];

header("Location: ../admin.php?msg=" . urlencode("Đăng nhập thành công!"));
exit;

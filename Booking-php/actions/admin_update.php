<?php
// actions/admin_update.php
session_start();
require_once __DIR__ . "/../config/db.php";

if(!isset($_SESSION["admin_id"])){
  header("Location: ../admin.php?err=" . urlencode("Bạn chưa đăng nhập."));
  exit;
}

$id = (int)($_POST["id"] ?? 0);
$action = $_POST["action"] ?? "";

if($id <= 0){
  header("Location: ../admin.php?err=" . urlencode("ID không hợp lệ."));
  exit;
}

$status = null;
if($action === "confirm") $status = "confirmed";
if($action === "cancel") $status = "cancelled";

if(!$status){
  header("Location: ../admin.php?err=" . urlencode("Action không hợp lệ."));
  exit;
}

$stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
$stmt->execute([$status, $id]);

header("Location: ../admin.php?msg=" . urlencode("Cập nhật trạng thái thành công (#{$id})."));
exit;

<?php
// actions/book.php
// Nhận POST từ index.php, validate server-side, lưu DB

require_once __DIR__ . "/../config/db.php";

// Helper: redirect về index kèm query
function back_with_error($msg){
  header("Location: ../index.php?error=" . urlencode($msg));
  exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  back_with_error("Invalid request method.");
}

$patient_name = trim($_POST["patient_name"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$email = trim($_POST["email"] ?? "");
$department = trim($_POST["department"] ?? "");
$doctor = trim($_POST["doctor"] ?? "");
$date = trim($_POST["date"] ?? "");
$time = trim($_POST["time"] ?? "");
$note = trim($_POST["note"] ?? "");

// ===== Server-side validate (bắt buộc vì JS có thể bị tắt) =====
if (mb_strlen($patient_name) < 2) back_with_error("Họ tên không hợp lệ.");
if (!preg_match('/^0\d{9,10}$/', $phone)) back_with_error("Số điện thoại không hợp lệ.");
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) back_with_error("Email không hợp lệ.");
if ($department === "" || $doctor === "") back_with_error("Chuyên khoa/Bác sĩ không hợp lệ.");
if ($date === "" || $time === "") back_with_error("Ngày/Giờ không hợp lệ.");

// (Tuỳ chọn) chặn trùng lịch theo phone+date+time (không tính cancelled)
$stmt = $pdo->prepare("SELECT COUNT(*) AS c FROM appointments
  WHERE phone = ? AND date = ? AND time = ? AND status <> 'cancelled'");
$stmt->execute([$phone, $date, $time]);
$row = $stmt->fetch();
if ($row && (int)$row["c"] > 0) back_with_error("Bạn đã có lịch ở khung giờ này. Vui lòng chọn giờ khác.");

// Insert
$stmt = $pdo->prepare("
  INSERT INTO appointments(patient_name, phone, email, department, doctor, date, time, note, status)
  VALUES(?,?,?,?,?,?,?,?, 'pending')
");
$stmt->execute([$patient_name, $phone, $email, $department, $doctor, $date, $time, $note]);

header("Location: ../index.php?success=1");
exit;

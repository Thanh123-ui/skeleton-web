<?php
// schedule.php
// Form nhập phone/email, submit GET tới chính nó, truy vấn DB hiển thị kết quả

require_once __DIR__ . "/config/db.php";

$q = trim($_GET["q"] ?? "");
$results = [];

if ($q !== "") {
  // Tìm theo phone hoặc email
  $stmt = $pdo->prepare("SELECT * FROM appointments
    WHERE phone = :q OR email = :q
    ORDER BY created_at DESC");
  $stmt->execute([":q" => $q]);
  $results = $stmt->fetchAll();
}

function status_text($s){
  if ($s === "confirmed") return "Đã xác nhận";
  if ($s === "cancelled") return "Đã hủy";
  return "Chờ xác nhận";
}
function status_class($s){
  if ($s === "confirmed") return "confirmed";
  if ($s === "cancelled") return "cancelled";
  return "pending";
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Xem lịch khám</title>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

<header>
  <div class="container header-inner">
    <a class="brand" href="index.php">
      <span class="logo" aria-hidden="true"></span>
      <span>PHÒNG KHÁM AN TÂM</span>
    </a>
    <nav>
      <ul>
        <li><a href="index.php">Đặt lịch</a></li>
        <li><a class="active" href="schedule.php">Xem lịch</a></li>
        <li><a href="admin.php">Admin</a></li>
      </ul>
    </nav>
  </div>
</header>

<main class="container">
  <section class="card">
    <h2>Tra cứu lịch khám</h2>
    <p class="hint">Nhập <b>số điện thoại</b> hoặc <b>email</b> đã dùng khi đặt lịch.</p>

    <hr class="sep" />

    <form method="GET" action="schedule.php">
      <div class="row">
        <div>
          <label>Từ khóa (SĐT hoặc Email)</label>
          <input name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="VD: 0901234567 hoặc abc@gmail.com" />
        </div>
        <div>
          <label>&nbsp;</label>
          <button class="btn btn-primary" type="submit">Tìm</button>
          <a class="btn btn-outline" href="schedule.php">Xóa</a>
          <a class="btn btn-outline" href="index.php">Đặt lịch mới</a>
        </div>
      </div>
    </form>

    <hr class="sep" />

    <?php if($q === ""): ?>
      <div class="notice">Nhập SĐT/Email để xem lịch.</div>
    <?php else: ?>
      <div class="notice">Tìm thấy: <b><?php echo count($results); ?></b> lịch.</div>

      <hr class="sep" />

      <div style="overflow:auto;">
        <table class="table">
          <thead>
            <tr>
              <th>Mã</th>
              <th>Bệnh nhân</th>
              <th>Chuyên khoa</th>
              <th>Bác sĩ</th>
              <th>Ngày/Giờ</th>
              <th>Ghi chú</th>
              <th>Trạng thái</th>
              <th>Đặt lúc</th>
            </tr>
          </thead>
          <tbody>
          <?php if(empty($results)): ?>
            <tr><td colspan="8">Không có lịch phù hợp.</td></tr>
          <?php else: ?>
            <?php foreach($results as $a): ?>
              <tr>
                <td><b>#<?php echo (int)$a["id"]; ?></b></td>
                <td>
                  <?php echo htmlspecialchars($a["patient_name"]); ?><br>
                  <small class="hint"><?php echo htmlspecialchars($a["phone"]); ?><br><?php echo htmlspecialchars($a["email"]); ?></small>
                </td>
                <td><?php echo htmlspecialchars($a["department"]); ?></td>
                <td><?php echo htmlspecialchars($a["doctor"]); ?></td>
                <td><?php echo htmlspecialchars($a["date"]); ?> • <?php echo htmlspecialchars($a["time"]); ?></td>
                <td><?php echo $a["note"] ? htmlspecialchars($a["note"]) : "<span class='hint'>(Không)</span>"; ?></td>
                <td><span class="status <?php echo status_class($a["status"]); ?>"><?php echo status_text($a["status"]); ?></span></td>
                <td><small class="hint"><?php echo htmlspecialchars($a["created_at"]); ?></small></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>
</main>

<footer class="footer">
  Trạng thái: <span class="status pending">Chờ xác nhận</span>
  <span class="status confirmed">Đã xác nhận</span>
  <span class="status cancelled">Đã hủy</span>
</footer>
</body>
</html>

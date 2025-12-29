<?php
// admin.php
session_start();
require_once __DIR__ . "/config/db.php";

// Helper
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

$logged_in = isset($_SESSION["admin_id"]);
$message = $_GET["msg"] ?? "";
$error = $_GET["err"] ?? "";

// Nếu đã login: lấy danh sách lịch
$appointments = [];
if ($logged_in) {
  $stmt = $pdo->query("SELECT * FROM appointments ORDER BY created_at DESC");
  $appointments = $stmt->fetchAll();
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin</title>
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
        <li><a href="schedule.php">Xem lịch</a></li>
        <li><a class="active" href="admin.php">Admin</a></li>
      </ul>
    </nav>
  </div>
</header>

<main class="container">

<?php if(!$logged_in): ?>
  <section class="card" style="max-width:560px;margin:0 auto;">
    <h2>Đăng nhập Admin</h2>
    <p class="hint">Tài khoản lấy từ DB bảng <b>admins</b> (mặc định: admin/123456).</p>

    <?php if($error): ?>
      <div class="notice err"><?php echo htmlspecialchars($error); ?></div>
    <?php else: ?>
      <div class="notice">Vui lòng đăng nhập.</div>
    <?php endif; ?>

    <hr class="sep" />

    <form method="POST" action="actions/admin_login.php">
      <div class="row row-1">
        <div>
          <label>Username</label>
          <input name="username" required placeholder="admin" />
        </div>
        <div>
          <label>Password</label>
          <input name="password" type="password" required placeholder="123456" />
        </div>
      </div>
      <hr class="sep" />
      <button class="btn btn-primary" type="submit">Đăng nhập</button>
      <a class="btn btn-outline" href="index.php">Trang chủ</a>
    </form>
  </section>

<?php else: ?>
  <section class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
      <div>
        <h2 style="margin:0;">Quản trị lịch khám</h2>
        <p class="hint" style="margin:6px 0 0 0;">Xác nhận/hủy sẽ cập nhật MySQL.</p>
      </div>
      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a class="btn btn-outline" href="logout.php">Đăng xuất</a>
      </div>
    </div>

    <?php if($message): ?>
      <hr class="sep" />
      <div class="notice ok"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <hr class="sep" />

    <div style="overflow:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Bệnh nhân</th>
            <th>Khoa/Bác sĩ</th>
            <th>Ngày/Giờ</th>
            <th>Ghi chú</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
        <?php if(empty($appointments)): ?>
          <tr><td colspan="7">Chưa có lịch.</td></tr>
        <?php else: ?>
          <?php foreach($appointments as $a): ?>
            <tr>
              <td><b>#<?php echo (int)$a["id"]; ?></b><br><small class="hint"><?php echo htmlspecialchars($a["created_at"]); ?></small></td>
              <td>
                <?php echo htmlspecialchars($a["patient_name"]); ?><br>
                <small class="hint"><?php echo htmlspecialchars($a["phone"]); ?><br><?php echo htmlspecialchars($a["email"]); ?></small>
              </td>
              <td>
                <?php echo htmlspecialchars($a["department"]); ?><br>
                <small class="hint"><?php echo htmlspecialchars($a["doctor"]); ?></small>
              </td>
              <td><?php echo htmlspecialchars($a["date"]); ?> • <?php echo htmlspecialchars($a["time"]); ?></td>
              <td><?php echo $a["note"] ? htmlspecialchars($a["note"]) : "<span class='hint'>(Không)</span>"; ?></td>
              <td><span class="status <?php echo status_class($a["status"]); ?>"><?php echo status_text($a["status"]); ?></span></td>
              <td>
                <form method="POST" action="actions/admin_update.php" style="display:flex;gap:8px;flex-wrap:wrap;">
                  <input type="hidden" name="id" value="<?php echo (int)$a["id"]; ?>" />
                  <button class="btn btn-success" type="submit" name="action" value="confirm" <?php echo $a["status"]==="confirmed"?"disabled":""; ?>>
                    Xác nhận
                  </button>
                  <button class="btn btn-danger" type="submit" name="action" value="cancel" <?php echo $a["status"]==="cancelled"?"disabled":""; ?>>
                    Hủy
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
<?php endif; ?>

</main>

<footer class="footer">
  Admin demo • PHP Session • PDO • MySQL
</footer>
</body>
</html>

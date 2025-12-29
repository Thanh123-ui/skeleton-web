<?php
// index.php
// Trang chủ + form đặt lịch (POST sang actions/book.php)
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Phòng khám An Tâm - Đặt lịch khám online</title>
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
        <li><a class="active" href="index.php">Đặt lịch</a></li>
        <li><a href="schedule.php">Xem lịch</a></li>
        <li><a href="admin.php">Admin</a></li>
      </ul>
    </nav>
  </div>
</header>

<section class="banner">
  <div class="container banner-inner">
    <div>
      <h1>Đặt lịch khám nhanh – tiện lợi</h1>
      <p>Demo chuyên đề: HTML/CSS/JS + PHP + MySQL (không framework).</p>
    </div>
    <div class="card">
      <b>Hướng dẫn nhanh</b>
      <p class="hint" style="margin:8px 0 0 0;">
        1) Điền form → 2) Gửi → 3) Admin xác nhận/hủy → 4) Người dùng tra cứu theo SĐT/Email.
      </p>
    </div>
  </div>
</section>

<main class="container">
  <div class="grid">
    <section class="card">
      <h2>Giới thiệu phòng khám</h2>
      <p>
        Phòng khám An Tâm cung cấp dịch vụ đa khoa, tai mũi họng, da liễu, răng hàm mặt, nhi khoa...
        Đặt lịch trước giúp giảm thời gian chờ.
      </p>
      <hr class="sep" />
      <div class="notice">
        Gợi ý chuyên đề: Trình bày quy trình xử lý: validate JS → POST PHP → lưu MySQL → admin update status.
      </div>
    </section>

    <section class="card">
      <h2>Form đặt lịch khám</h2>

      <?php if(isset($_GET["success"])): ?>
        <div class="notice ok">Đặt lịch thành công! Bạn có thể qua trang “Xem lịch”.</div>
      <?php elseif(isset($_GET["error"])): ?>
        <div class="notice err">Có lỗi: <?php echo htmlspecialchars($_GET["error"]); ?></div>
      <?php else: ?>
        <div id="formNotice" class="notice">Trạng thái: sẵn sàng.</div>
      <?php endif; ?>

      <hr class="sep" />

      <form id="bookingForm" method="POST" action="actions/book.php" novalidate>
        <div class="row">
          <div>
            <label>Họ tên bệnh nhân</label>
            <input name="patient_name" type="text" required placeholder="VD: Nguyễn Văn A" />
          </div>
          <div>
            <label>Số điện thoại</label>
            <input name="phone" type="tel" required placeholder="VD: 0901234567" />
          </div>
        </div>

        <div class="row">
          <div>
            <label>Email</label>
            <input name="email" type="email" required placeholder="VD: abc@gmail.com" />
          </div>
          <div>
            <label>Chuyên khoa</label>
            <select name="department" required>
              <option value="">-- Chọn chuyên khoa --</option>
              <option>Nội tổng quát</option>
              <option>Tai - Mũi - Họng</option>
              <option>Răng - Hàm - Mặt</option>
              <option>Da liễu</option>
              <option>Nhi khoa</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div>
            <label>Bác sĩ</label>
            <select name="doctor" required>
              <option value="">-- Chọn bác sĩ --</option>
              <option>BS. Nguyễn Văn A</option>
              <option>BS. Trần Thị B</option>
              <option>BS. Lê Minh C</option>
              <option>BS. Phạm Hồng D</option>
            </select>
            <small class="hint">Bạn có thể nâng cấp: lọc bác sĩ theo chuyên khoa bằng JS.</small>
          </div>
          <div>
            <label>Ngày khám</label>
            <input id="date" name="date" type="date" required />
          </div>
        </div>

        <div class="row">
          <div>
            <label>Giờ khám</label>
            <input name="time" type="time" required />
          </div>
          <div>
            <label>&nbsp;</label>
            <button class="btn btn-primary" type="submit">Đặt lịch</button>
            <a class="btn btn-outline" href="schedule.php">Xem lịch</a>
          </div>
        </div>

        <div class="row row-1">
          <div>
            <label>Ghi chú</label>
            <textarea name="note" placeholder="Triệu chứng, yêu cầu đặc biệt..."></textarea>
          </div>
        </div>
      </form>

    </section>
  </div>
</main>

<footer class="footer">
  © 2025 Demo chuyên đề • PHP + MySQL • Không framework
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>

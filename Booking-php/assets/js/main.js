// assets/js/main.js
// Validate form đặt lịch (client-side)

function isValidEmail(email){
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
}
function isValidPhone(phone){
  return /^0\d{9,10}$/.test(phone.trim());
}
function setNotice(id, type, msg){
  const el = document.getElementById(id);
  if(!el) return;
  el.className = "notice " + (type || "");
  el.textContent = msg;
}

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("bookingForm");
  if(!form) return;

  // Set min date = today
  const dateInput = document.getElementById("date");
  if(dateInput){
    const t = new Date();
    const yyyy = t.getFullYear();
    const mm = String(t.getMonth()+1).padStart(2,"0");
    const dd = String(t.getDate()).padStart(2,"0");
    dateInput.min = `${yyyy}-${mm}-${dd}`;
  }

  form.addEventListener("submit", (e) => {
    const name = form.patient_name.value.trim();
    const phone = form.phone.value.trim();
    const email = form.email.value.trim();
    const department = form.department.value.trim();
    const doctor = form.doctor.value.trim();
    const date = form.date.value;
    const time = form.time.value;

    if(name.length < 2){
      e.preventDefault();
      return setNotice("formNotice","err","Họ tên phải có ít nhất 2 ký tự.");
    }
    if(!isValidPhone(phone)){
      e.preventDefault();
      return setNotice("formNotice","err","Số điện thoại không hợp lệ (vd: 0901234567).");
    }
    if(!isValidEmail(email)){
      e.preventDefault();
      return setNotice("formNotice","err","Email không hợp lệ.");
    }
    if(!department || !doctor){
      e.preventDefault();
      return setNotice("formNotice","err","Vui lòng chọn chuyên khoa và bác sĩ.");
    }
    if(!date || !time){
      e.preventDefault();
      return setNotice("formNotice","err","Vui lòng chọn ngày và giờ khám.");
    }

    // OK
    setNotice("formNotice","ok","Dữ liệu hợp lệ. Đang gửi...");
  });
});

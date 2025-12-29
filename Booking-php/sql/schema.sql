-- Tạo database
CREATE DATABASE IF NOT EXISTS Booking-php
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE Booking-php;

-- Bảng lịch khám
CREATE TABLE IF NOT EXISTS appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_name VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(150) NOT NULL,
  department VARCHAR(100) NOT NULL,
  doctor VARCHAR(100) NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  note TEXT NULL,
  status ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Index phục vụ tìm kiếm nhanh theo phone/email
CREATE INDEX idx_appointments_phone ON appointments(phone);
CREATE INDEX idx_appointments_email ON appointments(email);

-- Bảng admin
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- Tạo admin mẫu: username=admin, password=123456
-- Password đã được hash bằng password_hash trong PHP (bạn có thể tạo lại)
INSERT INTO admins(username, password)
VALUES
('admin', '$2y$10$7wG0y6zvY3m7mQwF5w8e7u9h4qC2tE2t7d2xVwE0F7q2wU2eE9nUu')
ON DUPLICATE KEY UPDATE username=username;

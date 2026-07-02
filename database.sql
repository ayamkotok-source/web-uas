-- =========================================================
-- Database: manajemen_anggota
-- Sistem Manajemen Anggota (UAS PW2)
-- Import file ini lewat phpMyAdmin atau: mysql -u root -p < database.sql
-- =========================================================

CREATE DATABASE IF NOT EXISTS manajemen_anggota CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE manajemen_anggota;

-- -----------------------------------------------------
-- Tabel users (untuk Login & Registrasi)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Akun default: username = samso | password = samso123
INSERT INTO users (nama_lengkap, username, password) VALUES
('ajat', 'jensss', '12345678910');
-- (password di atas ter-hash dengan bcrypt, cocok dengan password_verify() di PHP)

-- -----------------------------------------------------
-- Tabel anggota (Data Anggota)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS anggota (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    no_telepon VARCHAR(20) DEFAULT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    status ENUM('Aktif','Tidak aktif') NOT NULL DEFAULT 'Aktif',
    tgl_daftar DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO anggota (nim, nama_lengkap, email, no_telepon, foto, status, tgl_daftar) VALUES
('251011700366', 'muhamad. ijen sudrajat', 'jensss@gmail.com', '081210115265', NULL, 'Aktif', '2024-01-15'),
('2021002354211', 'Muhammad Ikhsan', 'm.ikhsan@gmail.com', '081234567891', NULL, 'Aktif', '2024-02-20'),
('2021003796544', 'Fitri Fujiyanti', 'Fitri.fuji@gmail.com', '081234567892', NULL, 'Tidak aktif', '2024-03-10');

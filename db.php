<?php
// =========================================================
// Konfigurasi koneksi database
// Sesuaikan DB_USER / DB_PASS jika MySQL Anda memakai password
// =========================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'manajemen_anggota');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("<div style='font-family:sans-serif;padding:40px;max-width:600px;margin:60px auto;background:#fff3f3;border:1px solid #f5c2c2;border-radius:10px'>
            <h2 style='color:#c0392b'>Koneksi database gagal</h2>
            <p>Pastikan MySQL sudah menyala dan database <b>manajemen_anggota</b> sudah di-import dari file <code>database.sql</code>.</p>
            <p style='color:#888'>Detail: " . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}

<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = trim($_POST['nama_lengkap'] ?? '');
    $username   = trim($_POST['username'] ?? '');
    $password   = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi_password'] ?? '';

    if ($nama === '' || $username === '' || $password === '' || $konfirmasi === '') {
        $error = 'Semua kolom wajib diisi.';
    } elseif ($password !== $konfirmasi) {
        $error = 'Konfirmasi password tidak cocok.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username sudah digunakan, silakan pilih username lain.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (nama_lengkap, username, password) VALUES (?, ?, ?)');
            $stmt->execute([$nama, $username, $hash]);
            header('Location: login.php?registered=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrasi - Manajemen Anggota</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-card-header simple">
            <h2><i class="bi bi-person-plus"></i> Registrasi Akun</h2>
        </div>
        <div class="auth-card-body">
            <?php if ($error): ?>
                <div class="error-box"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <label>Nama Lengkap <span style="color:#e5484d">*</span></label>
                <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? '') ?>" required>

                <label>Username <span style="color:#e5484d">*</span></label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>

                <label>Password <span style="color:#e5484d">*</span></label>
                <input type="password" name="password" class="form-control" required>

                <label>Konfirmasi Password <span style="color:#e5484d">*</span></label>
                <input type="password" name="konfirmasi_password" class="form-control" required>

                <button type="submit" class="btn-auth"><i class="bi bi-person-check"></i> Daftar</button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>

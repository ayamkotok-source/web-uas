<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']      = $user['id'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['username']     = $user['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Manajemen Anggota</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-card-header">
            <div class="icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="white">
                    <path d="M12 12c2.7 0 8 1.34 8 4v2H4v-2c0-2.66 5.3-4 8-4zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                </svg>
            </div>
            <h2>Manajemen Anggota</h2>
            <p>Silakan login untuk melanjutkan</p>
        </div>
        <div class="auth-card-body">
            <?php if ($error): ?>
                <div class="error-box"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($_GET['registered'])): ?>
                <div class="success-box"><i class="bi bi-check-circle"></i> Registrasi berhasil, silakan login.</div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <label><i class="bi bi-person"></i> Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>

                <label><i class="bi bi-lock"></i> Password</label>
                <div class="input-eye">
                    <input type="password" id="passwordField" name="password" class="form-control" placeholder="Masukkan password" required>
                    <button type="button" onclick="togglePassword()"><i class="bi bi-eye" id="eyeIcon"></i></button>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="ingat_saya" id="ingatSaya">
                    <label class="form-check-label" for="ingatSaya" style="font-size:13.5px;font-weight:400;">Ingat saya</label>
                </div>

                <button type="submit" class="btn-auth"><i class="bi bi-box-arrow-in-right"></i> Login</button>
            </form>

            <div class="auth-footer">
                Belum punya akun? <a href="register.php">Registrasi</a>
            </div>

            <div class="auth-footer" style="margin-top:14px;font-size:12px;opacity:.7;">
                Demo: username <b>jensss</b> / password <b>12345678910</b>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(){
    const field = document.getElementById('passwordField');
    const icon = document.getElementById('eyeIcon');
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('bi-eye','bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('bi-eye-slash','bi-eye');
    }
}
</script>
</body>
</html>

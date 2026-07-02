<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM anggota WHERE id = ?');
$stmt->execute([$id]);
$anggota = $stmt->fetch();

if (!$anggota) {
    header('Location: dashboard.php?msg=' . urlencode('Data anggota tidak ditemukan.'));
    exit;
}

$error = '';
$active = 'dashboard';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim        = trim($_POST['nim'] ?? '');
    $nama       = trim($_POST['nama_lengkap'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $telepon    = trim($_POST['no_telepon'] ?? '');
    $status     = $_POST['status'] ?? 'Aktif';
    $tglDaftar  = $_POST['tgl_daftar'] ?? date('Y-m-d');

    if ($nim === '' || $nama === '' || $email === '') {
        $error = 'NIM, Nama Lengkap, dan Email wajib diisi.';
    } else {
        $check = $pdo->prepare('SELECT id FROM anggota WHERE nim = ? AND id != ?');
        $check->execute([$nim, $id]);
        if ($check->fetch()) {
            $error = 'NIM sudah digunakan anggota lain.';
        } else {
            $stmt = $pdo->prepare('UPDATE anggota SET nim=?, nama_lengkap=?, email=?, no_telepon=?, status=?, tgl_daftar=? WHERE id=?');
            $stmt->execute([$nim, $nama, $email, $telepon, $status, $tglDaftar, $id]);
            header('Location: dashboard.php?msg=' . urlencode('Data anggota berhasil diperbarui.'));
            exit;
        }
    }
    $anggota = array_merge($anggota, $_POST);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Anggota - Manajemen Anggota</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <div>
                <h1>Edit Anggota</h1>
                <p>Perbarui data anggota</p>
            </div>
        </div>

        <div class="panel" style="max-width:640px;">
            <?php if ($error): ?>
                <div class="error-box"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="edit_anggota.php?id=<?= $anggota['id'] ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                        <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($anggota['nim']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($anggota['nama_lengkap']) ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($anggota['email']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">No. Telepon</label>
                        <input type="text" name="no_telepon" class="form-control" value="<?= htmlspecialchars($anggota['no_telepon'] ?? '') ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="Aktif" <?= $anggota['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="Tidak aktif" <?= $anggota['status'] === 'Tidak aktif' ? 'selected' : '' ?>>Tidak aktif</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tanggal Daftar</label>
                        <input type="date" name="tgl_daftar" class="form-control" value="<?= htmlspecialchars($anggota['tgl_daftar']) ?>">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
                    <a href="dashboard.php" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>

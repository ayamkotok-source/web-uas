<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';

// ---- Statistik ----
$total   = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
$aktif   = $pdo->query("SELECT COUNT(*) FROM anggota WHERE status = 'Aktif'")->fetchColumn();
$tidak   = $pdo->query("SELECT COUNT(*) FROM anggota WHERE status = 'Tidak aktif'")->fetchColumn();
$baruBulanIni = $pdo->query("SELECT COUNT(*) FROM anggota WHERE MONTH(tgl_daftar) = MONTH(CURDATE()) AND YEAR(tgl_daftar) = YEAR(CURDATE())")->fetchColumn();

// ---- Pencarian ----
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM anggota WHERE nama_lengkap LIKE ? OR nim LIKE ? OR email LIKE ? ORDER BY id DESC");
    $like = "%$q%";
    $stmt->execute([$like, $like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM anggota ORDER BY id DESC");
}
$anggotaList = $stmt->fetchAll();

$active = 'dashboard';
$hariIni = date('d F Y');
$bulanIndo = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$hariIniIndo = date('d') . ' ' . $bulanIndo[(int)date('n')] . ' ' . date('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Manajemen Anggota</title>
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
                <h1>Dashboard</h1>
                <p>Selamat datang di sistem manajemen anggota</p>
            </div>
            <div class="page-date"><i class="bi bi-calendar3"></i> <?= $hariIniIndo ?></div>
        </div>

        <?php if (!empty($_GET['msg'])): ?>
        <div class="alert-welcome" id="successAlert">
            <span><i class="bi bi-check-circle"></i> <?= htmlspecialchars($_GET['msg']) ?></span>
            <button type="button" class="btn-close" onclick="document.getElementById('successAlert').remove()"></button>
        </div>
        <?php endif; ?>

        <div class="alert-welcome" id="welcomeAlert">
            <span>Selamat datang, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?>!</span>
            <button type="button" class="btn-close" onclick="document.getElementById('welcomeAlert').remove()"></button>
        </div>

        <div class="stat-grid">
            <div class="stat-card stat-blue">
                <div><div class="stat-number"><?= $total ?></div><div class="stat-label">Total Anggota</div></div>
                <i class="bi bi-people-fill stat-icon"></i>
            </div>
            <div class="stat-card stat-green">
                <div><div class="stat-number"><?= $aktif ?></div><div class="stat-label">Anggota Aktif</div></div>
                <i class="bi bi-person-check-fill stat-icon"></i>
            </div>
            <div class="stat-card stat-amber">
                <div><div class="stat-number"><?= $tidak ?></div><div class="stat-label">Tidak Aktif</div></div>
                <i class="bi bi-person-x-fill stat-icon"></i>
            </div>
            <div class="stat-card stat-cyan">
                <div><div class="stat-number"><?= $baruBulanIni ?></div><div class="stat-label">Baru Bulan Ini</div></div>
                <i class="bi bi-person-plus-fill stat-icon"></i>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h5><i class="bi bi-list-ul"></i> Data Anggota</h5>
                <div style="display:flex;gap:10px;">
                    <form class="search-box" method="GET" action="dashboard.php">
                        <input type="text" name="q" placeholder="Cari anggota..." value="<?= htmlspecialchars($q) ?>">
                        <button type="submit" class="btn btn-light border"><i class="bi bi-search"></i></button>
                    </form>
                    <a href="tambah_anggota.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
                </div>
            </div>

            <div style="overflow-x:auto;">
            <table class="table-anggota">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>NIM</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Tgl Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($anggotaList)): ?>
                        <tr><td colspan="8" style="text-align:center;padding:24px;color:#999;">Belum ada data anggota.</td></tr>
                    <?php else: ?>
                        <?php foreach ($anggotaList as $i => $a): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <img class="avatar-circle" src="https://ui-avatars.com/api/?name=<?= urlencode($a['nama_lengkap']) ?>&background=random&color=fff" alt="foto">
                            </td>
                            <td><?= htmlspecialchars($a['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($a['nim']) ?></td>
                            <td><?= htmlspecialchars($a['email']) ?></td>
                            <td>
                                <?php if ($a['status'] === 'Aktif'): ?>
                                    <span class="badge-status badge-aktif">Aktif</span>
                                <?php else: ?>
                                    <span class="badge-status badge-tidak">Tidak aktif</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($a['tgl_daftar'])) ?></td>
                            <td>
                                <a href="edit_anggota.php?id=<?= $a['id'] ?>" class="btn-icon btn-edit" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                <a href="delete_anggota.php?id=<?= $a['id'] ?>" class="btn-icon btn-delete" title="Hapus" onclick="return confirm('Hapus data <?= htmlspecialchars($a['nama_lengkap']) ?>?');"><i class="bi bi-trash-fill"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>

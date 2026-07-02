<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';

$active = 'laporan';

$statusFilter = $_GET['status'] ?? '';
$bulanFilter  = $_GET['bulan'] ?? '';
$tahunFilter  = $_GET['tahun'] ?? date('Y');

$where  = [];
$params = [];

if ($statusFilter !== '') {
    $where[] = 'status = ?';
    $params[] = $statusFilter;
}
if ($bulanFilter !== '') {
    $where[] = 'MONTH(tgl_daftar) = ?';
    $params[] = $bulanFilter;
}
if ($tahunFilter !== '') {
    $where[] = 'YEAR(tgl_daftar) = ?';
    $params[] = $tahunFilter;
}

$sql = 'SELECT * FROM anggota';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY tgl_daftar ASC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();

$bulanNama = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

// query string dipakai ulang untuk tombol export
$exportQuery = http_build_query(['status' => $statusFilter, 'bulan' => $bulanFilter, 'tahun' => $tahunFilter]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Data Anggota - Manajemen Anggota</title>
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
                <h1><i class="bi bi-file-earmark-bar-graph"></i> Laporan Data Anggota</h1>
            </div>
            <div>
                <a href="export_pdf.php?<?= $exportQuery ?>" target="_blank" class="btn-export-pdf"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
                <a href="export_excel.php?<?= $exportQuery ?>" class="btn-export-excel"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header"><h5><i class="bi bi-funnel"></i> Filter Laporan</h5></div>
            <form method="GET" action="laporan.php" class="filter-grid">
                <div>
                    <label>Status</label>
                    <select name="status">
                        <option value="" <?= $statusFilter === '' ? 'selected' : '' ?>>Semua Status</option>
                        <option value="Aktif" <?= $statusFilter === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="Tidak aktif" <?= $statusFilter === 'Tidak aktif' ? 'selected' : '' ?>>Tidak aktif</option>
                    </select>
                </div>
                <div>
                    <label>Bulan</label>
                    <select name="bulan">
                        <option value="" <?= $bulanFilter === '' ? 'selected' : '' ?>>Semua Bulan</option>
                        <?php foreach ($bulanNama as $num => $nama): ?>
                            <option value="<?= $num ?>" <?= (string)$bulanFilter === (string)$num ? 'selected' : '' ?>><?= $nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Tahun</label>
                    <select name="tahun">
                        <?php for ($y = date('Y') + 1; $y >= date('Y') - 4; $y--): ?>
                            <option value="<?= $y ?>" <?= (string)$tahunFilter === (string)$y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-filter"><i class="bi bi-search"></i> Filter</button>
                </div>
            </form>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h5><i class="bi bi-table"></i> Data Anggota <span class="records-badge"><?= count($data) ?> records</span></h5>
            </div>
            <div style="overflow-x:auto;">
            <table class="table-anggota">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Status</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="7" style="text-align:center;padding:24px;color:#999;">Tidak ada data untuk filter ini.</td></tr>
                    <?php else: ?>
                        <?php foreach ($data as $i => $a): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($a['nim']) ?></td>
                            <td><?= htmlspecialchars($a['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($a['email']) ?></td>
                            <td><?= htmlspecialchars($a['no_telepon'] ?? '-') ?></td>
                            <td>
                                <?php if ($a['status'] === 'Aktif'): ?>
                                    <span class="badge-status badge-aktif">Aktif</span>
                                <?php else: ?>
                                    <span class="badge-status badge-tidak">Tidak aktif</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d-m-Y', strtotime($a['tgl_daftar'])) ?></td>
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

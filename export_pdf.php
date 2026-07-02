<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';

$statusFilter = $_GET['status'] ?? '';
$bulanFilter  = $_GET['bulan'] ?? '';
$tahunFilter  = $_GET['tahun'] ?? '';

$where  = [];
$params = [];
if ($statusFilter !== '') { $where[] = 'status = ?'; $params[] = $statusFilter; }
if ($bulanFilter  !== '') { $where[] = 'MONTH(tgl_daftar) = ?'; $params[] = $bulanFilter; }
if ($tahunFilter  !== '') { $where[] = 'YEAR(tgl_daftar) = ?'; $params[] = $tahunFilter; }

$sql = 'SELECT * FROM anggota';
if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY tgl_daftar ASC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Laporan - Manajemen Anggota</title>
<style>
    body{ font-family: Arial, sans-serif; padding: 30px; color:#222; }
    h2{ margin-bottom:2px; }
    p.sub{ color:#666; margin-top:0; }
    table{ width:100%; border-collapse:collapse; margin-top:16px; }
    th, td{ border:1px solid #ccc; padding:8px 10px; font-size:13px; text-align:left; }
    th{ background:#f2f2f7; }
    .badge{ padding:3px 10px; border-radius:12px; color:#fff; font-size:11px; }
    .aktif{ background:#28a768; }
    .tidak{ background:#9a9fb0; }
    .no-print{ margin-bottom:16px; }
    @media print{ .no-print{ display:none; } }
</style>
</head>
<body onload="window.print()">
    <div class="no-print">
        <button onclick="window.print()">🖨️ Cetak / Simpan sebagai PDF</button>
    </div>
    <h2>Laporan Data Anggota</h2>
    <p class="sub">Dicetak pada <?= date('d F Y') ?> — Manajemen Anggota</p>
    <table>
        <thead>
            <tr>
                <th>No</th><th>NIM</th><th>Nama Lengkap</th><th>Email</th><th>No. Telepon</th><th>Status</th><th>Tgl Daftar</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $i => $a): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($a['nim']) ?></td>
                <td><?= htmlspecialchars($a['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($a['email']) ?></td>
                <td><?= htmlspecialchars($a['no_telepon'] ?? '-') ?></td>
                <td><span class="badge <?= $a['status'] === 'Aktif' ? 'aktif' : 'tidak' ?>"><?= htmlspecialchars($a['status']) ?></span></td>
                <td><?= date('d-m-Y', strtotime($a['tgl_daftar'])) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

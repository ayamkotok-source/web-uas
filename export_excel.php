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

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="laporan_anggota_' . date('Y-m-d') . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');
?>
<table border="1">
    <tr>
        <th colspan="7" style="font-size:16px;"><b>Laporan Data Anggota</b></th>
    </tr>
    <tr>
        <th>No</th>
        <th>NIM</th>
        <th>Nama Lengkap</th>
        <th>Email</th>
        <th>No. Telepon</th>
        <th>Status</th>
        <th>Tanggal Daftar</th>
    </tr>
    <?php foreach ($data as $i => $a): ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><?= htmlspecialchars($a['nim']) ?></td>
        <td><?= htmlspecialchars($a['nama_lengkap']) ?></td>
        <td><?= htmlspecialchars($a['email']) ?></td>
        <td><?= htmlspecialchars($a['no_telepon'] ?? '-') ?></td>
        <td><?= htmlspecialchars($a['status']) ?></td>
        <td><?= date('d-m-Y', strtotime($a['tgl_daftar'])) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare('DELETE FROM anggota WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: dashboard.php?msg=' . urlencode('Data anggota berhasil dihapus.'));
    exit;
}

header('Location: dashboard.php');
exit;

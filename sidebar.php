<?php
// $active harus diset di halaman pemanggil: dashboard | tambah | laporan
$active = $active ?? '';
$namaUser = $_SESSION['nama_lengkap'] ?? 'Pengguna';
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="white">
                <path d="M12 12c2.7 0 8 1.34 8 4v2H4v-2c0-2.66 5.3-4 8-4zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
            </svg>
        </div>
        <div>
            <div class="brand-title">Manajemen<br>Anggota</div>
        </div>
    </div>

    <div class="sidebar-welcome">Welcome, <?= htmlspecialchars($namaUser) ?></div>

    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-link <?= $active === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="tambah_anggota.php" class="nav-link <?= $active === 'tambah' ? 'active' : '' ?>">
            <i class="bi bi-person-plus-fill"></i> Tambah Anggota
        </a>
        <a href="laporan.php" class="nav-link <?= $active === 'laporan' ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-bar-graph-fill"></i> Laporan
        </a>
        <a href="logout.php" class="nav-link nav-logout" onclick="return confirm('Yakin ingin logout?');">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </nav>
</aside>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Absensi Sekolah</title>
    <?php include 'favicon.php'; ?>
    <link rel="stylesheet" href="css/dashboardStyle.css">
</head>
<body>
    <!-- Modern Header -->
    <header class="main-header no-print">
        <button class="hamburger" id="hamburger" onclick="toggleSidebar()">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="header-brand">
            <img style="all: revert; width: 150px; height: fit-content; margin-left: 20px;" src="app_logo/logo.png" alt="">
        </div>
            
    </header>

    <!-- Modern Sidebar -->
    <aside class="sidebar no-print" id="sidebar">
        <div class="sidebar-header">
            <div class="admin-panel">
                <div class="admin-avatar">
                    <span><?= strtoupper(substr($_SESSION['user'], 0, 1)) ?></span>
                </div>
                <div class="admin-info">
                    <h3 class="admin-name"><?= htmlspecialchars(is_array($_SESSION['user']) ? ($_SESSION['user']['username'] ?? 'Admin') : ($_SESSION['user'] ?? 'Admin')) ?></h3>
                    <span class="admin-role">
                        <?php 
                        $role = htmlspecialchars($_SESSION['user_role'] ?? 'Admin');
                        $roleBadge = $role === 'sAdmin' ? '👑 Super Admin' : '👤 Admin';
                        echo $roleBadge;
                        ?>
                    </span>
                </div>
            </div>
        </div>
        
        <nav class="sidebar-menu">
            <a href="dashboard.php?page=dashboard" class="menu-item" data-page="dashboard">
                <span class="icon">🏠</span>
                <span class="label">Dashboard</span>
            </a>
            <a href="dashboard.php?page=kelola-kelas" class="menu-item" data-page="kelola-kelas">
                <span class="icon">📚</span>
                <span class="label">Kelola Kelas</span>
            </a>
            <a href="dashboard.php?page=data-siswa" class="menu-item" data-page="data-siswa">
                <span class="icon">👨‍🎓</span>
                <span class="label">Data Siswa</span>
            </a>
            <a href="dashboard.php?page=rekap-absensi" class="menu-item" data-page="rekap-absensi">
                <span class="icon">📊</span>
                <span class="label">Rekapitulasi</span>
            </a>
            
            
            <!-- Guru Attendance System -->
            <hr style="margin: 12px 0; border: none; border-top: 1px solid #e5e7eb;">
            <div style="padding: 12px 16px; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Guru</div>

            <a href="dashboard.php?page=data-guru" class="menu-item" data-page="data-guru">
                <span class="icon">👩‍🏫</span>
                <span class="label">Data Guru</span>
            </a>
            
            <a href="dashboard.php?page=guru-absensi" class="menu-item" data-page="guru-absensi">
                <span class="icon">✍️</span>
                <span class="label">Absensi Guru</span>
            </a>
            <a href="dashboard.php?page=download-recap-guru" class="menu-item" data-page="download-recap-guru">
                <span class="icon">📈</span>
                <span class="label">Rekap Absensi Guru</span>
            </a>

            <hr style="margin: 12px 0; border: none; border-top: 1px solid #e5e7eb;">
            <div style="padding: 12px 16px; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Cetak Kartu</div>
            <a href="dashboard.php?page=cetak-kartu" class="menu-item" data-page="cetak-kartu">
                <span class="icon">🖨️</span>
                <span class="label">Kartu Siswa</span>
            </a>
            <a href="dashboard.php?page=cetak-kartu-guru" class="menu-item" data-page="cetak-kartu-guru">
                <span class="icon">🖨️</span>
                <span class="label">Kartu Guru</span>
            </a>
            
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'sAdmin'): ?>
                <hr style="margin: 12px 0; border: none; border-top: 1px solid #e5e7eb;">
                <div style="padding: 12px 16px; font-size: 0.85rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Admin</div>
                <a href="dashboard.php?page=admin" class="menu-item" data-page="admin">
                    <span class="icon">👤</span>
                    <span class="label">Manajemen Admin</span>
                </a>
                <a href="dashboard.php?page=manajemen-sekolah" class="menu-item" data-page="manajemen-sekolah">
                    <span class="icon">⚙️</span>
                    <span class="label">Pengaturan</span>
                </a>
            <?php endif; ?>
        </nav>

        <div class="sidebar-footer">
            <a href="logout.php" class="logout-link">
                <span class="icon">🚪</span>
                <span class="label">Keluar Akun</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content bg-print" id="mainContent" >
        <div class="content-wrapper bg-print">
            <?php echo $content; ?>
        </div>
    </main>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <script src="js/dashboardScript.js"></script>
    <script>
        // Simple sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        }

        // Highlight active menu item
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = new URLSearchParams(window.location.search).get('page') || 'dashboard';
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                if (item.dataset.page === currentPage) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
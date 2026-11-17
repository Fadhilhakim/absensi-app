<?php

session_start();

require_once __DIR__ . '/App/Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}


spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class) . '.php';
    $file = __DIR__ . '/' . $path;
    if (file_exists($file)) {
        require_once $file;
    }
});


// Router
use App\Controller\DashboardController;
use App\Controller\KelolaKelasController;
use App\Controller\DataSiswaController;
use App\Controller\DataGuruController;
use App\Controller\RekapAbsensiController;
use App\Controller\ManajemenSekolahController;
use App\Controller\CetakkartuController;
use App\Controller\CetakKartuGuruController;
use App\Controller\AbsensiGuruController;
use App\Controller\AdminController;

$page = $_GET['page'] ?? 'dashboard';

switch ($page) {
    case 'dashboard':
        $controller = new DashboardController();
        break;
    case 'kelola-kelas':
        $controller = new KelolaKelasController();
        break;
    case 'data-siswa':
        $controller = new DataSiswaController();
        break;
    case 'data-guru':
        $controller = new DataGuruController();
        break;
    case 'guru-absensi':
        $controller = new AbsensiGuruController();
        break;
    case 'cetak-kartu-guru':
        $controller = new CetakKartuGuruController();
        break;
    case 'download-recap-guru':
        $controller = new AbsensiGuruController();
        break;
    case 'rekap-absensi':
        $controller = new RekapAbsensiController();
        break;
    case 'manajemen-sekolah':
        $controller = new ManajemenSekolahController();
        break;
    case 'cetak-kartu':
        $controller = new CetakKartuController();
        break;
    case 'admin':
        $controller = new AdminController();
        break;
    default:
        $controller = new DashboardController();
        break;
}

$controller->index();
?>

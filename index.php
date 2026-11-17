<?php
// session_start();

require_once __DIR__ . '/App/Model/Database.php';
require_once __DIR__ . '/App/Model/SiswaModel.php';
require_once __DIR__ . '/App/Model/SekolahModel.php';
require_once __DIR__ . '/App/Model/AbsensiGuru.php';
require_once __DIR__ . '/App/Model/GuruModel.php';
require_once __DIR__ . '/App/Model/Absensi.php';
require_once __DIR__ . '/App/Controller/AbsensiController.php';

use App\Controller\AbsensiController;

$controller = new AbsensiController();
$controller->index();

<?php
namespace App\Controller;

use App\Model\GuruModel;
use App\Model\SekolahModel;

class CetakKartuGuruController {
    public function index() {
        $guruModel = new GuruModel();
        $sekolahModel = new SekolahModel();

        $guru = $guruModel->getAll();
        $data = $sekolahModel->get();

        $action = $_GET['action'] ?? null;
        $nip = $_GET['nip'] ?? null;

        date_default_timezone_set('Asia/Makassar');

        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $tanggal = date('d');
        $bulanSekarang = $bulan[(int)date('m')];
        $tahun = date('Y');

        $formatTanggal = $tanggal . ' ' . $bulanSekarang . ' ' . $tahun;

        // Handle print individual guru card
        if ($action === 'print-individual' && $nip) {
            $guruData = $guruModel->getByNip($nip);
            if (!$guruData) {
                echo "<script>alert('Data guru tidak ditemukan'); window.history.back();</script>";
                exit;
            }

            $tataTertibList = !empty($data['tata_tertib']) ? explode("\n", $data['tata_tertib']) : [];
            $tanggalHariIni = $formatTanggal;
            $kepsek = $guruModel->getKepsek();

            require_once 'kartu/kartu-guru.php';
            return;
        }

        // Handle print all guru cards
        if ($action === 'print-all') {
            $guruList = $guruModel->getAll();
            if (empty($guruList)) {
                echo "<script>alert('Tidak ada data guru'); window.history.back();</script>";
                exit;
            }

            $tataTertibList = !empty($data['tata_tertib']) ? explode("\n", $data['tata_tertib']) : [];
            $tanggalHariIni = $formatTanggal;
            $kepsek = $guruModel->getKepsek();

            require_once 'kartu/kartu-guru-all.php';
            return;
        }

        // Handle POST request for bulk print
        $method = $_SERVER['REQUEST_METHOD'];
        if ($action === 'cetak' && $method === 'POST') {
            $guruList = $guruModel->getAll();

            if (!$guruList) {
                echo "<script>alert('Data tidak ditemukan'); window.history.back();</script>";
                exit;
            }

            $tataTertibList = !empty($data['tata_tertib']) ? explode("\n", $data['tata_tertib']) : [];
            $tanggalHariIni = $formatTanggal;
            $kepsek = $guruModel->getKepsek();

            require_once 'kartu/kartu-guru-all.php';
            return;
        }

        // Default view - show the form and list
        $data = [
            'guru' => $guru,
            'sekolah' => $sekolahModel->get(),
        ];

        ob_start();
        require_once 'App/View/dashboard/cetak_kartu_guru.php';
        $content = ob_get_clean();
        require_once 'App/View/dashboard.php';
    }
}

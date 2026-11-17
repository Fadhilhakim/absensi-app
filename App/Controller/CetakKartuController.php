<?php
namespace App\Controller;

use App\Model\SekolahModel;
use App\Model\GuruModel;
use App\Model\SiswaModel;
use App\Model\KelasModel;

class CetakkartuController {
    public function index() {
        $sekolah = new SekolahModel();
        $siswaModel = new SiswaModel();
        $kelasModel = new KelasModel();
        $guruModel = new GuruModel();

        $siswa = $siswaModel->getAll();
        $data = $sekolah->get(); 
        $kelas = $kelasModel->getAll();
        $dataSiswa = null;

        $action = $_GET['action'] ?? null;
        $nisn = $_GET['nisn'] ?? null;
        $kelasId = $_GET['kelas_id'] ?? null;

        if ($action === 'preview' && $nisn) { 
            $preview = $siswaModel->getOneByNISN($nisn);
            ob_start();
            require_once 'App/View/dashboard/cetak_kartu.php';
            $content = ob_get_clean();
            require_once 'App/View/dashboard.php';
            return;
        }

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

        if ($action === 'cetak' && $nisn) {
            $dataSiswa = $siswaModel->getOneByNISN($nisn);
            $tataTertibList = !empty($data['tata_tertib']) ? explode("\n", $data['tata_tertib']) : [];
            $kepsek = $guruModel->getKepsek();

            $tanggalHariIni = $formatTanggal;
            if (!$dataSiswa) {
                echo "<script>alert('Data siswa tidak ditemukan'); window.history.back();</script>";
                exit;
            }
            require_once 'kartu/kartu.php';
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'];

        if ($action === 'cetak' && $method === 'POST') {


            // IF cetak per kelas:
            if (!empty($_POST['kelas'])) {
                $kelasId = $_POST['kelas'];
                $dataSiswa = $siswaModel->getByKelas($kelasId);
            }

            // Cek data
            if (!$dataSiswa) {
                echo "<script>alert('Data tidak ditemukan'); window.history.back();</script>";
                exit;
            }

            $tataTertibList = !empty($data['tata_tertib']) ? explode("\n", $data['tata_tertib']) : [];
            $tanggalHariIni = $formatTanggal;
            $kepsek = $guruModel->getKepsek();

            require_once 'kartu/kartu_kelas.php';
            return;
        }


        ob_start();
        require_once 'App/View/dashboard/cetak_kartu.php';
        $content = ob_get_clean();
        require_once 'App/View/dashboard.php';
    }
}
?>

<?php
namespace App\Controller;

use App\Model\AbsensiModel;
use App\Model\KelasModel;
use App\Model\SekolahModel;

class RekapAbsensiController {

    public function index() {
        $absensiModel = new AbsensiModel();
        $kelasModel = new KelasModel(); 
        $sekolahModel = new SekolahModel();
        
        // Ambil parameter GET
        $tanggal = $_GET['tanggal'] ?? null;
        $kelasId  = isset($_GET['kelas']) && $_GET['kelas'] !== '' ? (int)$_GET['kelas'] : null;
        $bulan = $_GET['bulan'] ?? null;
        $nama_kelas = $kelasModel->getAll();
        
        // Jika ada permintaan update data
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_absensi'])) {
            $id = $_POST['id_absensi'];
            $status = $_POST['status'];
            $keterangan = $_POST['keterangan'];
            
            if ($absensiModel->update($id, $status, $keterangan)) {
                $_SESSION['success'] = "Data absensi berhasil diperbarui.";
            } else {
                $_SESSION['error'] = "Gagal memperbarui data absensi.";
            }
            
            $tanggal = $_POST['tanggal'] ?? null;
            $kelas   = $_POST['kelas'] ?? null;

            $url = '?page=rekap-absensi';
            if ($tanggal) $url .= '&tanggal=' . urlencode($tanggal);
            if ($kelas)   $url .= '&kelas=' . urlencode($kelas);

            header("Location: $url");
            exit;
        }

        // Jika ada parameter bulan, tampilkan halaman rekap bulanan
        if (!empty($bulan)) {
            return $this->showMonthlyRecap($absensiModel, $kelasModel, $bulan, $kelasId);
        }

        // Ambil data absensi berdasarkan filter
        if ($tanggal && $kelasId) {
            $absensi = $absensiModel->getByDateAndClass($tanggal, $kelasId);
        } elseif ($tanggal) {
            $absensi = $absensiModel->getByDate($tanggal);
        } elseif ($kelasId) {
            $absensi = $absensiModel->getByClass($kelasId);
        } else {
            $absensi = $absensiModel->getToday();
        }
        $data = [
            'absensi' => $absensi,
            'tanggal' => $tanggal,
            'nama_kelas' => $nama_kelas,
        ];

        ob_start();
        require_once 'App/View/dashboard/rekap_absensi.php';
        $content = ob_get_clean();
        require_once 'App/View/dashboard.php';
    }

    /**
     * Tampilkan halaman rekap absensi bulanan
     */
    private function showMonthlyRecap($absensiModel, $kelasModel, $bulan, $kelasId = null) {
        // Validasi format bulan (YYYY-MM)
        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $_SESSION['error'] = "Format bulan tidak valid.";
            header("Location: ?page=rekap-absensi");
            exit;
        }

        // Ambil data absensi
        if ($kelasId) {
            $rawData = $absensiModel->getByMonthAndClass($bulan, $kelasId);
            $namaKelasSelected = '';
            foreach ($kelasModel->getAll() as $kls) {
                if ($kls['id_kelas'] == $kelasId) {
                    $namaKelasSelected = $kls['nama_kelas'];
                    break;
                }
            }
        } else {
            $rawData = $absensiModel->getByMonth($bulan);
            $namaKelasSelected = null;
        }

        if (empty($rawData)) {
            $_SESSION['error'] = "Tidak ada data absensi untuk periode yang dipilih.";
            header("Location: ?page=rekap-absensi");
            exit;
        }

        // Proses data untuk ditampilkan per kelas
        $groupedData = [];
        $daftarTanggal = [];
        
        foreach ($rawData as $row) {
            $kelas = $row['nama_kelas'];
            $tanggal = $row['tanggal'];
            
            // Kumpulkan semua tanggal unik
            if (!in_array($tanggal, $daftarTanggal)) {
                $daftarTanggal[] = $tanggal;
            }
            
            // Kelompokkan per kelas dan siswa
            if (!isset($groupedData[$kelas])) {
                $groupedData[$kelas] = [];
            }
            
            $nisn = $row['nisn'];
            $nama = $row['nama'];
            $key = "{$nisn}-{$nama}";
            
            if (!isset($groupedData[$kelas][$key])) {
                $groupedData[$kelas][$key] = [
                    'nisn' => $nisn,
                    'nama' => $nama,
                    'absensi_by_date' => []
                ];
            }
            
            $groupedData[$kelas][$key]['absensi_by_date'][$tanggal] = [
                'status' => $row['status'],
                'keterangan' => $row['keterangan']
            ];
        }

        // Urutkan tanggal
        sort($daftarTanggal);
        
        // Format bulan untuk ditampilkan
        $bulanTampil = \DateTime::createFromFormat('Y-m', $bulan)->format('F Y');
        $totalHariSekolah = count($daftarTanggal);

        $absensiData = $rawData;

        ob_start();
        require_once 'App/View/dashboard/download_recap.php';
        $content = ob_get_clean();
        
        // Tampilkan langsung tanpa layout dashboard
        echo $content;
        exit;
    }
}
?>

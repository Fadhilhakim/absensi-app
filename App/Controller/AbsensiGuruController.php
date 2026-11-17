<?php
namespace App\Controller;

use App\Model\AbsensiGuru;
use App\Model\GuruModel;

class AbsensiGuruController {
    public function index() {
        $absensiGuruModel = new AbsensiGuru();
        $guruModel = new GuruModel();
        
        // Ambil parameter GET
        $tanggal = $_GET['tanggal'] ?? null;
        $bulan = $_GET['bulan'] ?? null;
        
        // Jika ada permintaan update data
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_absensi'])) {
            $id = $_POST['id_absensi'];
            $status = $_POST['status'];
            $keterangan = $_POST['keterangan'];
            
            if ($absensiGuruModel->update($id, $status, $keterangan)) {
                $_SESSION['success'] = "Data absensi guru berhasil diperbarui.";
            } else {
                $_SESSION['error'] = "Gagal memperbarui data absensi guru.";
            }
            
            $tanggal = $_POST['tanggal'] ?? null;

            $url = '?page=guru-absensi';
            if ($tanggal) $url .= '&tanggal=' . urlencode($tanggal);

            header("Location: $url");
            exit;
        }

        // Jika ada parameter bulan, tampilkan halaman rekap bulanan
        if (!empty($bulan)) {
            return $this->showMonthlyRecap($absensiGuruModel, $bulan);
        }

        // Ambil data absensi berdasarkan filter
        if ($tanggal) {
            $absensi = $absensiGuruModel->getByDate($tanggal);
        } else {
            $absensi = $absensiGuruModel->getToday();
        }
        
        $data = [
            'absensi' => $absensi,
            'tanggal' => $tanggal,
        ];

        // Determine which view to show based on page parameter
        $currentPage = $_GET['page'] ?? 'guru-absensi';
        
        if ($currentPage === 'download-recap-guru') {
            // Show the recap view with current month by default
            $bulanDefault = date('Y-m');
            return $this->showMonthlyRecap($absensiGuruModel, $bulanDefault);
        }

        ob_start();
        require_once 'App/View/dashboard/guru_absensi.php';
        $content = ob_get_clean();
        require_once 'App/View/dashboard.php';
    }

    /**
     * Tampilkan halaman rekap absensi guru bulanan
     */
    private function showMonthlyRecap($absensiGuruModel, $bulan) {
        // Validasi format bulan (YYYY-MM)
        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $_SESSION['error'] = "Format bulan tidak valid.";
            header("Location: ?page=guru-absensi");
            exit;
        }

        // Ambil data absensi
        $absensiData = $absensiGuruModel->getByMonth($bulan);

        // Struktur data untuk tampilan dengan guru
        $guruAbsensi = [];
        foreach ($absensiData as $row) {
            $nip = $row['nip'];
            if (!isset($guruAbsensi[$nip])) {
                $guruAbsensi[$nip] = [
                    'nip' => $row['nip'],
                    'nama' => $row['nama'],
                    'foto' => $row['foto'],
                    'jabatan' => $row['jabatan'],
                    'absensi' => []
                ];
            }
            $guruAbsensi[$nip]['absensi'][] = $row;
        }

        // Hitung statistik
        $stats = $this->calculateStats($absensiData);

        $data = [
            'absensiData' => $absensiData,
            'guruAbsensi' => $guruAbsensi,
            'bulan' => $bulan,
            'stats' => $stats,
        ];

        ob_start();
        require_once 'App/View/dashboard/download_recap_guru.php';
        $content = ob_get_clean();
        require_once 'App/View/dashboard.php';
    }

    /**
     * Hitung statistik absensi
     */
    private function calculateStats($data) {
        $stats = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
            'terlambat' => 0,
        ];

        foreach ($data as $row) {
            $status = strtolower($row['status'] ?? '');
            $keterangan = strtolower($row['keterangan'] ?? '');

            if ($status === 'hadir') {
                $stats['hadir']++;
            } elseif ($status === 'sakit') {
                $stats['sakit']++;
            } elseif ($status === 'izin') {
                $stats['izin']++;
            } elseif (in_array($status, ['alpha', 'alpa'])) {
                $stats['alpha']++;
            }
            
            if ($keterangan === 'terlambat') {
                $stats['terlambat']++;
            }
        }

        return $stats;
    }
}

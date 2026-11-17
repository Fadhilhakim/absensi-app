<?php
namespace App\Controller;

use App\Model\SiswaModel;
use App\Model\GuruModel;
use App\Model\AbsensiModel;
use App\Model\Database;

class DashboardController {
    public function index() {
        $siswaModel = new SiswaModel();
        $guruModel = new GuruModel();
        $absensiModel = new AbsensiModel();

        // Statistik cepat
        $totalSiswa = 0;
        $totalGuru = 0;
        $hadirHariIni = 0;
        $tidakHadirHariIni = 0;

        // Total siswa & guru menggunakan model yang ada
        try {
            $totalSiswa = count($siswaModel->getAll());
        } catch (\Throwable $e) {}
        try {
            $totalGuru = count($guruModel->getAll());
        } catch (\Throwable $e) {}

        // Kehadiran hari ini menggunakan AbsensiModel->getToday()
        try {
            $todayRecords = $absensiModel->getToday();
            foreach ($todayRecords as $rec) {
                $status = $rec['status'] ?? '';
                if ($status === 'Hadir') {
                    $hadirHariIni++;
                } elseif (in_array($status, ['Sakit','Izin','Alpha'])) {
                    $tidakHadirHariIni++;
                }
            }
        } catch (\Throwable $e) {}

        // Tren kehadiran 7 hari terakhir (menghitung jumlah Hadir per hari)
        $mapHari = ['Sun' => 'Min', 'Mon' => 'Sen', 'Tue' => 'Sel', 'Wed' => 'Rab', 'Thu' => 'Kam', 'Fri' => 'Jum', 'Sat' => 'Sab'];
        $labels = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime("-{$i} day"));
            $abbr = date('D', strtotime($tgl));
            $labels[] = $mapHari[$abbr] ?? $abbr;

            $countHadir = 0;
            try {
                $rows = $absensiModel->getByDate($tgl);
                foreach ($rows as $row) {
                    if (($row['status'] ?? '') === 'Hadir') {
                        $countHadir++;
                    }
                }
            } catch (\Throwable $e) {}
            $values[] = $countHadir;
        }
        $tren7Hari = ['labels' => $labels, 'values' => $values];

        // Kueri agregasi untuk daftar khusus
        $conn = Database::getConnection();
        $alphaGt3 = [];
        $izinGt4 = [];
        $sakitGt7 = [];
        $top5Hadir = [];
        try {
            $stmt = $conn->prepare("SELECT s.nisn, s.nama, k.nama_kelas, COUNT(*) AS jumlah FROM absensi a JOIN siswa s ON a.nisn = s.nisn LEFT JOIN kelas k ON s.kelas = k.id_kelas WHERE a.status = 'Alpha' GROUP BY a.nisn ORDER BY jumlah DESC");
            $stmt->execute();
            $alphaGt3 = array_values(array_filter($stmt->fetchAll(\PDO::FETCH_ASSOC), function($r){ return (int)$r['jumlah'] > 3; }));
        } catch (\Throwable $e) {}
        try {
            $stmt = $conn->prepare("SELECT s.nisn, s.nama, k.nama_kelas, COUNT(*) AS jumlah FROM absensi a JOIN siswa s ON a.nisn = s.nisn LEFT JOIN kelas k ON s.kelas = k.id_kelas WHERE a.status = 'Izin' GROUP BY a.nisn ORDER BY jumlah DESC");
            $stmt->execute();
            $izinGt4 = array_values(array_filter($stmt->fetchAll(\PDO::FETCH_ASSOC), function($r){ return (int)$r['jumlah'] > 4; }));
        } catch (\Throwable $e) {}
        try {
            $stmt = $conn->prepare("SELECT s.nisn, s.nama, k.nama_kelas, COUNT(*) AS jumlah FROM absensi a JOIN siswa s ON a.nisn = s.nisn LEFT JOIN kelas k ON s.kelas = k.id_kelas WHERE a.status = 'Sakit' GROUP BY a.nisn ORDER BY jumlah DESC");
            $stmt->execute();
            $sakitGt7 = array_values(array_filter($stmt->fetchAll(\PDO::FETCH_ASSOC), function($r){ return (int)$r['jumlah'] > 7; }));
        } catch (\Throwable $e) {}
        try {
            $stmt = $conn->prepare("SELECT s.nisn, s.nama, k.nama_kelas, COUNT(*) AS jumlah FROM absensi a JOIN siswa s ON a.nisn = s.nisn LEFT JOIN kelas k ON s.kelas = k.id_kelas WHERE a.status = 'Hadir' GROUP BY a.nisn ORDER BY jumlah DESC LIMIT 5");
            $stmt->execute();
            $top5Hadir = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {}

        // Daftar keterlambatan bulan berjalan (berdasarkan keterangan = 'Terlambat')
        $terlambatList = [];
        try {
            $stmt = $conn->prepare(
                "SELECT s.nisn, s.nama, k.nama_kelas, COUNT(*) AS terlambat
                 FROM absensi a
                 JOIN siswa s ON a.nisn = s.nisn
                 LEFT JOIN kelas k ON s.kelas = k.id_kelas
                 WHERE a.keterangan = 'Terlambat' AND DATE_FORMAT(a.tanggal, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
                 GROUP BY a.nisn
                 ORDER BY terlambat DESC"
            );
            $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $r) {
                $c = (int)$r['terlambat'];
                $rekom = '';
                if ($c > 7) {
                    $rekom = 'Sanksi lanjut: tugas tambahan/skorsing (kebijakan sekolah)';
                } elseif ($c >= 5) {
                    $rekom = 'Pemanggilan orang tua + surat pernyataan';
                } elseif ($c >= 3) {
                    $rekom = 'Teguran tertulis + pemberitahuan orang tua';
                } elseif ($c >= 1) {
                    $rekom = 'Teguran lisan';
                }
                $terlambatList[] = [
                    'nisn' => $r['nisn'],
                    'nama' => $r['nama'],
                    'nama_kelas' => $r['nama_kelas'],
                    'jumlah' => $c,
                    'rekomendasi' => $rekom
                ];
            }
        } catch (\Throwable $e) {}

        // Inject data ke view sebagai variabel global window.*
        $jsData = '<script>'
            . 'window.__TOTAL_SISWA__ = ' . json_encode($totalSiswa) . ';'
            . 'window.__TOTAL_GURU__ = ' . json_encode($totalGuru) . ';'
            . 'window.__HADIR_HARI_INI__ = ' . json_encode($hadirHariIni) . ';'
            . 'window.__TIDAK_HADIR_HARI_INI__ = ' . json_encode($tidakHadirHariIni) . ';'
            . 'window.__TREN_7_HARI__ = ' . json_encode($tren7Hari) . ';'
            . 'window.__ALPHA_GT3__ = ' . json_encode($alphaGt3) . ';'
            . 'window.__IZIN_GT4__ = ' . json_encode($izinGt4) . ';'
            . 'window.__SAKIT_GT7__ = ' . json_encode($sakitGt7) . ';'
            . 'window.__TOP5_HADIR__ = ' . json_encode($top5Hadir) . ';'
            . 'window.__TERLAMBAT_LIST__ = ' . json_encode($terlambatList) . ';'
            . '</script>';

        ob_start();
        require_once 'App/View/dashboard/main_dashboard.php';
        $content = $jsData . ob_get_clean();
        require_once 'App/View/dashboard.php';
    }
}
?>

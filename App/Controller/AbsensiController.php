<?php

namespace App\Controller;

use App\Model\SekolahModel;
use App\Model\SiswaModel;
use App\Model\GuruModel;
use App\Model\Absensi;
use App\Model\AbsensiGuru;

class AbsensiController
{
    private $siswaModel;
    private $guruModel;
    private $absensiModel;
    private $absensiGuruModel;
    private $sekolahModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->guruModel = new GuruModel();
        $this->absensiModel = new Absensi();
        $this->absensiGuruModel = new AbsensiGuru();
        $this->sekolahModel = new SekolahModel();
    }

    public function index()
    {
        session_start();

        date_default_timezone_set('Asia/Makassar');
        $tanggalHariIni = date("Y-m-d");
        $waktuSekarang = date('H:i:s');

        // 🔹 ABSENSI SISWA
        if ($this->absensiModel->countByDate($tanggalHariIni) == 0) {
            $semuaSiswa = $this->siswaModel->getAll();

            foreach ($semuaSiswa as $siswa) {
                $this->absensiModel->insertDefault([
                    'nisn' => $siswa['nisn'],
                    'tanggal' => $tanggalHariIni,
                    'time' => null,
                    'status' => null,
                    'keterangan' => 'Tidak Absen',
                    'pulang' => null,
                ]);
            }
        }

        // 🔹 ABSENSI GURU
        if ($this->absensiGuruModel->countByDate($tanggalHariIni) == 0) {
            $semuaGuru = $this->guruModel->getAll();

            foreach ($semuaGuru as $guru) {
                $this->absensiGuruModel->insertDefault([
                    'nip' => $guru['nip'],
                    'tanggal' => $tanggalHariIni,
                    'time' => null,
                    'status' => null,
                    'keterangan' => 'Tidak Absen',
                    'pulang' => null,
                ]);
            }
        }

        // data absensi siswa dan guru bisa dipanggil terpisah
        $absensiSiswa = $this->absensiModel->getByDate($tanggalHariIni);
        $absensiGuru = $this->absensiGuruModel->getByDate($tanggalHariIni);

        // Ambil waktu TIME dari DB 
        $jamMasukStr = $sekolah['jam_masuk'] ?? null;   // "07:00:00"
        $jamPulangStr = $sekolah['jam_pulang'] ?? null;  // "15:00:00"

        error_log("DB jam_masuk raw: " . var_export($jamMasukStr, true));
        error_log("DB jam_pulang raw: " . var_export($jamPulangStr, true));

        $today = date('Y-m-d');
        $sekolah = $this->sekolahModel->get();

        $jamMasukStr  = $sekolah['jam_masuk'] ?? null;
        $jamPulangStr = $sekolah['jam_pulang'] ?? null;

        $Absensi = "BUKAN JAM SEKOLAH";
        $statusAbsensi = "-";
        $bgColor = "gray";

        // Jika jam sekolah belum diset
        if (empty($jamMasukStr) || empty($jamPulangStr)) {
            $statusAbsensi = "Data jam tidak lengkap";
            return; // langsung keluar
        }

        $today = date('Y-m-d');
        $nowTs = time();

        // Jam masuk
        $jamMasukTs = strtotime("$today $jamMasukStr");
        $jamMasukPlus1Ts = strtotime("$today $jamMasukStr +1 hour");

        // Jam pulang
        $jamPulangTs = strtotime("$today $jamPulangStr");
        $jamPulangPlus1Ts = strtotime("$today $jamPulangStr +1 hour");

        // CASE 1: Jam pulang di hari yang sama (normal)
        if ($jamMasukTs < $jamPulangTs) {

            if ($nowTs >= $jamMasukTs && $nowTs <= $jamMasukPlus1Ts) {
                // MASUK TEPAT WAKTU
                $Absensi = "MASUK";
                $statusAbsensi = "Tepat Waktu";
                $bgColor = "rgb(124, 249, 124)";
            } elseif ($nowTs > $jamMasukPlus1Ts && $nowTs < $jamPulangTs) {
                // MASUK TERLAMBAT
                $Absensi = "MASUK (TERLAMBAT)";
                $statusAbsensi = "Terlambat";
                $bgColor = "red";
            } elseif ($nowTs >= $jamPulangTs && $nowTs <= $jamPulangPlus1Ts) {
                // PULANG TEPAT WAKTU
                $Absensi = "PULANG";
                $statusAbsensi = "Tepat Waktu";
                $bgColor = "orange";
            } elseif ($nowTs > $jamPulangPlus1Ts) {
                // PULANG TERLAMBAT
                $Absensi = "PULANG";
                $statusAbsensi = "Pulang Terlambat";
                $bgColor = "red";
            }

            // CASE 2: Jam pulang lewat tengah malam (contoh 22:00 → 02:00)
        } else {

            $endOfDayTs = strtotime("$today 23:59:59");
            $nextDay = date('Y-m-d', strtotime('+1 day'));
            $jamPulangNextTs = strtotime("$nextDay $jamPulangStr");
            $jamPulangPlus1NextTs = strtotime("$nextDay $jamPulangStr +1 hour");

            // MASUK → masih sebelum jam 23:59
            if ($nowTs >= $jamMasukTs && $nowTs <= $endOfDayTs) {

                if ($nowTs <= $jamMasukPlus1Ts) {
                    $Absensi = "MASUK";
                    $statusAbsensi = "Tepat Waktu";
                    $bgColor = "rgb(124, 249, 124)";
                } else {
                    $Absensi = "MASUK (TERLAMBAT)";
                    $statusAbsensi = "Terlambat";
                    $bgColor = "red";
                }

                // PULANG → setelah jam 00:00
            } elseif ($nowTs >= strtotime("$today 00:00:00") && $nowTs <= $jamPulangNextTs) {

                if ($nowTs <= $jamPulangPlus1NextTs) {
                    $Absensi = "PULANG";
                    $statusAbsensi = "Tepat Waktu";
                    $bgColor = "orange";
                } else {
                    $Absensi = "PULANG";
                    $statusAbsensi = "Pulang Terlambat";
                    $bgColor = "red";
                }
            } else {
                $Absensi = "BUKAN JAM SEKOLAH";
                $statusAbsensi = "-";
                $bgColor = "gray";
            }
        }





        $dataSiswa = [];
        $dataGuru = [];
        $error = null;
        $success = null;
        $isGuru = false;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if ($Absensi == "BUKAN JAM SEKOLAH") {
                $_SESSION['error'] = "Bukan jam sekolah. Absensi tidak dapat dilakukan.";
                header("Location: index.php");
                exit();
            }

            $input = $_POST['nisn'] ?? '';
            if ($input == '') {
                $_SESSION['error'] = "NISN/NIP tidak boleh kosong.";
                header("Location: index.php");
                exit();
            }

            // Check if input is guru (NIP > 17 digits) or siswa (NISN <= 17 digits)
            $isGuru = strlen($input) > 17;

            if ($isGuru) {
                // Guru attendance
                $dataGuru = $this->guruModel->getByNip($input);
                if (empty($dataGuru)) {
                    $_SESSION['error'] = "Guru dengan NIP tersebut tidak ditemukan.";
                    header("Location: index.php");
                    exit();
                }
            } else {
                // Siswa attendance
                $dataSiswa = $this->siswaModel->getOneByNisn($input);
                if (empty($dataSiswa)) {
                    $_SESSION['error'] = "Siswa tidak ditemukan.";
                    header("Location: index.php");
                    exit();
                }
            }

            if ($isGuru) {
                // GURU ATTENDANCE LOGIC
                $nip = $dataGuru['nip'];

                // Mode absensi: PULANG
                if ($Absensi == "PULANG") {
                    // Pastikan sudah absen masuk sebelumnya
                    if (!$this->absensiGuruModel->cekAbsensiHariIni($nip, $tanggalHariIni)) {
                        $_SESSION['error'] = "Guru belum melakukan absensi masuk hari ini.";
                        $_SESSION['dataGuru'] = $dataGuru;
                        header("Location: index.php");
                        exit();
                    }

                    // Cek apakah sudah absen pulang
                    if ($this->absensiGuruModel->cekSudahPulang($nip, $tanggalHariIni)) {
                        $_SESSION['error'] = "Guru sudah melakukan absensi pulang hari ini.";
                        $_SESSION['dataGuru'] = $dataGuru;
                        header("Location: index.php");
                        exit();
                    }

                    // Simpan jam pulang
                    $jamPulang = date('Y-m-d H:i:s');
                    if ($this->absensiGuruModel->updatePulang($nip, $tanggalHariIni, $jamPulang)) {
                        $_SESSION['success'] = "Absensi pulang berhasil dicatat!";
                    } else {
                        $_SESSION['error'] = "Gagal mencatat absensi pulang.";
                    }

                    $_SESSION['dataGuru'] = $dataGuru;
                    header("Location: index.php");
                    exit();
                } else {
                    // Mode absensi: MASUK
                    if ($this->absensiGuruModel->cekAbsensiHariIni($nip, $tanggalHariIni)) {
                        $_SESSION['error'] = "Guru sudah melakukan absensi masuk hari ini.";
                        $_SESSION['dataGuru'] = $dataGuru;
                        header("Location: index.php");
                        exit();
                    }

                    $statusKehadiran = "Hadir";
                    $time = date('Y-m-d H:i:s');

                    if ($this->absensiGuruModel->insertAbsensi($nip, $tanggalHariIni, $statusKehadiran, $statusAbsensi, $time)) {
                        $waktuSekarang  = date('H:i:s');
                        $_SESSION['success'] = "Absensi masuk berhasil dicatat!";
                    } else {
                        $_SESSION['error'] = "Gagal mencatat absensi masuk.";
                    }

                    $_SESSION['dataGuru'] = $dataGuru;
                    header("Location: index.php");
                    exit();
                }
            } else {
                // SISWA ATTENDANCE LOGIC
                $nisn = $dataSiswa['nisn'];

                // Mode absensi: PULANG
                if ($Absensi == "PULANG") {

                    // Pastikan sudah absen masuk sebelumnya
                    if (!$this->absensiModel->cekAbsensiHariIni($nisn, $tanggalHariIni)) {
                        $_SESSION['error'] = "Siswa belum melakukan absensi masuk hari ini.";
                        $_SESSION['dataSiswa'] = $dataSiswa;
                        header("Location: index.php");
                        exit();
                    }

                    // Cek apakah sudah absen pulang
                    if ($this->absensiModel->cekSudahPulang($nisn, $tanggalHariIni)) {
                        $_SESSION['error'] = "Siswa sudah melakukan absensi pulang hari ini.";
                        $_SESSION['dataSiswa'] = $dataSiswa;
                        header("Location: index.php");
                        exit();
                    }

                    // Simpan jam pulang
                    $jamPulang = date('Y-m-d H:i:s');
                    if ($this->absensiModel->updatePulang($nisn, $tanggalHariIni, $jamPulang)) {
                        $_SESSION['success'] = "Absensi pulang berhasil dicatat!";
                    } else {
                        $_SESSION['error'] = "Gagal mencatat absensi pulang.";
                    }

                    $_SESSION['dataSiswa'] = $dataSiswa;
                    header("Location: index.php");
                    exit();
                } else {
                    // Mode absensi: MASUK
                    if ($this->absensiModel->cekAbsensiHariIni($nisn, $tanggalHariIni)) {
                        $_SESSION['error'] = "Siswa sudah melakukan absensi masuk hari ini.";
                        $_SESSION['dataSiswa'] = $dataSiswa;
                        header("Location: index.php");
                        exit();
                    }

                    $statusKehadiran = "Hadir";
                    $time = date('Y-m-d H:i:s');

                    if ($this->absensiModel->insertAbsensi($nisn, $tanggalHariIni, $statusKehadiran, $statusAbsensi, $time)) {
                        $waktuSekarang  = date('H:i:s');
                        $_SESSION['success'] = "Absensi masuk berhasil dicatat!";
                    } else {
                        $_SESSION['error'] = "Gagal mencatat absensi masuk.";
                    }

                    $_SESSION['dataSiswa'] = $dataSiswa;
                    header("Location: index.php");
                    exit();
                }
            }
        }

        // Ambil data session jika ada
        if (isset($_SESSION['dataSiswa'])) {
            $dataSiswa = $_SESSION['dataSiswa'];
            $isGuru = false;
            unset($_SESSION['dataSiswa']);
        }

        if (isset($_SESSION['dataGuru'])) {
            $dataGuru = $_SESSION['dataGuru'];
            $isGuru = true;
            unset($_SESSION['dataGuru']);
        }

        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
            $success = $_SESSION['success'];
            unset($_SESSION['success']);
        }

        require_once __DIR__ . '/../View/layout.php';
    }
}

<?php
namespace App\Model;

use App\Model\Database;
use PDO;

class AbsensiModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    // Ambil semua data absensi
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM absensi ORDER BY tanggal DESC, time DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update status dan keterangan absensi
    public function update($id_absensi, $status, $keterangan) {
        $stmt = $this->conn->prepare("UPDATE absensi SET status = :status, keterangan = :keterangan WHERE id_absensi = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':keterangan', $keterangan);
        $stmt->bindParam(':id', $id_absensi);
        return $stmt->execute();
    }

    public function getByDateAndClass($tanggal, $kelas) {
        $sql = "SELECT 
                    a.id_absensi,
                    a.nisn,
                    s.nama,
                    s.foto,
                    k.nama_kelas,
                    a.time,
                    a.status,
                    a.keterangan,
                    a.pulang
                FROM absensi a
                JOIN siswa s ON a.nisn = s.nisn
                JOIN kelas k ON s.kelas = k.id_kelas
                WHERE a.tanggal = :tanggal AND k.id_kelas = :kelas
                ORDER BY s.nama ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->bindParam(':kelas', $kelas);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByDate($tanggal) {
        $sql = "SELECT 
                    a.id_absensi,
                    a.nisn,
                    s.nama,
                    s.foto,
                    k.nama_kelas,
                    a.time,
                    a.status,
                    a.keterangan,
                    a.pulang
                FROM absensi a
                JOIN siswa s ON a.nisn = s.nisn
                JOIN kelas k ON s.kelas = k.id_kelas
                WHERE a.tanggal = :tanggal
                ORDER BY k.nama_kelas, s.nama";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByClass($kelas) {
        $sql = "SELECT 
                    a.id_absensi,
                    a.nisn,
                    s.nama,
                    s.foto,
                    k.nama_kelas,
                    a.time,
                    a.status,
                    a.keterangan,
                    a.pulang
                FROM absensi a
                JOIN siswa s ON a.nisn = s.nisn
                JOIN kelas k ON s.kelas = k.id_kelas
                WHERE k.id_kelas = :kelas
                ORDER BY a.tanggal DESC, s.nama";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':kelas', $kelas);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getToday() {
        $sql = "SELECT 
                    a.id_absensi,
                    a.nisn,
                    s.nama,
                    s.foto,
                    k.nama_kelas,
                    a.time,
                    a.status,
                    a.keterangan,
                    a.pulang
                FROM absensi a
                JOIN siswa s ON a.nisn = s.nisn
                JOIN kelas k ON s.kelas = k.id_kelas
                WHERE a.tanggal = CURDATE()
                ORDER BY k.nama_kelas, s.nama";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Ambil data absensi berdasarkan bulan dan kelas untuk rekap
    public function getByMonthAndClass($bulan, $kelas) {
        $sql = "SELECT 
                    a.id_absensi,
                    a.nisn,
                    a.tanggal,
                    s.nama,
                    s.foto,
                    k.nama_kelas,
                    a.time,
                    a.status,
                    a.keterangan,
                    a.pulang
                FROM absensi a
                JOIN siswa s ON a.nisn = s.nisn
                JOIN kelas k ON s.kelas = k.id_kelas
                WHERE DATE_FORMAT(a.tanggal, '%Y-%m') = :bulan AND k.id_kelas = :kelas
                ORDER BY a.tanggal ASC, s.nama ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':bulan', $bulan);
        $stmt->bindParam(':kelas', $kelas);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Ambil data absensi berdasarkan bulan saja (semua kelas)
    public function getByMonth($bulan) {
        $sql = "SELECT 
                    a.id_absensi,
                    a.nisn,
                    a.tanggal,
                    s.nama,
                    s.foto,
                    k.nama_kelas,
                    a.time,
                    a.status,
                    a.keterangan,
                    a.pulang
                FROM absensi a
                JOIN siswa s ON a.nisn = s.nisn
                JOIN kelas k ON s.kelas = k.id_kelas
                WHERE DATE_FORMAT(a.tanggal, '%Y-%m') = :bulan
                ORDER BY k.nama_kelas ASC, a.tanggal ASC, s.nama ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':bulan', $bulan);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
}
?>

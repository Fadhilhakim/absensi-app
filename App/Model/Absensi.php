<?php
namespace App\Model;

use PDO;

class Absensi {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    // Mengecek apakah siswa sudah absen masuk pada hari ini
    public function cekAbsensiHariIni($nisn, $tanggal)
    {
        $sql = "SELECT 1 FROM absensi 
                WHERE nisn = :nisn 
                AND tanggal = :tanggal 
                AND time IS NOT NULL 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nisn', $nisn);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->execute();

        // Jika ada hasil (rowCount > 0), berarti siswa SUDAH ABSEN
        return $stmt->rowCount() > 0;
    }



    // Mengecek apakah siswa sudah mengisi jam pulang
    public function cekSudahPulang($nisn, $tanggal) {
        $sql = "SELECT 1 FROM absensi 
                WHERE nisn = :nisn 
                AND tanggal = :tanggal 
                AND pulang IS NOT NULL 
                AND pulang <> '' 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nisn', $nisn);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Update field jam pulang
    public function updatePulang($nisn, $tanggal, $jamPulang) {
        $sql = "UPDATE absensi 
                SET pulang = :pulang 
                WHERE nisn = :nisn 
                AND tanggal = :tanggal 
                AND pulang IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':pulang', $jamPulang);
        $stmt->bindParam(':nisn', $nisn);
        $stmt->bindParam(':tanggal', $tanggal);
        return $stmt->execute();
    }
    public function insertAbsensi($nisn, $tanggal, $status, $keterangan, $time) {
        $stmt = $this->conn->prepare("
            UPDATE absensi 
            SET status = :status, 
                keterangan = :keterangan, 
                time = :time 
            WHERE nisn = :nisn 
            AND tanggal = :tanggal
        ");

        $stmt->bindParam(':nisn', $nisn);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':keterangan', $keterangan);
        $stmt->bindParam(':time', $time);
        return $stmt->execute();

    }

    public function countByDate($tanggal) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM absensi WHERE tanggal = :tanggal");
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Insert data absensi default
    public function insertDefault($data) {
        $sql = "INSERT INTO absensi (nisn, tanggal) VALUES (:nisn, :tanggal)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nisn', $data['nisn']);
        $stmt->bindParam(':tanggal', $data['tanggal']);
        return $stmt->execute();
    }

    // Ambil absensi berdasarkan tanggal
    public function getByDate($tanggal) {
        $sql = "SELECT * FROM absensi WHERE tanggal = :tanggal ORDER BY nisn";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

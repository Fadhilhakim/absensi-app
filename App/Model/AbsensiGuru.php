<?php
namespace App\Model;

use PDO;
use App\Model\Database;

class AbsensiGuru {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function countByDate($tanggal) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM absensi_guru WHERE tanggal = ?");
        $stmt->execute([$tanggal]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['total'] : 0;
    }

    public function insertDefault($data) {
        $query = "INSERT INTO absensi_guru (nip, tanggal, time, status, keterangan, pulang)
                  VALUES (:nip, :tanggal, :time, :status, :keterangan, :pulang)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':nip', $data['nip']);
        $stmt->bindValue(':tanggal', $data['tanggal']);
        $stmt->bindValue(':time', $data['time']);
        $stmt->bindValue(':status', $data['status']);
        $stmt->bindValue(':keterangan', $data['keterangan']);
        $stmt->bindValue(':pulang', $data['pulang']);

        return $stmt->execute();
    }

    public function getByDate($tanggal) {
        $sql = "SELECT 
                    ag.id_absensi,
                    ag.nip,
                    g.nama,
                    g.foto,
                    g.jabatan,
                    ag.time,
                    ag.status,
                    ag.keterangan,
                    ag.pulang,
                    ag.tanggal
                FROM absensi_guru ag
                JOIN guru g ON ag.nip = g.nip
                WHERE ag.tanggal = :tanggal
                ORDER BY g.nama ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT 
                    ag.id_absensi,
                    ag.nip,
                    g.nama,
                    g.foto,
                    g.jabatan,
                    ag.time,
                    ag.status,
                    ag.keterangan,
                    ag.pulang,
                    ag.tanggal
                FROM absensi_guru ag
                JOIN guru g ON ag.nip = g.nip
                ORDER BY ag.tanggal DESC, g.nama ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getToday() {
        $sql = "SELECT 
                    ag.id_absensi,
                    ag.nip,
                    g.nama,
                    g.foto,
                    g.jabatan,
                    ag.time,
                    ag.status,
                    ag.keterangan,
                    ag.pulang,
                    ag.tanggal
                FROM absensi_guru ag
                JOIN guru g ON ag.nip = g.nip
                WHERE ag.tanggal = CURDATE()
                ORDER BY g.nama ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByMonth($bulan) {
        $sql = "SELECT 
                    ag.id_absensi,
                    ag.nip,
                    g.nama,
                    g.foto,
                    g.jabatan,
                    ag.time,
                    ag.status,
                    ag.keterangan,
                    ag.pulang,
                    ag.tanggal
                FROM absensi_guru ag
                JOIN guru g ON ag.nip = g.nip
                WHERE DATE_FORMAT(ag.tanggal, '%Y-%m') = :bulan
                ORDER BY ag.tanggal ASC, g.nama ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':bulan', $bulan);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id_absensi, $status, $keterangan) {
        $stmt = $this->db->prepare("UPDATE absensi_guru SET status = :status, keterangan = :keterangan WHERE id_absensi = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':keterangan', $keterangan);
        $stmt->bindParam(':id', $id_absensi);
        return $stmt->execute();
    }

    public function updateWithTime($id_absensi, $status, $keterangan, $time = null, $pulang = null) {
        $query = "UPDATE absensi_guru SET status = :status, keterangan = :keterangan";
        if ($time !== null) {
            $query .= ", time = :time";
        }
        if ($pulang !== null) {
            $query .= ", pulang = :pulang";
        }
        $query .= " WHERE id_absensi = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':keterangan', $keterangan);
        $stmt->bindParam(':id', $id_absensi);
        if ($time !== null) {
            $stmt->bindParam(':time', $time);
        }
        if ($pulang !== null) {
            $stmt->bindParam(':pulang', $pulang);
        }
        return $stmt->execute();
    }

    // Cek apakah guru sudah absen hari ini
    public function cekAbsensiHariIni($nip, $tanggal) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM absensi_guru WHERE nip = ? AND tanggal = ? AND time IS NOT NULL");
        $stmt->execute([$nip, $tanggal]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }

    // Cek apakah guru sudah absen pulang
    public function cekSudahPulang($nip, $tanggal) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM absensi_guru WHERE nip = ? AND tanggal = ? AND pulang IS NOT NULL");
        $stmt->execute([$nip, $tanggal]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }

    // Insert absensi untuk guru
    public function insertAbsensi($nip, $tanggal, $status, $keterangan, $time) {
        $stmt = $this->db->prepare("UPDATE absensi_guru SET status = ?, keterangan = ?, time = ? WHERE nip = ? AND tanggal = ?");
        return $stmt->execute([$status, $keterangan, $time, $nip, $tanggal]);
    }

    // Update jam pulang
    public function updatePulang($nip, $tanggal, $pulang) {
        $stmt = $this->db->prepare("UPDATE absensi_guru SET pulang = ? WHERE nip = ? AND tanggal = ?");
        return $stmt->execute([$pulang, $nip, $tanggal]);
    }
}

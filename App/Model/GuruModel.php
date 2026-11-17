<?php
namespace App\Model;

use App\Model\Database;
use PDO;

class GuruModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    // Ambil semua data guru
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT nip, nama, jabatan, foto FROM guru ORDER BY nama ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cek apakah NIP sudah ada
    public function exists($nip) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM guru WHERE nip = ?");
        $stmt->execute([$nip]);
        return $stmt->fetchColumn() > 0;
    }

    // Tambah data guru baru
    public function insert($data) {
        $sql = "INSERT INTO guru (nip, nama, jabatan, foto)
                VALUES (:nip, :nama, :jabatan, :foto)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':nip' => $data['nip'],
            ':nama' => $data['nama'],
            ':jabatan' => $data['jabatan'],
            ':foto' => $data['foto'] ?? null
        ]);
    }

    // Ambil data guru berdasarkan NIP
    public function getByNip($nip) {
        $stmt = $this->conn->prepare("SELECT * FROM guru WHERE nip = ?");
        $stmt->execute([$nip]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getKepsek() {
        $stmt = $this->conn->prepare("SELECT * FROM guru WHERE jabatan = 'Kepala Sekolah'");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update data guru
    public function update($nip, $data) {
        $sql = "UPDATE guru 
                SET nama = :nama, 
                    jabatan = :jabatan, 
                    foto = :foto 
                WHERE nip = :nip";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':nama' => $data['nama'],
            ':jabatan' => $data['jabatan'],
            ':foto' => $data['foto'] ?? $data['old_photo'],
            ':nip' => $nip
        ]);
    }

    // Hapus data guru
    public function delete($nip) {
        $stmt = $this->conn->prepare("DELETE FROM guru WHERE nip = ?");
        $stmt->execute([$nip]);
    }
}
?>

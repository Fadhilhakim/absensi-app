<?php
namespace App\Model;

use PDO;
use App\Model\Database;

class KelasModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("
            SELECT kelas.*, guru.nama AS nama_wali 
            FROM kelas 
            LEFT JOIN guru ON kelas.wali_kelas = guru.nip
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id_kelas) {
        $stmt = $this->conn->prepare("SELECT * FROM kelas WHERE id_kelas = ?");
        $stmt->execute([$id_kelas]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $stmt = $this->conn->prepare("INSERT INTO kelas (nama_kelas, wali_kelas) VALUES (?, ?)");
        return $stmt->execute([$data['nama_kelas'], $data['wali_kelas']]);
    }

    public function update($id_kelas, $data) {
        $stmt = $this->conn->prepare("UPDATE kelas SET nama_kelas = ?, wali_kelas = ? WHERE id_kelas = ?");
        return $stmt->execute([$data['nama_kelas'], $data['wali_kelas'], $id_kelas]);
    }

    public function delete($id_kelas) {
        $stmt = $this->conn->prepare("DELETE FROM kelas WHERE id_kelas = ?");
        return $stmt->execute([$id_kelas]);
    }

    // Alumni: ambil daftar kelas alumni beserta jumlah alumninya
    public function getPastClasses() {
        $sql = "SELECT ak.*, (
                    SELECT COUNT(*) FROM alumni_siswa asw WHERE asw.id_alumni_kelas = ak.id_alumni_kelas
                ) AS jumlah_alumni
                FROM alumni_kelas ak
                ORDER BY ak.tanggal_dipindah DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Alumni: ambil siswa alumni berdasarkan id_alumni_kelas
    public function getAlumniStudents($id_alumni_kelas) {
        $stmt = $this->conn->prepare("SELECT * FROM alumni_siswa WHERE id_alumni_kelas = ? ORDER BY nama ASC");
        $stmt->execute([$id_alumni_kelas]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Alumni: pindahkan seluruh siswa dalam kelas menjadi alumni (transaction-safe)
    public function moveClassToAlumni(int $id_kelas, int $tahun_lulus, bool $deleteKelas = true) {
        try {
            $this->conn->beginTransaction();

            // Kunci baris kelas yang akan dipindah
            $stmt = $this->conn->prepare("SELECT * FROM kelas WHERE id_kelas = ? FOR UPDATE");
            $stmt->execute([$id_kelas]);
            $kelas = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$kelas) {
                throw new \RuntimeException('Kelas tidak ditemukan');
            }

            // Buat entri alumni_kelas
            $stmt = $this->conn->prepare("INSERT INTO alumni_kelas (id_kelas_asal, nama_kelas, tahun_lulus, tanggal_dipindah) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$id_kelas, $kelas['nama_kelas'], $tahun_lulus]);
            $idAlumniKelas = (int)$this->conn->lastInsertId();

            // Pindahkan siswa kelas tsb ke alumni_siswa
            $insertSql = "INSERT INTO alumni_siswa (nisn, nama, id_alumni_kelas, tanggal_lahir, alamat, nama_ibu, no_hp_orang_tua, foto, tanggal_dipindah)
                          SELECT s.nisn, s.nama, ?, s.tanggal_lahir, s.alamat, s.nama_ibu, s.no_hp_orang_tua, s.foto, NOW()
                          FROM siswa s WHERE s.kelas = ?";
            $stmt = $this->conn->prepare($insertSql);
            $stmt->execute([$idAlumniKelas, $id_kelas]);

            // Hapus siswa aktif pada kelas tersebut
            $stmt = $this->conn->prepare("DELETE FROM siswa WHERE kelas = ?");
            $stmt->execute([$id_kelas]);

            // Opsional: hapus entri kelas aktif
            if ($deleteKelas) {
                $stmt = $this->conn->prepare("DELETE FROM kelas WHERE id_kelas = ?");
                $stmt->execute([$id_kelas]);
            }

            $this->conn->commit();
            return $idAlumniKelas;
        } catch (\Throwable $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}

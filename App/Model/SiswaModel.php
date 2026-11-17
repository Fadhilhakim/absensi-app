<?php
namespace App\Model;

use App\Model\Database;
use PDO;

class SiswaModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    // Ambil semua data siswa
    public function getAll() {
        $sql = "SELECT 
                    s.nisn, 
                    s.nama, 
                    k.nama_kelas AS nama_kelas, 
                    s.kelas,
                    s.foto
                FROM siswa s
                LEFT JOIN kelas k ON s.kelas = k.id_kelas";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exists($nisn) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM siswa WHERE nisn = ?");
        $stmt->execute([$nisn]);
        return $stmt->fetchColumn() > 0;
    }

    public function insert($data) {
        $sql = "INSERT INTO siswa (nisn, nama, kelas, tanggal_lahir, alamat, nama_ibu, no_hp_orang_tua, foto)
                VALUES (:nisn, :nama, :kelas, :tanggal_lahir, :alamat, :nama_ibu, :no_hp_orang_tua, :foto)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':nisn' => $data['nisn'],
            ':nama' => $data['nama'],
            ':kelas' => $data['id_kelas'],
            ':tanggal_lahir' => $data['tanggal_lahir'],
            ':alamat' => $data['alamat'],
            ':nama_ibu' => $data['nama_ibu'],
            ':no_hp_orang_tua' => $data['no_hp_orang_tua'],
            ':foto' => $data['foto'],
        ]);
    }


    public function getByNisn($nisn) {
        $stmt = $this->conn->prepare("SELECT * FROM siswa WHERE nisn = ?");
        $stmt->execute([$nisn]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOneByNISN($nisn) {
        $sql = "SELECT 
                    s.nisn, 
                    s.nama, 
                    k.nama_kelas AS nama_kelas, 
                    s.kelas,
                    S.tanggal_lahir,
                    s.alamat,
                    s.nama_ibu,
                    s.no_hp_orang_tua,
                    s.foto
                FROM siswa s
                LEFT JOIN kelas k ON s.kelas = k.id_kelas
                WHERE nisn = :nisn";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nisn', $nisn);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($nisn, $data) {
        $sql = "UPDATE siswa SET nama = ?, kelas = ?, tanggal_lahir = ?, alamat = ?, nama_ibu = ?, no_hp_orang_tua = ?, foto = ? WHERE nisn = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['nama'],
            $data['id_kelas'],
            $data['tanggal_lahir'],
            $data['alamat'],
            $data['nama_ibu'],
            $data['no_hp_orang_tua'],
            $data['foto'] ?? $data['old_photo'],
            $nisn
        ]);
    }


    public function delete($nisn) {
        $stmt = $this->conn->prepare("DELETE FROM siswa WHERE nisn = ?");
        $stmt->execute([$nisn]);
    }

    public function getByKelas($id_kelas) {
        $sql = "SELECT 
                    s.nisn, 
                    s.nama, 
                    k.nama_kelas AS nama_kelas, 
                    s.kelas,
                    s.tanggal_lahir,
                    s.alamat,
                    s.foto
                FROM siswa s
                LEFT JOIN kelas k ON s.kelas = k.id_kelas
                WHERE s.kelas = :id_kelas
                ORDER BY s.nisn DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_kelas', $id_kelas);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Pagination helpers
    public function countAll() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM siswa");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getPaged(int $limit, int $offset) {
        $sql = "SELECT 
                    s.nisn,
                    s.nama,
                    k.nama_kelas AS nama_kelas,
                    s.kelas,
                    s.foto
                FROM siswa s
                LEFT JOIN kelas k ON s.kelas = k.id_kelas
                ORDER BY s.nisn DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countByQuery(string $q) {
        $like = '%' . $q . '%';
        $sql = "SELECT COUNT(*)
                FROM siswa s
                LEFT JOIN kelas k ON s.kelas = k.id_kelas
                WHERE s.nisn LIKE :q OR s.nama LIKE :q OR k.nama_kelas LIKE :q";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':q', $like, PDO::PARAM_STR);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getPagedByQuery(string $q, int $limit, int $offset) {
        $like = '%' . $q . '%';
        $sql = "SELECT 
                    s.nisn,
                    s.nama,
                    k.nama_kelas AS nama_kelas,
                    s.kelas,
                    s.foto
                FROM siswa s
                LEFT JOIN kelas k ON s.kelas = k.id_kelas
                WHERE s.nisn LIKE :q OR s.nama LIKE :q OR k.nama_kelas LIKE :q
                ORDER BY s.nisn DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':q', $like, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

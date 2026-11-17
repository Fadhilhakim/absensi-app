<?php
namespace App\Model;

use PDO;
use App\Model\Database;

class SekolahModel {
    private $conn;
    private $table = 'sekolah';

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function get() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function exists() {
        $stmt = $this->conn->prepare("SELECT 1 FROM {$this->table} LIMIT 1");
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    public function save(array $data) {
        $this->conn->beginTransaction();
        try {
            $existing = $this->get();

            if ($existing) {
                $id = (int)$existing['id'];
                $sql = "UPDATE {$this->table}
                        SET nama_sekolah = :nama_sekolah,
                            alamat = :alamat,
                            telp = :telp,
                            npsn = :npsn,
                            jam_masuk = :jam_masuk,
                            jam_pulang = :jam_pulang,
                            tata_tertib = :tata_tertib,
                            logo = :logo
                        WHERE id = :id";

                $stmt = $this->conn->prepare($sql);
                $params = [
                    ':id' => $id,
                    ':nama_sekolah' => $data['nama_sekolah'],
                    ':alamat' => $data['alamat'],
                    ':telp' => $data['telp'],
                    ':npsn' => $data['npsn'],
                    ':jam_masuk' => $data['jam_masuk'],
                    ':jam_pulang' => $data['jam_pulang'],
                    ':tata_tertib' => $data['tata_tertib'],
                    ':logo' => $data['logo']
                ];
                $stmt->execute($params);

            } else {
                $stmt = $this->conn->prepare("
                    INSERT INTO {$this->table}
                    (nama_sekolah, alamat, telp, npsn, jam_masuk, jam_pulang, tata_tertib, logo)
                    VALUES (:nama_sekolah, :alamat, :telp, :npsn, :jam_masuk, :jam_pulang, :tata_tertib, :logo)
                ");
                $stmt->execute([
                    ':nama_sekolah' => $data['nama_sekolah'],
                    ':alamat' => $data['alamat'],
                    ':telp' => $data['telp'],
                    ':npsn' => $data['npsn'],
                    ':jam_masuk' => $data['jam_masuk'],
                    ':jam_pulang' => $data['jam_pulang'],
                    ':tata_tertib' => $data['tata_tertib'],
                    ':logo' => $data['logo']
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }


    public function updateById(int $id, array $data) {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET nama_sekolah = :nama_sekolah,
                alamat = :alamat,
                telp = :telp,
                npsn = :npsn,
                jam_masuk = :jam_masuk,
                jam_pulang = :jam_pulang,
                tata_tertib = :tata_tertib
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':nama_sekolah' => $data['nama_sekolah'] ?? null,
            ':alamat' => $data['alamat'] ?? null,
            ':telp' => $data['telp'] ?? null,
            ':npsn' => $data['npsn'] ?? null,
            ':jam_masuk' => $data['jam_masuk'] ?? null,
            ':jam_pulang' => $data['jam_pulang'] ?? null,
            ':tata_tertib' => $data['tata_tertib'] ?? null,
        ]);
    }
}

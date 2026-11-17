<?php
namespace App\Controller;

use App\Model\KelasModel;
use App\Model\GuruModel;

class KelolaKelasController {
    public function index() {
        $kelasModel = new KelasModel();
        $guruModel = new GuruModel();

        $action = $_GET['action'] ?? null;
        $id_kelas = $_GET['id_kelas'] ?? null;
        $editData = null;

        if ($action === 'edit' && $id_kelas) {
            $editData = $kelasModel->getById($id_kelas);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Aksi khusus: pindahkan ke alumni
            if (isset($_POST['aksi']) && $_POST['aksi'] === 'pindah_alumni' && isset($_POST['tahun_lulus']) && isset($_POST['id_kelas'])) {
                try {
                    $deleteKelas = isset($_POST['hapus_kelas']) && $_POST['hapus_kelas'] === '1';
                    $kelasModel->moveClassToAlumni((int)$_POST['id_kelas'], (int)$_POST['tahun_lulus'], $deleteKelas);
                    $_SESSION['success'] = "Kelas dan siswa berhasil dipindahkan ke alumni.";
                } catch (\Throwable $e) {
                    $_SESSION['error'] = "Gagal memindahkan ke alumni: " . $e->getMessage();
                }
                header("Location: ?page=kelola-kelas");
                exit;
            }

            $data = [
                'nama_kelas' => $_POST['nama_kelas'],
                'wali_kelas' => $_POST['wali_kelas']
            ];

            if ($action === 'edit' && $id_kelas) {
                $kelasModel->update($id_kelas, $data);
                $_SESSION['success'] = "Data berhasil diperbaharui";
            } else {
                $kelasModel->insert($data);
                $_SESSION['success'] = "Data sekolah berhasil ditambahkan!";
            }

            header("Location: ?page=kelola-kelas");
            exit;
        }

        if ($action === 'delete' && $id_kelas) {
            $kelasModel->delete($id_kelas);
            $_SESSION['error'] = "Data berhasil dihapus!";
            header("Location: ?page=kelola-kelas");
            exit;
        }

        $data = [
            'kelas' => $kelasModel->getAll(),
            'guru' => $guruModel->getAll(),
            'editData' => $editData,
            'alumniKelas' => $kelasModel->getPastClasses()
        ];

        ob_start();
        require 'App/View/dashboard/kelola_kelas.php';
        $content = ob_get_clean();

        require 'App/View/dashboard.php';
    }
}

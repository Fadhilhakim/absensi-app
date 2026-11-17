<?php
namespace App\Controller;

use App\Model\GuruModel;

class DataGuruController {
    public function index() {

        $guruModel = new GuruModel();

        $action = $_GET['action'] ?? null;
        $nip = $_GET['nip'] ?? null;
        $editData = null;
        
        // Mode edit
        if ($action === 'edit' && $nip) {
            $editData = $guruModel->getByNip($nip);
        }

        // Jika form dikirim (tambah atau edit)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nip' => $_POST['nip'],
                'nama' => $_POST['nama'],
                'jabatan' => $_POST['jabatan'],
                'old_photo' => $_POST['old_photo'] ?? null,
                'foto' => null
            ];

            // Upload foto jika ada
            if (!empty($_FILES['foto']['name'])) {
                $targetDir = 'uploads/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $fileName = basename($_FILES['foto']['name']);
                $targetFilePath = $targetDir . $fileName;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFilePath)) {
                    $data['foto'] = $fileName;
                }
            } else {
                // Gunakan foto lama jika tidak upload baru
                $data['foto'] = $data['old_photo'];
            }

            // Jika sedang edit
            if ($action === 'edit' && $nip) {
                $guruModel->update($nip, $data);
                $_SESSION['success'] = 'Data berhasil diperbaharui!';
            } else {
                // Cek apakah NIP sudah ada
                if ($guruModel->exists($data['nip'])) {
                    $_SESSION['error'] = "NIP {$data['nip']} sudah terdaftar!";
                    header("Location: ?page=data-guru");
                    exit;
                } else {
                    $_SESSION['success'] = 'Data berhasil ditambahkan!';
                    $guruModel->insert($data);
                }
            }

            header("Location: ?page=data-guru");
            exit;
        }

        // Hapus data
        if ($action === 'delete' && $nip) {
            $guruModel->delete($nip);
            header("Location: ?page=data-guru");
            exit;
        }

        $data = [
            'guru' => $guruModel->getAll(),
            'editData' => $editData
        ];

        ob_start();
        require 'App/View/dashboard/data_guru.php';
        $content = ob_get_clean();

        require 'App/View/dashboard.php';
    }
}

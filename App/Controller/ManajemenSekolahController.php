<?php
namespace App\Controller;

use App\Model\SekolahModel;

class ManajemenSekolahController {

    public function index() {
        $sekolahModel = new SekolahModel();
        $sekolah = $sekolahModel->get();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tataTertib = array_filter($_POST['tata_tertib'] ?? []);
            $tata_tertib_text = implode("\n", $tataTertib);

            // Default logo dari input hidden
            $foto = $_POST['old_photo'] ?? '';

            // === HANDLE UPLOAD FILE LOGO ===
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'logo_sekolah/';
                $fileTmp = $_FILES['foto']['tmp_name'];
                $fileExt = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $newFileName = "logo_sekolah.$fileExt";
                $targetFile = $uploadDir . $newFileName;

                // Hapus file lama logo_sekolah.* (kecuali default.png)
                foreach (glob($uploadDir . "logo_sekolah.*") as $oldFile) {
                    if (basename($oldFile) !== 'default.png') {
                        unlink($oldFile);
                    }
                }

                // Pindahkan file yang baru diupload
                if (move_uploaded_file($fileTmp, $targetFile)) {
                    $foto = $newFileName;
                } else {
                    $_SESSION['error'] = "Gagal mengupload logo sekolah.";
                }
            }

            // === SIMPAN DATA KE DATABASE ===
            $data = [
                'nama_sekolah'   => $_POST['nama_sekolah'] ?? '',
                'alamat'         => $_POST['alamat'] ?? '',
                'telp' => $_POST['telp'] ?? '',
                'npsn'           => $_POST['npsn'] ?? '',
                'jam_masuk'      => $_POST['JOS-masuk'] ?? '',
                'jam_pulang'     => $_POST['JOS-pulang'] ?? '',
                'tata_tertib'    => $tata_tertib_text,
                'logo'           => $foto
            ];

            if ($sekolahModel->save($data)) {
                $_SESSION['success'] = "Data sekolah berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Gagal memperbarui data sekolah.";
            }

            header('Location: ?page=manajemen-sekolah');
            exit;
        }

        $tataTertibList = !empty($sekolah['tata_tertib']) ? explode("\n", $sekolah['tata_tertib']) : [];

        ob_start();
        require 'App/View/dashboard/manajemen_sekolah.php';
        $content = ob_get_clean();
        require 'App/View/dashboard.php';
    }
}

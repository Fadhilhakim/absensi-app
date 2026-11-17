<?php
namespace App\Controller;

use App\Model\SiswaModel;
use App\Model\KelasModel;

class DataSiswaController {
    public function index() {
        $siswaModel = new SiswaModel();
        $kelasModel = new KelasModel();

        $action = $_GET['action'] ?? null;
        $nisn = $_GET['nisn'] ?? null;
        $editData = null;

        // Mode edit
        if ($action === 'edit' && $nisn) {
            $editData = $siswaModel->getByNisn($nisn);
        }

        // Proses form kirim
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Bulk CSV upload handling
            if ($action === 'bulk-upload') {
                if (!empty($_FILES['csv']['tmp_name']) && is_uploaded_file($_FILES['csv']['tmp_name'])) {
                    $file = $_FILES['csv']['tmp_name'];
                    $handle = fopen($file, 'r');
                    $inserted = 0;
                    $errors = [];
                    if ($handle !== false) {
                        $header = fgetcsv($handle);
                        if ($header === false) {
                            $_SESSION['error'] = 'File CSV kosong atau tidak valid.';
                            header("Location: ?page=data-siswa");
                            exit;
                        }

                        $headers = array_map(function($h){ return strtolower(trim($h)); }, $header);
                        $rowNum = 1;
                        $kelasList = $kelasModel->getAll();

                        while (($row = fgetcsv($handle)) !== false) {
                            $rowNum++;
                            // skip empty rows
                            if (count(array_filter($row)) === 0) continue;

                            $assoc = [];
                            foreach ($headers as $i => $h) {
                                $assoc[$h] = isset($row[$i]) ? trim($row[$i]) : '';
                            }

                            // Basic validations
                            $nisn = $assoc['nisn'] ?? '';
                            if ($nisn === '') { $errors[] = "Baris {$rowNum}: NISN kosong."; continue; }
                            if (strlen($nisn) > 20) { $errors[] = "Baris {$rowNum}: NISN terlalu panjang (>20)."; continue; }
                            if ($siswaModel->exists($nisn)) { $errors[] = "Baris {$rowNum}: NISN {$nisn} sudah terdaftar."; continue; }

                            // kelas: accept id or name
                            $kelasRaw = $assoc['kelas'] ?? '';
                            $id_kelas = null;
                            if (is_numeric($kelasRaw) && $kelasRaw !== '') {
                                $k = $kelasModel->getById((int)$kelasRaw);
                                if ($k) $id_kelas = (int)$k['id_kelas'];
                            } else {
                                foreach ($kelasList as $kitem) {
                                    if (strtolower(trim($kitem['nama_kelas'])) === strtolower(trim($kelasRaw))) { $id_kelas = (int)$kitem['id_kelas']; break; }
                                }
                            }
                            if ($id_kelas === null) { $errors[] = "Baris {$rowNum}: Kelas tidak valid ('{$kelasRaw}')."; continue; }

                            // tanggal_lahir: try Y-m-d then d/m/Y
                            $tanggal = null;
                            $tglRaw = $assoc['tanggal_lahir'] ?? '';
                            if ($tglRaw !== '') {
                                $d = \DateTime::createFromFormat('Y-m-d', $tglRaw);
                                if (!$d) $d = \DateTime::createFromFormat('d/m/Y', $tglRaw);
                                if ($d) $tanggal = $d->format('Y-m-d');
                                else { $errors[] = "Baris {$rowNum}: Tanggal lahir tidak valid ('{$tglRaw}')."; continue; }
                            }

                            $nohp = $assoc['no_hp_orang_tua'] ?? '';
                            if ($nohp === '' || strlen($nohp) > 15) { $errors[] = "Baris {$rowNum}: No HP orang tua tidak valid atau kosong."; continue; }

                            $dataRow = [
                                'nisn' => $nisn,
                                'nama' => $assoc['nama'] ?? '',
                                'id_kelas' => $id_kelas,
                                'tanggal_lahir' => $tanggal,
                                'alamat' => $assoc['alamat'] ?? '',
                                'nama_ibu' => $assoc['nama_ibu'] ?? '',
                                'no_hp_orang_tua' => $nohp,
                                'foto' => 'default.png'
                            ];

                            try {
                                $siswaModel->insert($dataRow);
                                $inserted++;
                            } catch (\Throwable $e) {
                                $errors[] = "Baris {$rowNum}: Gagal memasukkan data (DB error).";
                            }
                        }

                        fclose($handle);
                        $_SESSION['success'] = "Berhasil mengimpor {$inserted} baris.";
                        if (!empty($errors)) {
                            $_SESSION['error'] = implode('<br>', $errors);
                        }
                    } else {
                        $_SESSION['error'] = 'Tidak dapat membuka file CSV.';
                    }
                } else {
                    $_SESSION['error'] = 'File CSV tidak ditemukan atau tidak diunggah.';
                }

                header("Location: ?page=data-siswa");
                exit;
            }
            $data = [
                'nisn' => $_POST['nisn'],
                'nama' => $_POST['nama'],
                'id_kelas' => $_POST['kelas'],
                'tanggal_lahir' => $_POST['tanggal_lahir'],
                'alamat' => $_POST['alamat'],
                'nama_ibu' => $_POST['nama_ibu'],
                'no_hp_orang_tua' => $_POST['no_hp_orang_tua'],
                'old_photo' => $_POST['old_photo'] ?? null,
                'foto' => null,
            ];

            // Upload foto
            if (!empty($_FILES['foto']['name'])) {
                $targetDir = 'uploads/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = basename($_FILES['foto']['name']);
                $targetFilePath = $targetDir . $fileName;
                move_uploaded_file($_FILES['foto']['tmp_name'], $targetFilePath);
                $data['foto'] = $fileName;
            } else {
                $data['foto'] = $data['old_photo'];
            }

            if ($action === 'edit') {
                $siswaModel->update($data['nisn'], $data);
                $_SESSION['success'] = "Data berhasil diperbaharui!";
            } else {
                if ($siswaModel->exists($data['nisn'])) {
                    $_SESSION['error'] = "NISN {$data['nisn']} sudah terdaftar!";
                    header("Location: ?page=data-siswa");
                    exit;
                } else {
                    $_SESSION['success'] = "Data berhasil ditambahkan!";
                    $siswaModel->insert($data);
                }
            }

            header("Location: ?page=data-siswa");
            exit;
        }

        // Hapus data
        if ($action === 'delete' && $nisn) {
            $siswaModel->delete($nisn);
            header("Location: ?page=data-siswa");
                    $_SESSION['error'] = "Data berhasil Dihapus!";
            exit;
        }

        // Pagination params
        $pageNo = isset($_GET['pageNo']) ? max(1, (int)$_GET['pageNo']) : 1;
        $perPage = 10; // adjust page size as needed
        $offset = ($pageNo - 1) * $perPage;

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';

        $total = 0;
        $siswaPage = [];
        try {
            if ($q !== '') {
                $total = $siswaModel->countByQuery($q);
                $siswaPage = $siswaModel->getPagedByQuery($q, $perPage, $offset);
            } else {
                $total = $siswaModel->countAll();
                $siswaPage = $siswaModel->getPaged($perPage, $offset);
            }
        } catch (\Throwable $e) {}

        $totalPages = max(1, (int)ceil($total / $perPage));

        $data = [
            'siswa' => $siswaPage,
            'kelas' => $kelasModel->getAll(),
            'editData' => $editData,
            'pageNo' => $pageNo,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages,
            'q' => $q,
        ];

        ob_start();
        require 'App/View/dashboard/data_siswa.php';
        $content = ob_get_clean();

        require 'App/View/dashboard.php';
    }
}

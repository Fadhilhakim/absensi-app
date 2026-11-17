<?php
namespace App\Controller;
use App\Model\AbsensiModel;

class AjaxAbsensiController {
    public function update() {
        $absensi = new AbsensiModel();

        $id = $_POST['id'];
        $status = $_POST['status'];
        $keterangan = $_POST['keterangan'];

        $absensi->updateAbsensi($id, $status, $keterangan);

        echo json_encode(['success' => true]);
    }
}

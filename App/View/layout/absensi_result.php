<div class="container">
    
    <?php if (isset($_SESSION['errors'])){ require_once 'error.php'; } ?>

    <?php if (empty($dataSiswa) && !isset($_SESSION['errors'])): ?>

        <div id="clock">
            <p style="position:relative; padding:10px; text-align: center; font-size: 100px; " id="date-time"></p>
        </div>


    <?php  else: ?>
            <div class="row">

                <div class="col foto">
                    <img class="img" style="width: 300px"
                        src="uploads/<?php echo isset($dataSiswa["foto"]) ? htmlspecialchars($dataSiswa["foto"]) : 'default.jpg'; ?>"
                        alt="Foto Siswa" class="foto">
                </div>
                <div class="col">
                    <h1><?php echo htmlspecialchars($dataSiswa["nama"] ?? 'Nama Tidak Tersedia'); ?></h1>
                    <table>
                        <tr>
                            <td class="tab">NISN</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($dataSiswa["nisn"] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="tab">Tanggal Lahir</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($dataSiswa["tanggal_lahir"] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="tab">Kelas</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($dataSiswa["nama_kelas"] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="tab">Alamat</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($dataSiswa["alamat"] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="tab">Nama Ibu</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($dataSiswa["nama_ibu"] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="tab">No HP</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($dataSiswa["no_hp_orang_tua"] ?? '-'); ?></td>
                        </tr>
                    </table>

                    <?php if (isset($success)): ?>
                        <div class="success">
                            <h1 class="m-alert"><strong>Absensi Berhasil</strong></h1>
                            <p><?php echo $waktuSekarang; ?> WITA (<?php echo $statusAbsensi; ?>)</p>
                        </div>
                    <?php endif; ?>


                    <?php if (isset($error)): ?>
                        <div class="error">
                            <h1 class="m-alert"><strong>Absensi Gagal</strong></h1>
                            <p><?php echo $error ?> </p>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="absensi-container">
                    <h3>Catatan Absensi Siswa</h3>

                    <h4>1. Jadwal Absensi</h4>
                    <ul>
                        <li><strong>📌 Absensi Masuk:</strong> Waktu absensi: 
                            <strong><?= date('H:i', strtotime($sekolah['jam_masuk'])) . ' - ' . date('H:i', strtotime($sekolah['jam_masuk'] . ' +1 hours')) ?></strong>
                        </li>
                        
                        <li><strong>📌 Absensi Pulang:</strong> Waktu absensi: 
                            <?= date('H:i', strtotime($sekolah['jam_pulang'])) . ' - ' . date('H:i', strtotime($sekolah['jam_pulang'] . ' +1     hours')) ?>
                        </li>
                    </ul>

                    <h4>2. Sanksi Keterlambatan</h4>
                    <ul>
                        <li><strong>🔴 Terlambat 1 - 2 kali dalam sebulan:</strong> ✅ Teguran lisan dari guru piket atau wali
                            kelas.</li>
                        <li><strong>🔴 Terlambat 3 - 4 kali dalam sebulan:</strong> ✅ Teguran tertulis dan pemberitahuan kepada
                            orang tua/wali siswa.</li>
                        <li><strong>🔴 Terlambat 5 kali atau lebih dalam sebulan:</strong> ✅ Pemanggilan orang tua untuk
                            pembinaan siswa. ✅ Siswa wajib membuat surat pernyataan tidak mengulangi keterlambatan.</li>
                        <li><strong>🔴 Terlambat lebih dari 7 kali dalam sebulan:</strong> ✅ Sanksi lebih lanjut seperti tugas
                            tambahan, skorsing, atau tindakan lain sesuai kebijakan sekolah.</li>
                    </ul>
                </div>

    <?php endif; ?>

</div>
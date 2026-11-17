<div class="container">
    
    <?php if (isset($_SESSION['errors'])){ require_once 'error.php'; } ?>

    <?php if (empty($dataGuru) && !isset($_SESSION['errors'])): ?>

        <div id="clock">
            <p style="position:relative; padding:10px; text-align: center; font-size: 100px; " id="date-time"></p>
        </div>


    <?php  else: ?>
            <div class="row">

                <div class="col foto">
                    <img class="img" style="width: 300px"
                        src="uploads/<?php echo isset($dataGuru["foto"]) ? htmlspecialchars($dataGuru["foto"]) : 'default.jpg'; ?>"
                        alt="Foto Guru" class="foto">
                </div>
                <div class="col">
                    <h1><?php echo htmlspecialchars($dataGuru["nama"] ?? 'Nama Tidak Tersedia'); ?></h1>
                    <table>
                        <tr>
                            <td class="tab">NIP</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($dataGuru["nip"] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="tab">Jabatan</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($dataGuru["jabatan"] ?? '-'); ?></td>
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
                    <h3>Catatan Absensi Guru</h3>

                    <h4>1. Jadwal Absensi</h4>
                    <ul>
                        <li><strong>📌 Absensi Masuk:</strong> Waktu absensi: 
                            <strong><?= date('H:i', strtotime($sekolah['jam_masuk'])) . ' - ' . date('H:i', strtotime($sekolah['jam_masuk'] . ' +1 hours')) ?></strong>
                        </li>
                        
                        <li><strong>📌 Absensi Pulang:</strong> Waktu absensi: 
                            <?= date('H:i', strtotime($sekolah['jam_pulang'])) . ' - ' . date('H:i', strtotime($sekolah['jam_pulang'] . ' +1 hours')) ?>
                        </li>
                    </ul>

                    <h4>2. Sanksi Keterlambatan</h4>
                    <ul>
                        <li><strong>🔴 Terlambat 1 - 2 kali dalam sebulan:</strong> ✅ Teguran lisan dari pimpinan.</li>
                        <li><strong>🔴 Terlambat 3 - 4 kali dalam sebulan:</strong> ✅ Teguran tertulis.</li>
                        <li><strong>🔴 Terlambat 5 kali atau lebih dalam sebulan:</strong> ✅ Pemanggilan untuk pembinaan. ✅ Wajib membuat surat pernyataan tidak mengulangi keterlambatan.</li>
                        <li><strong>🔴 Terlambat lebih dari 7 kali dalam sebulan:</strong> ✅ Sanksi lebih lanjut sesuai peraturan instansi.</li>
                    </ul>
                </div>

    <?php endif; ?>

</div>

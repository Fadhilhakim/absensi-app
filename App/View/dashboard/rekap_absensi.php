<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../../login.php');
    exit;
}
?>
<link rel="stylesheet" href="css/input.css">
<link rel="stylesheet" href="css/dashboard/rekap_absensi.css">


<?php if (isset($_SESSION['error'])) {
    require_once 'error.php';
} ?>
<?php if (isset($_SESSION['success'])) {
    require_once 'success.php';
} ?>

<div class="card">
    <div class="card-header">
        <div>
            <h2>📊 Rekap Absensi</h2>
            <p>Kelola dan tinjau data absensi siswa secara detail</p>
        </div>
    </div>
    <div class="card-content">
        <div id="sectionRekapAbsensi">
            <!-- FILTER SECTION -->
            <div class="form-section">
                <h3 style="margin-top: 0; color: var(--text-primary);">Filter Absensi</h3>
                <form method="get" action="dashboard.php">
                    <input type="hidden" name="page" value="rekap-absensi">
                    
                    <div class="form-group">
                        <label for="tanggal">Pilih Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="<?= htmlspecialchars($_GET['tanggal'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="kelas">Pilih Kelas</label>
                        <select name="kelas" id="kelas">
                            <option value="">-- Semua Kelas --</option>
                            <?php foreach ($nama_kelas as $row): ?>
                                <option value="<?= htmlspecialchars($row['id_kelas']) ?>" <?= (isset($_GET['kelas']) && $_GET['kelas'] == $row['id_kelas']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['nama_kelas']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="edit-btn">Lihat Data</button>
                        <a href="?page=rekap-absensi" style="padding: 10px; border-radius: 6px; background: #6b7280; color: white; text-decoration: none; font-weight: 600; transition: all 0.2s; text-align: center;">Hari Ini</a>
                    </div>
                </form>

                <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 16px 0;">

                <h3 style="margin-top: 0; color: var(--text-primary);">Download Rekap</h3>
                <form method="get" action="dashboard.php">
                    <input type="hidden" name="page" value="rekap-absensi">

                    <div class="form-group">
                        <label for="bulan">Pilih Bulan</label>
                        <input type="month" name="bulan" id="bulan" value="<?= htmlspecialchars($_GET['bulan'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="kelasDownload">Pilih Kelas</label>
                        <select name="kelas" id="kelasDownload">
                            <option value="">-- Semua Kelas --</option>
                            <?php foreach ($nama_kelas as $row): ?>
                                <option value="<?= htmlspecialchars($row['id_kelas']) ?>"
                                    <?= (isset($_GET['kelas']) && $_GET['kelas'] == $row['id_kelas']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['nama_kelas']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="edit-btn">Lihat Rekap</button>
                        <a href="?page=rekap-absensi" style="padding: 10px; border-radius: 6px; background: #6b7280; color: white; text-decoration: none; font-weight: 600; transition: all 0.2s; text-align: center;">Reset</a>
                    </div>
                </form>
            </div>

            <!-- TABEL ABSENSI -->
            <div class="table-container">
                <?php
                $tanggalTampil = isset($_GET['tanggal']) ? date('l, d F Y', strtotime($_GET['tanggal'])) : date('l, d F Y');
                $namaKelas = '';
                if (!empty($_GET['kelas']) && isset($nama_kelas)) {
                    foreach ($nama_kelas as $kls) {
                        if ($kls['id_kelas'] == $_GET['kelas']) {
                            $namaKelas = $kls['nama_kelas'];
                            break;
                        }
                    }
                }
                ?>

                <div class="search-section">
                    <input type="text" id="searchInput" placeholder="Cari nama siswa..." style="flex: 1; min-width: 200px;">
                </div>

                <h3 style="color: var(--text-primary); margin: 16px 0 12px;">
                    ABSENSI <?= htmlspecialchars($tanggalTampil) ?>
                    <?= $namaKelas ? " - KELAS " . htmlspecialchars($namaKelas) : "" ?>
                </h3>

                <table class="table-d" id="dataTabel" style="padding:0; font-size: smaller;">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Masuk</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Pulang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($absensi)): ?>
                            <tr><td colspan="8" align="center">Tidak ada data</td></tr>
                        <?php else: ?>
                            <?php foreach ($absensi as $row): ?>
                                <tr>
                                    <form method="post" 
                                        action="?page=rekap-absensi<?= isset($_GET['tanggal']) ? '&tanggal=' . htmlspecialchars($_GET['tanggal']) : '' ?><?= isset($_GET['kelas']) ? '&kelas=' . htmlspecialchars($_GET['kelas']) : '' ?>">

                                        <!-- Foto -->
                                        <td align="center">
                                            <?php if (!empty($row['foto'])): ?>
                                                <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt="foto"
                                                     style="width:40px;height:60px;object-fit:cover;">
                                            <?php else: ?>
                                                <span style="color:#aaa;">(Tidak ada)</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Nama siswa -->
                                        <td><?= htmlspecialchars($row['nama']) ?></td>

                                        <!-- Nama kelas -->
                                        <td><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></td>

                                        <!-- Jam Masuk -->
                                        <td>
                                            <?= !empty($row['pulang']) 
                                                ? htmlspecialchars(date('H:i', strtotime($row['time']))) 
                                                : '-' ?>
                                        </td>

                                        <!-- Status -->
                                        <td>
                                            <select name="status" class="status-option" onchange="updateStatusColor(this)">
                                                <?php foreach (['Hadir', 'Sakit', 'Izin', 'Alpha'] as $opt): ?>
                                                    <option value="<?= $opt ?>" <?= $row['status'] == $opt ? 'selected' : '' ?>>
                                                        <?= $opt ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>

                                        <!-- Keterangan -->
                                        <td>
                                            <select name="keterangan" class="status-option" onchange="updateKetColor(this)">
                                                <?php foreach (['Tepat Waktu', 'Terlambat'] as $ket): ?>
                                                    <option value="<?= $ket ?>" <?= $row['keterangan'] == $ket ? 'selected' : '' ?>>
                                                        <?= $ket ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>

                                        <!-- Pulang -->
                                        <td>
                                            <?= !empty($row['pulang']) 
                                                ? htmlspecialchars(date('H:i', strtotime($row['pulang']))) 
                                                : '-' ?>
                                        </td>

                                        <!-- Tombol Simpan -->
                                        <td>
                                            <input type="hidden" name="tanggal" value="<?= htmlspecialchars($_GET['tanggal'] ?? '') ?>">
                                            <input type="hidden" name="kelas" value="<?= htmlspecialchars($_GET['kelas'] ?? '') ?>">
                                            <input type="hidden" name="id_absensi" value="<?= $row['id_absensi'] ?>">
                                            <button type="submit" class="edit-btn">Simpan</button>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Warna untuk status
    function updateStatusColor(select) {
        select.className = 'status-option'; // reset
        const val = select.value.toLowerCase();
        if (val === 'hadir') select.classList.add('status-hadir');
        else if (val === 'sakit') select.classList.add('status-sakit');
        else if (val === 'izin') select.classList.add('status-izin');
        else if (val === 'alpha') select.classList.add('status-alpha');
    }

    // Warna untuk keterangan
    function updateKetColor(select) {
        select.className = 'status-option'; // reset
        const val = select.value.toLowerCase();
        if (val === 'tepat waktu') select.classList.add('ket-tepat');
        else if (val === 'terlambat') select.classList.add('ket-terlambat');
    }

    // Inisialisasi warna saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('select[name="status"]').forEach(updateStatusColor);
        document.querySelectorAll('select[name="keterangan"]').forEach(updateKetColor);
    });

    document.getElementById("searchInput").addEventListener("keyup", function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#dataTabel tr");
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });

</script>
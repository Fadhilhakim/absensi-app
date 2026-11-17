<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../../login.php');
    exit;
}
?>
<link rel="stylesheet" href="css/input.css">
<link rel="stylesheet" href="css/dashboard/kelola_kelas.css">

<?php if (isset($_SESSION['error'])) {
    require_once 'error.php';
} ?>    
<?php if (isset($_SESSION['success'])) {
    require_once 'success.php';
} ?>

<div class="card">
    <div class="card-header">
        <div>
            <h2>📚 Kelola Kelas</h2>
            <p>Buat kelas baru atau edit kelas yang telah ada</p>
        </div>
        <div>
            <button type="button" id="btnShowData" class="edit-btn">📚 Data Kelas</button>
            <button type="button" id="btnShowAlumni" class="delete-btn">🕘 Alumni</button>
        </div>
    </div>

    <div class="card-content">
        <div id="sectionDataKelas">
            <div class="form-section">
                <form method="POST" id="single-upload">
                    <div class="form-group">
                        <label>Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control"
                            value="<?= $editData['nama_kelas'] ?? '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Wali Kelas</label>
                        <select name="wali_kelas" class="form-control" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php foreach ($data['guru'] as $g): ?>
                                <option value="<?= $g['nip'] ?>" <?= (isset($editData['wali_kelas']) && $editData['wali_kelas'] == $g['nip']) ? 'selected' : '' ?>>
                                    <?= $g['nama'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-buttons">
                        <input type="submit" value="<?= isset($editData) ? '💾 Update' : '💾 Simpan' ?>">
                        <?php if (isset($editData)): ?>
                            <a href="dashboard.php?page=kelola-kelas" style="height: fit-content;" class="delete-btn">❌ Batal</a>
                        <?php else: ?>
                            <input type="reset" class="delete-btn" value="🔄 Reset">
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Alumni Section -->
            <?php if (isset($editData)): ?>
            <div id="alumniSection">
                <h4>⚠️ Buat Menjadi Kelas Alumni</h4>
                <p>Semua siswa pada kelas ini akan dipindahkan ke tabel alumni dan dihapus dari absensi</p>
                <form method="POST"
                    onsubmit="return confirm('Pindahkan seluruh siswa kelas ini ke Alumni? Tindakan ini tidak dapat dibatalkan.');">
                    <label>Tahun Lulus</label>
                    <input type="number" name="tahun_lulus" min="2000" max="2100"
                        value="<?= date('Y') ?>" required>
                    <label style="display:flex; align-items:center; gap:8px; margin-top:12px;">
                        <input type="checkbox" name="hapus_kelas" value="1" checked> 
                        Hapus kelas aktif setelah dipindah
                    </label>
                    <input type="hidden" name="aksi" value="pindah_alumni">
                    <input type="hidden" name="id_kelas" value="<?= htmlspecialchars($editData['id_kelas'] ?? '') ?>">
                    <button type="submit">➡️ Pindahkan ke Alumni</button>
                </form>
            </div>
            <?php endif; ?>
        </div>

        <div class="table-container">
            <table class="table-d">
                <thead>
                    <tr>
                        <th style="text-align: center;">ID</th>
                        <th>Nama Kelas</th>
                        <th>Wali Kelas</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['kelas'] as $k): ?>
                        <tr>
                            <td style="text-align: center; font-weight: 500;"><?= $k['id_kelas'] ?></td>
                            <td style="font-weight: 500;"><?= htmlspecialchars($k['nama_kelas']) ?></td>
                            <td><?= htmlspecialchars($k['nama_wali'] ?? '-') ?></td>
                            <td style="text-align: center;">
                                <div class="action-buttons">
                                    <a href="?page=kelola-kelas&action=edit&id_kelas=<?= $k['id_kelas'] ?>">
                                        <button class="edit-btn">✏️ Edit</button>
                                    </a>
                                    <a href="?page=kelola-kelas&action=delete&id_kelas=<?= $k['id_kelas'] ?>" 
                                       onclick="return confirm('Yakin hapus?')">
                                        <button class="delete-btn">🗑️ Hapus</button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (!empty($data['alumniKelas'])): ?>
        <div id="sectionAlumni" style="display:none;">
            <h3>🕘 Riwayat Kelas (Alumni)</h3>
            <div style="overflow-x: auto;">
                <table class="table-d">
                    <thead>
                        <tr>
                            <th style="text-align:center;">ID</th>
                            <th>Nama Kelas</th>
                            <th style="text-align:center;">Tahun Lulus</th>
                            <th style="text-align:center;">Jumlah</th>
                            <th style="text-align:center;">Tanggal</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['alumniKelas'] as $ak): ?>
                            <tr>
                                <td style="text-align:center;"><?= $ak['id_alumni_kelas'] ?></td>
                                <td><?= htmlspecialchars($ak['nama_kelas']) ?></td>
                                <td style="text-align:center;"><?= htmlspecialchars($ak['tahun_lulus']) ?></td>
                                <td style="text-align:center;"><?= (int) $ak['jumlah_alumni'] ?></td>
                                <td style="text-align:center;"><?= htmlspecialchars($ak['tanggal_dipindah']) ?></td>
                                <td style="text-align:center;">
                                    <button class="edit-btn" onclick="toggleAlumni('alumni-<?= $ak['id_alumni_kelas'] ?>')">👁️ Lihat</button>
                                </td>
                            </tr>
                            <tr id="alumni-<?= $ak['id_alumni_kelas'] ?>" style="display:none; background:#f9fafb;">
                                <td colspan="6">
                                    <?php
                                    $as = (new \App\Model\KelasModel())->getAlumniStudents($ak['id_alumni_kelas']);
                                    if (empty($as)) {
                                        echo '<em>Tidak ada data</em>';
                                    } else { ?>
                                        <table style="width:100%; border-collapse:collapse;" class="inner-table">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:left; padding:6px 8px;">NISN</th>
                                                    <th style="text-align:left; padding:6px 8px;">Nama</th>
                                                    <th style="text-align:left; padding:6px 8px;">Alamat</th>
                                                    <th style="text-align:left; padding:6px 8px;">No. HP</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($as as $row): ?>
                                                    <tr>
                                                        <td style="padding:6px 8px;"><?= htmlspecialchars($row['nisn']) ?></td>
                                                        <td style="padding:6px 8px;"><?= htmlspecialchars($row['nama']) ?></td>
                                                        <td style="padding:6px 8px;"><?= htmlspecialchars($row['alamat']) ?></td>
                                                        <td style="padding:6px 8px;"><?= htmlspecialchars($row['no_hp_orang_tua']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function toggleAlumni(id) {
        const row = document.getElementById(id);
        if (!row) return;
        row.style.display = row.style.display === 'none' ? '' : 'none';
    }

    (function() {
        const btnData = document.getElementById('btnShowData');
        const btnAlumni = document.getElementById('btnShowAlumni');
        const secData = document.getElementById('sectionDataKelas').parentElement;
        const secAlumni = document.getElementById('sectionAlumni');
        if (!btnData || !btnAlumni) return;

        function showData() {
            secData.style.display = '';
            if (secAlumni) secAlumni.style.display = 'none';
        }

        function showAlumni() {
            secData.style.display = 'none';
            if (secAlumni) secAlumni.style.display = '';
        }

        btnData.addEventListener('click', showData);
        btnAlumni.addEventListener('click', showAlumni);
    })();
</script>
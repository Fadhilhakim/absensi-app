<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../../login.php');
    exit;
}
?>
<link rel="stylesheet" href="css/input.css">
<link rel="stylesheet" href="css/dashboard/guru_absensi.css">

<?php if (isset($_SESSION['error'])){ require_once 'error.php'; } ?>
<?php if (isset($_SESSION['success'])){ require_once 'success.php'; } ?>

<div class="page-header">
    <h1>👨‍🏫 Absensi Guru</h1>
    <p>Kelola dan lihat absensi guru sekolah</p>
</div>

<div class="filter-section">
    <h3>🔍 Filter Absensi</h3>
    <form method="get" class="filter-box">
        <input type="hidden" name="page" value="guru-absensi">
        <input type="date" name="tanggal" value="<?= htmlspecialchars($data['tanggal'] ?? date('Y-m-d')) ?>">
        <button type="submit" style="width: 200px; height: 50px">🔎 Cari</button>
        <a href="?page=guru-absensi">🔄 Reset</a>
        <a href="?page=download-recap-guru" style="background: #ffe600ff; margin-left: auto; color: black">📊 Rekap Bulanan</a>
    </form>
</div>

<div class="table-container">
    <table class="table-d">
        <thead>
            <tr>
                <th style="text-align: center; width: 5%">No</th>
                <th style="width: 15%">Foto</th>
                <th style="width: 20%">Nama Guru</th>
                <th style="width: 15%">Jabatan</th>
                <th style="text-align: center; width: 12%">Jam Masuk</th>
                <th style="text-align: center; width: 12%">Status</th>
                <th style="text-align: center; width: 10%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (!empty($data['absensi'])): 
                foreach ($data['absensi'] as $row): 
                    $statusClass = 'status-' . strtolower($row['status'] ?? '');
            ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td>
                        <?php if (!empty($row['foto']) && file_exists('uploads/' . $row['foto'])): ?>
                            <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['nama']) ?>" style="width: 50px; height: 50px; border-radius: 6px; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 50px; height: 50px; border-radius: 6px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #6b7280;">👤</div>
                        <?php endif; ?>
                    </td>
                    <td style="font-weight: 500;"><?= htmlspecialchars($row['nama'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['jabatan'] ?? '-') ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($row['time'] ?? '-') ?></td>
                    <td style="text-align: center;">
                        <span class="status-badge <?= $statusClass ?>">
                            <?= htmlspecialchars($row['status'] ?? 'Belum Diisi') ?>
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <button class="edit-btn" onclick="openEditForm(<?= htmlspecialchars(json_encode($row)) ?>)" style="padding: 6px 12px; font-size: 0.85rem;">✏️ Edit</button>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px 16px; color: #9ca3af;">
                        Tidak ada data absensi untuk tanggal <?= htmlspecialchars($data['tanggal'] ?? 'hari ini') ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Overlay -->
<div class="modal-overlay" id="modalOverlay" onclick="closeEditForm()"></div>

<!-- Edit Form Modal -->
<div class="modal-form" id="editForm">
    <div class="modal-header">
        <h3 id="formTitle">Edit Absensi Guru</h3>
        <button class="modal-close" onclick="closeEditForm()">✕</button>
    </div>
    
    <form method="post">
        <input type="hidden" name="id_absensi" id="id_absensi">
        <input type="hidden" name="tanggal" id="tanggal_input" value="<?= htmlspecialchars($data['tanggal'] ?? date('Y-m-d')) ?>">
        
        <div class="form-group">
            <label>Nama Guru</label>
            <div style="padding: 10px; background: #f3f4f6; border-radius: 6px; font-weight: 500;" id="guru_nama">-</div>
        </div>

        <div class="form-group">
            <label for="status">Status Kehadiran</label>
            <select id="status" name="status" required>
                <option value="">-- Pilih Status --</option>
                <option value="Hadir">✅ Hadir</option>
                <option value="Sakit">🤒 Sakit</option>
                <option value="Izin">📋 Izin</option>
                <option value="Alpha">❌ Alpha</option>
            </select>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan tambahan..."></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">💾 Simpan</button>
            <button type="button" class="btn-cancel" onclick="closeEditForm()">❌ Batal</button>
        </div>
    </form>
</div>

<script>
    function openEditForm(rowData) {
        document.getElementById('id_absensi').value = rowData.id_absensi;
        document.getElementById('guru_nama').textContent = rowData.nama;
        document.getElementById('status').value = rowData.status || '';
        document.getElementById('keterangan').value = rowData.keterangan || '';
        
        document.getElementById('modalOverlay').classList.add('active');
        document.getElementById('editForm').classList.add('active');
    }

    function closeEditForm() {
        document.getElementById('modalOverlay').classList.remove('active');
        document.getElementById('editForm').classList.remove('active');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditForm();
        }
    });
</script>

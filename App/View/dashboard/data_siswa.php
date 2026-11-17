<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../../login.php');
    exit;
}
?>
<link rel="stylesheet" href="css/input.css">
<link rel="stylesheet" href="css/dashboard/data_siswa.css">

<?php if (isset($_SESSION['error'])) {
    require_once 'error.php';
} ?>    
<?php if (isset($_SESSION['success'])) {
    require_once 'success.php';
} ?>

<div class="card">
    <div class="card-header">
        <div style="display: block;">
            <h2>👨‍🎓 Data Siswa</h2> 
            <p>Kelola data siswa, tambah siswa baru, dan upload data dalam jumlah besar</p>
        </div>
    </div>

    <div class="card-content">
        <div id="sectionDataSiswa">
            <!-- Form Input Siswa -->
            <div class="form-section">
                <form id="single-upload" action="?page=data-siswa<?= isset($editData) ? '&action=edit&nisn=' . $editData['nisn'] : '' ?>" method="post" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nisn">NISN</label>
                            <input type="text" id="nisn" name="nisn" required value="<?= $editData['nisn'] ?? '' ?>" <?= isset($editData) ? 'readonly' : '' ?>>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" required value="<?= $editData['nama'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="kelas">Kelas</label>
                            <select name="kelas" id="kelas" required>
                                <option value="">-- Pilih Kelas --</option>
                                <?php foreach ($data['kelas'] as $k) : ?>
                                    <option value="<?= $k['id_kelas']; ?>"
                                        <?= isset($editData['kelas']) && $editData['kelas'] == $k['id_kelas'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($k['nama_kelas']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required value="<?= $editData['tanggal_lahir'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="form-group form-row full">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" required><?= $editData['alamat'] ?? '' ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama_ibu">Nama Ibu</label>
                            <input type="text" id="nama_ibu" name="nama_ibu" required value="<?= $editData['nama_ibu'] ?? '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="no_hp_orang_tua">No HP Orang Tua</label>
                            <input type="text" id="no_hp_orang_tua" name="no_hp_orang_tua" required value="<?= $editData['no_hp_orang_tua'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="form-group form-row full">
                        <label>Foto Siswa</label>
                        <div class="photo-section">
                            <div class="photo-preview">
                                <img id="preview" src="uploads/<?= isset($editData) ? $editData['foto'] : 'default.png' ?>" alt="Preview Foto">
                            </div>
                            <div class="photo-input">
                                <input type="file" id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
                                <input type="text" name="old_photo" id="old_photo" value="<?= $editData['foto'] ?? '' ?>" hidden>
                                <small style="color: #6b7280;">Format: JPG, PNG (Max 2MB)</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-buttons">
                        <input type="submit" value="<?= isset($editData) ? '💾 Update' : '💾 Simpan' ?>">
                        <?php if (isset($editData)): ?>
                            <a href="?page=data-siswa" class="delete-btn">❌ Batal</a>
                        <?php else: ?>
                            <input type="reset" value="🔄 Reset">
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <!-- Bulk Upload Section -->
            <div class="bulk-section">
                <h3>📤 Upload Data Siswa Massal</h3>
                <p>Download template Excel dan import data siswa dalam jumlah besar</p>
                <a href="downloads/template_siswa.xlsx" class="download-link">⬇️ Download Template</a>
                
                <form id="bulk-upload"  action="?page=data-siswa&action=bulk-upload" method="post" enctype="multipart/form-data" style="margin-top: 16px; width: 100%;">
                    <label for="csv">Pilih File CSV:</label>
                    <input type="file" id="csv" name="csv" accept=".csv" required>
                    <input id="bulk-submit" type="submit" value="📤 Upload">
                </form>
            </div>
        </div>


    </div>
    
    <!-- CSV Preview Modal -->
<div id="csvModal" class="csv-modal">
    <div class="csv-modal-content">
        <div class="csv-modal-header">
            <h2>📋 Preview Data CSV</h2>
            <button type="button" class="csv-modal-close" onclick="closeCSVModal()">✕</button>
        </div>
        
        <div id="csvSummary" class="csv-summary"></div>
        <div id="csvPreview" class="csv-preview"></div>
        
        <div class="csv-modal-footer">
            <button type="button" class="btn-close" onclick="closeCSVModal()">Tutup</button>
            <button type="button" class="btn-submit" onclick="submitBulkUpload()">✅ Simpan Data</button>
        </div>
    </div>
</div>
<!-- Tabel Data Siswa -->
<div class="table-container">
    <form method="GET" action="" style="all:revert;">
    <div class="search-section">
            <input type="hidden" name="page" value="data-siswa">
            <input type="text" id="searchInput" placeholder="Cari siswa (Nama/NISN/Kelas)..." name="q" value="<?= htmlspecialchars($data['q'] ?? '') ?>">
            <?php if (!empty($data['q'])): ?>
                <a href="?page=data-siswa" class="delete-btn">🔄 Reset</a>
            <?php endif; ?>
        </div>
    </form>

    <table class="table-d">
        <thead>
            <tr>
                <th style="text-align: center; width: 60px;">NISN</th>
                <th style="text-align: center; width: 60px;">Foto</th>
                <th>Nama</th>
                <th style="text-align: center;">Kelas</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody id="dataTabel">
            <?php foreach ($data['siswa'] as $siswa): ?>
                <tr>
                    <td style="text-align: center; font-weight: 500;"><?php echo htmlspecialchars($siswa['nisn']); ?></td>
                    <td style="text-align: center;">
                        <img src="uploads/<?php echo htmlspecialchars($siswa['foto']); ?>" alt="Foto" class="student-photo">
                    </td>
                    <td><?php echo htmlspecialchars($siswa['nama']); ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($siswa['nama_kelas']); ?></td>
                    <td style="text-align: center;">
                        <div class="action-buttons">
                            <a href="?page=data-siswa&action=edit&nisn=<?= $siswa['nisn'] ?>">
                                <button class="edit-btn">✏️ Edit</button>
                            </a>
                            <a href="?page=data-siswa&action=delete&nisn=<?= $siswa['nisn'] ?>" onclick="return confirm('Yakin ingin menghapus siswa ini?')">
                                <button class="delete-btn">🗑️ Hapus</button>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if (isset($data['totalPages']) && $data['totalPages'] > 1): ?>
        <div class="pagination">
            <?php $current = (int)($data['pageNo'] ?? 1); $totalPages = (int)$data['totalPages']; $q = urlencode($data['q'] ?? ''); $qParam = $q ? ('&q=' . $q) : ''; ?>
            <?php if ($current > 1): ?>
                <a href="?page=data-siswa&pageNo=1<?= $qParam ?>">⏮️ First</a>
                <a href="?page=data-siswa&pageNo=<?= $current - 1 . $qParam ?>">⏪ Prev</a>
            <?php endif; ?>

            <?php for ($i = max(1, $current - 2); $i <= min($totalPages, $current + 2); $i++): ?>
                <?php if ($i == $current): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=data-siswa&pageNo=<?= $i . $qParam ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($current < $totalPages): ?>
                <a href="?page=data-siswa&pageNo=<?= $current + 1 . $qParam ?>">Next ⏩</a>
                <a href="?page=data-siswa&pageNo=<?= $totalPages . $qParam ?>">Last ⏭️</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        this.form.submit();
    }
});


function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function closeCSVModal() {
    document.getElementById('csvModal').style.display = 'none';
}

function submitBulkUpload() {
    document.getElementById('bulk-upload').submit();
}

// CSV file input handler
document.getElementById('csv')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(event) {
        const csv = event.target.result;
        const lines = csv.split('\n');
        
        if (lines.length < 2) {
            alert('File CSV kosong atau tidak valid');
            return;
        }

        const summary = document.getElementById('csvSummary');
        const preview = document.getElementById('csvPreview');
        
        summary.innerHTML = `<strong>Total baris data:</strong> ${lines.length - 1}`;
        
        let tableHTML = '<table class="table-d"><thead><tr>';
        const headers = lines[0].split(',').map(h => h.trim());
        
        headers.forEach(header => {
            tableHTML += `<th>${header}</th>`;
        });
        
        tableHTML += '</tr></thead><tbody>';
        
        for (let i = 1; i < Math.min(lines.length, 11); i++) {
            if (lines[i].trim()) {
                const cells = lines[i].split(',').map(c => c.trim());
                tableHTML += '<tr>';
                cells.forEach(cell => {
                    tableHTML += `<td>${cell}</td>`;
                });
                tableHTML += '</tr>';
            }
        }
        
        if (lines.length > 11) {
            tableHTML += `<tr><td colspan="${headers.length}" style="text-align: center; padding: 12px; color: #6b7280;">... dan ${lines.length - 11} baris lainnya</td></tr>`;
        }
        
        tableHTML += '</tbody></table>';
        preview.innerHTML = tableHTML;
        
        document.getElementById('csvModal').style.display = 'block';
    };
    
    reader.readAsText(file);
});
</script>

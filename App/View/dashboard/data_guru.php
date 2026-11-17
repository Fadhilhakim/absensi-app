<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../../login.php');
    exit;
}
?>
<!-- <link rel="stylesheet" href="css/input.css"> -->
<link rel="stylesheet" href="css/dashboard/data_guru.css">

<div class="page-header">
    <h1>👨‍🏫 Data Guru dan Staf</h1>
    <p>Kelola data guru dan staf sekolah</p>
</div>

<?php if (isset($_SESSION['error'])){ require_once 'error.php'; } ?>
<?php if (isset($_SESSION['success'])){ require_once 'success.php'; } ?>

<!-- Form Input / Edit Guru -->
<div class="form-section">
    <h3><?= isset($editData) ? '✏️ Edit Data Guru' : '➕ Tambah Data Guru Baru' ?></h3>
    
    <form id="single-upload" 
          action="?page=data-guru<?= isset($editData) ? '&action=edit&nip=' . $editData['nip'] : '' ?>" 
          method="post" 
          enctype="multipart/form-data">

        <div class="form-group">
            <label for="nip">NIP (Nomor Induk Pegawai):</label>
            <input type="text" id="nip" name="nip" required 
                   placeholder="Masukkan NIP..."
                   value="<?= $editData['nip'] ?? '' ?>" 
                   <?= isset($editData) ? 'readonly' : '' ?>>
        </div>

        <div class="form-group">
            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" required 
                   placeholder="Masukkan nama lengkap..."
                   value="<?= $editData['nama'] ?? '' ?>">
        </div>

        <div class="form-group">
            <label for="jabatan">Jabatan:</label>
            <select class="inpt" id="jabatan" name="jabatan" required>
                <option value="">-- Pilih Jabatan --</option>
                <?php
                $jabatanOptions = ['Guru', 'Guru BK', 'Kepala Tata Usaha', 'Staf Perpustakaan', 'Kepala Laboratorium', 'Operator Sekolah', 'Koordinator', 'Satpam', 'Kepala Sekolah', 'Wakil Kepala Sekolah'];
                $selectedJabatan = $editData['jabatan'] ?? '';
                foreach ($jabatanOptions as $option):
                ?>
                    <option value="<?= $option ?>" <?= $selectedJabatan === $option ? 'selected' : '' ?>>
                        <?= $option ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Photo Upload Section -->
        <div class="photo-upload-section">
            <div class="photo-preview">
                <img id="preview" 
                    src="uploads/<?= isset($editData) ? $editData['foto'] : 'default.png' ?>" 
                    alt="Preview Foto">
            </div>
            <div class="photo-upload-controls">
                <label for="foto">📷 Upload Foto:</label>
                <input class="inpt" type="file" id="foto" name="foto" accept="image/*" 
                    onchange="previewImage(event)">
                <p style="font-size: 0.85rem; color: var(--text-secondary); margin: 0;">
                    Format: JPG, PNG, GIF (Ukuran max 5MB)
                </p>
                <input type="text" name="old_photo" id="old_photo" value="<?= isset($editData) ? $editData['foto'] : 'default.png' ?>" hidden>
            </div>
        </div>

        <div class="form-actions">
            <input type="submit" value="<?= isset($editData) ? '💾 Update' : '💾 Simpan' ?>">
            <?php if (isset($editData)): ?>
                <a href="?page=data-guru" style="text-decoration: none;">❌ Batal</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Search Section -->
<div class="search-section">
    <h3>🔍 Cari Guru atau Staf</h3>
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Ketik nama, NIP, atau jabatan...">
        <button id="searchBtn">Cari</button>
    </div>
</div>

<!-- Guru Data Table -->
<div class="table-container">
    <table class="table-d">
        <thead>
            <tr>
                <th style="text-align:center; width: 120px;">NIP</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th style="text-align:center; width: 100px;">Foto</th>
                <th style="text-align:center; width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="dataTabel">
            <?php foreach ($data['guru'] as $guru): ?>
                <tr>
                    <td style="text-align:center; font-weight: 500;"><?= $guru['nip']; ?></td>
                    <td style="font-weight: 500;"><?= htmlspecialchars($guru['nama']); ?></td>
                    <td><?= htmlspecialchars($guru['jabatan']); ?></td>
                    <td style="text-align:center;">
                        <img src="uploads/<?= $guru['foto'] ?? 'default.png' ?>" 
                             alt="Foto <?= htmlspecialchars($guru['nama']); ?>" 
                             style="width: 40px; height: 50px; object-fit: cover;">
                    </td>
                    <td style="text-align:center;"> 
                        <div class="action-buttons">
                            <a href="?page=data-guru&action=edit&nip=<?= $guru['nip'] ?>">
                                <button class="edit-btn">✏️ Edit</button>
                            </a>
                            <a href="?page=data-guru&action=delete&nip=<?= $guru['nip'] ?>" 
                               onclick="return confirm('Yakin ingin menghapus data guru ini?')">
                                <button class="delete-btn">🗑️ Hapus</button>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById("searchInput").addEventListener("keyup", function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#dataTabel tr");
        let visibleCount = 0;
        
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            let isVisible = text.includes(filter);
            row.style.display = isVisible ? "" : "none";
            if (isVisible) visibleCount++;
        });
    });

    document.getElementById('searchBtn').addEventListener('click', function() {
        document.getElementById('searchInput').focus();
    });

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('preview').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</script>

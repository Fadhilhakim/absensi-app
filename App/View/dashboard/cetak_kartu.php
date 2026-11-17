<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../../login.php');
    exit;
}
?>
<link rel="stylesheet" href="css/input.css">
<link rel="stylesheet" href="css/dashboard/cetak_kartu.css">

<div class="page-header">
    <h1>🖨️ Cetak Kartu Siswa</h1>
    <p>Cetak kartu identitas siswa secara individual atau per kelas</p>
</div>

<?php if (isset($_SESSION['error'])){ require_once 'error.php'; } ?>
<?php if (isset($_SESSION['success'])){ require_once 'success.php'; } ?>

<!-- Print Options Section -->
<div class="print-section">
    <!-- Single Student Print -->
    <div class="print-card" style="width: 100%;">
        <h3>📋 Cetak Kartu Siswa Individual</h3>
        <div class="preview-container">
            <?php include 'kartu/kartu-preview.php' ?>
        </div>
        <div class="button-group">
            <a href='?page=cetak-kartu&action=cetak&nisn=<?= $preview['nisn'] ?? '' ?>' target="_blank" class="edit-btn" style="text-align: center; text-decoration: none;">🖨️ Cetak Kartu</a>
        </div>
    </div>

    <!-- Class Print -->
    <div class="print-card" style="width: 40%;">
        <h3>👥 Cetak Kartu Sesuai Kelas</h3>
        <form method="POST" action="?page=cetak-kartu&action=cetak" target="_blank" style="all: revert; height: 100%; display: flex; flex-direction: column;">
            <div class="form-grup" style="flex: 1;">
                <label for="kelas">Pilih Kelas:</label>
                <select name="kelas" id="kelas" class="inpt form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelas as $k) : ?>
                        <option value="<?= $k['id_kelas']; ?>">
                            <?= htmlspecialchars($k['nama_kelas']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="edit-btn" style="width: 100%; padding: 12px;">
                    🖨️ Cetak Semua Siswa
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Search and List Section -->
<div class="search-section">
    <h3>🔍 Cari & Pilih Siswa</h3>
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Ketik nama atau NISN siswa...">
        <button id="searchBtn">Cari</button>
    </div>
</div>

<!-- Student Table -->
<div class="table-container">
    <table class="table-d">
        <thead>
            <tr>
                <th style="text-align:center; width: 100px;">NISN</th>
                <th style="text-align:center; width: 80px;">Foto</th>
                <th>Nama Siswa</th>
                <th style="text-align:center; width: 120px;">Kelas</th>
                <th style="text-align:center; width: 120px;">Aksi</th>
            </tr>
        </thead>
        <tbody id="dataTabel">
            <?php foreach ($siswa as $sis): ?>
                <tr>
                    <td style="text-align:center; font-weight: 500;"><?php echo $sis['nisn']; ?></td>
                    <td style="text-align:center;">
                        <img src="uploads/<?php echo $sis['foto'] ?>" alt="Foto <?php echo $sis['nama']; ?>" style="width: 40px; height: 50px; object-fit: cover;">
                    </td>
                    <td style="font-weight: 500;"><?php echo htmlspecialchars($sis['nama']); ?></td>
                    <td style="text-align:center;"><?php echo htmlspecialchars($sis['nama_kelas']); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="?page=cetak-kartu&action=preview&nisn=<?= $sis['nisn'] ?>" class="edit-btn" style="width: 110px">👁️ Preview</a>
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
        
        // Show message if no results
        if (visibleCount === 0 && filter !== '') {
            console.log('No results found');
        }
    });

    document.getElementById('searchBtn').addEventListener('click', function() {
        document.getElementById('searchInput').focus();
    });
</script>
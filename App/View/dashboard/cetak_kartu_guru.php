<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../../login.php');
    exit;
}
?>
<link rel="stylesheet" href="css/input.css">
<link rel="stylesheet" href="css/dashboard/cetak_kartu_guru.css">

<?php if (isset($_SESSION['error'])){ require_once 'error.php'; } ?>
<?php if (isset($_SESSION['success'])){ require_once 'success.php'; } ?>

<div class="page-header no-print">
    <h1>🖨️ Cetak Kartu Guru</h1>
    <p>Cetak kartu identitas guru sekolah</p>
</div>

<div class="search-section no-print">
    <h3>🔍 Cari Guru</h3>
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Cari nama guru...">
        <button onclick="filterTable()">🔎 Cari</button>
    </div>
</div>

<div class="table-container no-print">
    <table class="table-d">
        <thead>
            <tr>
                <th style="text-align: center; width: 5%">No</th>
                <th style="width: 15%">Foto</th>
                <th style="width: 20%">Nama Guru</th>
                <th style="width: 15%">NIP</th>
                <th style="width: 20%">Jabatan</th>
                <th style="text-align: center; width: 10%">Aksi</th>
            </tr>
        </thead>
        <tbody id="guruTableBody">
            <?php 
            $no = 1;
            if (!empty($data['guru'])): 
                foreach ($data['guru'] as $g): 
            ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td>
                        <?php if (!empty($g['foto']) && file_exists('uploads/' . $g['foto'])): ?>
                            <img src="uploads/<?= htmlspecialchars($g['foto']) ?>" alt="<?= htmlspecialchars($g['nama'] ?? '') ?>" style="width: 50px; height: 50px; border-radius: 6px; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 50px; height: 50px; border-radius: 6px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #6b7280;">👤</div>
                        <?php endif; ?>
                    </td>
                    <td style="font-weight: 500;" class="guru-name"><?= htmlspecialchars($g['nama'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($g['nip'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($g['jabatan'] ?? '-') ?></td>
                    <td style="text-align: center;">
                        <div class="action-buttons">
                            <button class="edit-btn" onclick="printGuruCard('<?= htmlspecialchars($g['nip']) ?>')">🖨️ Cetak</button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px 16px; color: #9ca3af;">
                        Tidak ada data guru
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function printGuruCard(nip) {

        const printWindow = window.open(`?page=cetak-kartu-guru&action=print-individual&nip=${nip}`, '_blank');

        setTimeout(function() {
            if (printWindow) {
                printWindow.focus();
            }
        }, 500);
    }

    function filterTable() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const tableRows = document.querySelectorAll('#guruTableBody tr');

        tableRows.forEach(row => {
            const guruName = row.querySelector('.guru-name')?.textContent || '';
            row.style.display = guruName.toLowerCase().includes(searchValue) ? '' : 'none';
        });
    }

    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterTable();
        }
    });
</script>

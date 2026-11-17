<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../../login.php');
    exit;
}
?>
<link rel="stylesheet" href="css/input.css">
<link rel="stylesheet" href="css/dashboard/download_recap_guru.css">

<div class="logo-wrapper" style="display: none; border: none;">
    <!-- LOGO NORMAL -->
    <img src="logo_sekolah/logo_sekolah.svg"
        class="logo normal" style="border: none;">

    <!-- LOGO INVERT -->
    <img src="logo_sekolah/logo_sekolah.svg"
        class="logo invert" style="border: none;">
</div>

<div class="display-print" style="display: none;">
    <br><br><br>
</div>

<?php if (isset($_SESSION['error'])) {
    require_once 'error.php';
} ?>
<?php if (isset($_SESSION['success'])) {
    require_once 'success.php';
} ?>

<div class="page-header no-print">
    <h1>📊 Rekap Absensi Guru</h1>
    <p>Lihat dan unduh rekapitulasi absensi guru bulanan</p>
</div>

<div class="filter-section no-print bg-print">
    <h3>🔍 Filter Rekapitulasi</h3>
    <form method="get" class="filter-box">
        <input type="hidden" name="page" value="download-recap-guru">
        <input type="month" name="bulan" value="<?= htmlspecialchars($data['bulan'] ?? date('Y-m')) ?>" required>
        <button type="submit">🔎 Tampilkan</button>

        <div class="export-buttons">
            <button type="button" class="export-btn export-excel" onclick="exportToExcel()">📊 Export Excel</button>
            <button type="button" class="export-btn export-pdf" onclick="window.print()">📄 Print/PDF</button>
        </div>
    </form>
</div>

<?php if (!empty($data['guruAbsensi'])): ?>
    <div class="stats-section no-print">
        <div class="stat-card success">
            <h4>✅ Hadir</h4>
            <div class="number"><?= $data['stats']['hadir'] ?? 0 ?></div>
        </div>
        <div class="stat-card warning">
            <h4>🤒 Sakit</h4>
            <div class="number"><?= $data['stats']['sakit'] ?? 0 ?></div>
        </div>
        <div class="stat-card">
            <h4>📋 Izin</h4>
            <div class="number"><?= $data['stats']['izin'] ?? 0 ?></div>
        </div>
        <div class="stat-card danger">
            <h4>❌ Alpha</h4>
            <div class="number"><?= $data['stats']['alpha'] ?? 0 ?></div>
        </div>
    </div>



    <div class="table-container bg-print">
        <table class="table-d">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 20%">Nama Guru</th>
                    <th style="width: 15%">NIP</th>
                    <th style="text-align: center; width: 8%">Hadir</th>
                    <th style="text-align: center; width: 8%">Sakit</th>
                    <th style="text-align: center; width: 8%">Izin</th>
                    <th style="text-align: center; width: 8%">Alpha</th>
                    <th style="text-align: center; width: 8%">Terlambat</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $totalHadir = 0;
                $totalSakit = 0;
                $totalIzin = 0;
                $totalAlpha = 0;
                $totalTerlambat = 0;

                foreach ($data['guruAbsensi'] as $guru):
                    // Hitung statistik per guru
                    $hitung = [
                        'hadir' => 0,
                        'sakit' => 0,
                        'izin' => 0,
                        'alpha' => 0,
                        'terlambat' => 0
                    ];

                    foreach ($guru['absensi'] as $abs) {
                        $status = strtolower($abs['status'] ?? '');
                        $keterangan = strtolower($abs['keterangan'] ?? '');

                        if ($status === 'hadir') {
                            $hitung['hadir']++;
                        } elseif ($status === 'sakit') {
                            $hitung['sakit']++;
                        } elseif ($status === 'izin') {
                            $hitung['izin']++;
                        } elseif (in_array($status, ['alpha', 'alpa'])) {
                            $hitung['alpha']++;
                        }

                        if ($keterangan === 'terlambat') {
                            $hitung['terlambat']++;
                        }
                    }

                    $totalHadir += $hitung['hadir'];
                    $totalSakit += $hitung['sakit'];
                    $totalIzin += $hitung['izin'];
                    $totalAlpha += $hitung['alpha'];
                    $totalTerlambat += $hitung['terlambat'];
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td style="font-weight: 500;"><?= htmlspecialchars($guru['nama'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($guru['nip'] ?? '-') ?></td>
                        <td style="text-align: center; background: #dcfce7; color: #166534; font-weight: 600;"><?= $hitung['hadir'] ?></td>
                        <td style="text-align: center; background: #fef3c7; color: #92400e; font-weight: 600;"><?= $hitung['sakit'] ?></td>
                        <td style="text-align: center; background: #e0e7ff; color: #3730a3; font-weight: 600;"><?= $hitung['izin'] ?></td>
                        <td style="text-align: center; background: #fee2e2; color: #991b1b; font-weight: 600;"><?= $hitung['alpha'] ?></td>
                        <td style="text-align: center; background: #ede9fe; color: #5b21b6; font-weight: 600;"><?= $hitung['terlambat'] ?></td>

                    </tr>
                <?php endforeach; ?>
                <tr style="background: #f3f4f6; font-weight: 700;">
                    <td colspan="3" style="text-align: right;">TOTAL</td>
                    <td style="text-align: center; background: #dcfce7; color: #166534;"><?= $totalHadir ?></td>
                    <td style="text-align: center; background: #fef3c7; color: #92400e;"><?= $totalSakit ?></td>
                    <td style="text-align: center; background: #e0e7ff; color: #3730a3;"><?= $totalIzin ?></td>
                    <td style="text-align: center; background: #fee2e2; color: #991b1b;"><?= $totalAlpha ?></td>
                    <td style="text-align: center; background: #ede9fe; color: #5b21b6;"><?= $totalTerlambat ?></td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: center; margin-top: 24px; color: #6b7280; font-size: 0.9rem;">
            <p>Data rekapitulasi untuk bulan: <strong><?= date('F Y', strtotime($data['bulan'] ?? date('Y-m'))) ?></strong></p>
        </div>
    
    <?php else: ?>
        <div style="background: white; padding: 40px; border-radius: 12px; text-align: center; color: #9ca3af; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);">
            Tidak ada data absensi guru untuk bulan <?= htmlspecialchars($data['bulan'] ?? date('Y-m')) ?>
        </div>
    <?php endif; ?>
    </div>


<script>
    function exportToExcel() {
        const table = document.querySelector('.table-d');
        let csv = [];

        // Header
        const headers = [];
        table.querySelectorAll('thead th').forEach(th => {
            headers.push('"' + th.textContent.trim() + '"');
        });
        csv.push(headers.join(','));

        // Body
        table.querySelectorAll('tbody tr').forEach(tr => {
            const cells = [];
            tr.querySelectorAll('td').forEach(td => {
                cells.push('"' + td.textContent.trim() + '"');
            });
            csv.push(cells.join(','));
        });

        // Download
        const csvContent = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv.join('\n'));
        const link = document.createElement('a');
        link.setAttribute('href', csvContent);
        link.setAttribute('download', 'Rekap_Absensi_Guru_<?= date('Y-m') ?>.csv');
        link.click();
    }
</script>
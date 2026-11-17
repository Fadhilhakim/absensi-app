<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi Bulanan</title>
    <link rel="stylesheet" href="css/dashboard/download_recap.css">
    <style>
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-wrapper">
            <!-- LOGO NORMAL -->
            <img src="logo_sekolah/logo_sekolah.svg" class="logo normal">
            <!-- LOGO INVERT -->
            <img src="logo_sekolah/logo_sekolah.svg" class="logo invert">
        </div>
        <br><br><br><br><br><br><br><br><br>

        <?php
        // Data dummy untuk bulan Desember 2025
        $bulanTampil = "Desember 2025";
        $tahun = 2025;
        $bulan = 12;
        $daftarTanggal = [];
        for ($day = 1; $day <= 31; $day++) {
            $daftarTanggal[] = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
        }
        $totalHariSekolah = count($daftarTanggal);

        // Array nama siswa Indonesia (40 siswa)
        $namaSiswa = [
            "Ahmad Fauzi", "Siti Nurhaliza", "Budi Santoso", "Dewi Sari", "Eko Prasetyo",
            "Fitriani Lestari", "Gunawan Setiawan", "Hani Susanti", "Irfan Maulana", "Julianti Rahayu",
            "Kurniawan Adi", "Lina Marlina", "Muhammad Rizki", "Novi Andriani", "Oka Saputra",
            "Putri Ayu", "Rudi Hartono", "Sari Dewi", "Taufik Hidayat", "Umi Kalsum",
            "Vina Sari", "Wahyu Nugroho", "Yuniarti Sari", "Zainal Abidin", "Ani Suryani",
            "Bambang Sutrisno", "Citra Lestari", "Dedi Kurniawan", "Eni Fitriani", "Fajar Ramadhan",
            "Gita Permata", "Hendra Wijaya", "Indah Sari", "Joko Susilo", "Kartika Dewi",
            "Lutfi Hakim", "Maya Sari", "Nanda Putra", "Oki Setiawan", "Puji Lestari"
        ];

        // Fungsi untuk generate NISN (misalnya mulai dari 1000001)
        function generateNISN($index) {
            return 1000000 + $index + 1;
        }

        // Fungsi untuk generate absensi acak per siswa
        function generateAbsensi($daftarTanggal) {
            $absensi = [];
            foreach ($daftarTanggal as $tgl) {
                $rand = rand(1, 100);
                if ($rand <= 80) {
                    $status = 'Hadir';
                    $keterangan = (rand(1, 10) <= 7) ? 'Tepat Waktu' : 'Terlambat';
                } elseif ($rand <= 85) {
                    $status = 'Sakit';
                    $keterangan = null;
                } elseif ($rand <= 90) {
                    $status = 'Izin';
                    $keterangan = null;
                } else {
                    $status = 'Alpha';
                    $keterangan = null;
                }
                $absensi[$tgl] = ['status' => $status, 'keterangan' => $keterangan];
            }
            return $absensi;
        }

        // Buat data siswa
        $siswaData = [];
        foreach ($namaSiswa as $index => $nama) {
            $siswaData[] = [
                'nisn' => generateNISN($index),
                'nama' => $nama,
                'absensi_by_date' => generateAbsensi($daftarTanggal)
            ];
        }

        // Kelompokkan data (misalnya satu kelas)
        $groupedData = [
            'Kelas X A' => $siswaData
        ];

        // Cek jika ada data
        $absensiData = !empty($groupedData);
        ?>

        <?php if ($absensiData): ?>
            <?php foreach ($groupedData as $kelas => $siswaData): ?>
                <h3 style="text-align: center;"><?= htmlspecialchars($kelas) ?></h3>
                <br>
                <table class="recap-table">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">NISN</th>
                            <th rowspan="2">NAMA SISWA</th>
                            <?php foreach ($daftarTanggal as $tgl): ?>
                                <th style="font-size: 6pt; width:15px">
                                    <?= date('d', strtotime($tgl)) ?>
                                </th>
                            <?php endforeach; ?>
                            <th rowspan="2">Hadir</th>
                            <th rowspan="2">Sakit</th>
                            <th rowspan="2">Izin</th>
                            <th rowspan="2">Alpha</th>
                            <th rowspan="2">Terlambat</th>
                        </tr>
                        <tr>
                            <?php
                                $daysMap = [
                                    'Monday' => 'Sen',
                                    'Tuesday' => 'Sel',
                                    'Wednesday' => 'Rab',
                                    'Thursday' => 'Kam',
                                    'Friday' => 'Jum',
                                    'Saturday' => 'Sab',
                                    'Sunday' => 'Min'
                                ];
                            ?>
                            <?php foreach ($daftarTanggal as $tgl): ?>
                                <th style="font-weight: normal; font-size: 5px;">
                                    <?= $daysMap[date('l', strtotime($tgl))] ?? date('D', strtotime($tgl)) ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($siswaData as $siswa): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($siswa['nisn']) ?></td>
                                <td class="student-name" style="text-align: left; padding-left: 3px"><?= htmlspecialchars($siswa['nama']) ?></td>
                                <?php
                                    $countH = 0; $countS = 0; $countI = 0; $countA = 0;
                                    $countTepat = 0; $countTerlambat = 0; $countTidakAbsen = 0;
                                    foreach ($daftarTanggal as $tgl): 
                                ?>
                                    <td>
                                        <?php
                                            $statusTgl = $siswa['absensi_by_date'][$tgl] ?? null;
                                            if ($statusTgl):
                                                $status = $statusTgl['status'];
                                                $keterangan = $statusTgl['keterangan'];
                                                
                                                if ($status === 'Hadir') { $countH++; }
                                                elseif ($status === 'Sakit') { $countS++; }
                                                elseif ($status === 'Izin') { $countI++; }
                                                elseif ($status === 'Alpha') { $countA++; }
                                                
                                                if ($keterangan === 'Tepat Waktu') { $countTepat++; }
                                                elseif ($keterangan === 'Terlambat') { $countTerlambat++; }
                                                else { $countTidakAbsen++; }
                                        ?>
                                            <span class="status-<?= strtolower($status) === 'hadir' ? 'hadir' : (strtolower($status) === 'sakit' ? 'sakit' : (strtolower($status) === 'izin' ? 'izin' : 'alpha')) ?>">
                                                <?= strtoupper($status[0]) ?>
                                            </span>
                                        <?php else: ?>
                                            <span>-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <td><?= $countH ?></td>
                                <td><?= $countS ?></td>
                                <td><?= $countI ?></td>
                                <td><?= $countA ?></td>
                                <td><?= $countTerlambat ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="summary-row">
                            <td colspan="3" class="summary-label">TOTAL</td>
                            <?php
                                $summaryByDate = [];
                                foreach ($daftarTanggal as $tgl) {
                                    $summaryByDate[$tgl] = ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0, 'T' => 0, 'TL' => 0];
                                }
                                
                                foreach ($siswaData as $siswa) {
                                    foreach ($daftarTanggal as $tgl) {
                                        $statusTgl = $siswa['absensi_by_date'][$tgl] ?? null;
                                        if ($statusTgl) {
                                            $status = $statusTgl['status'];
                                            $keterangan = $statusTgl['keterangan'];
                                            
                                            if ($status === 'Hadir') { $summaryByDate[$tgl]['H']++; }
                                            elseif ($status === 'Sakit') { $summaryByDate[$tgl]['S']++; }
                                            elseif ($status === 'Izin') { $summaryByDate[$tgl]['I']++; }
                                            elseif ($status === 'Alpha') { $summaryByDate[$tgl]['A']++; }
                                            
                                            if ($keterangan === 'Tepat Waktu') { $summaryByDate[$tgl]['T']++; }
                                            elseif ($keterangan === 'Terlambat') { $summaryByDate[$tgl]['TL']++; }
                                        }
                                    }
                                }
                            ?>
                            <?php foreach ($daftarTanggal as $tgl): ?>
                                <td style="font-size: 5pt;">
                                    H:<?= $summaryByDate[$tgl]['H'] ?> |
                                    T:<?= $summaryByDate[$tgl]['T'] ?>
                                </td>
                            <?php endforeach; ?>
                            <td colspan="5"></td>
                        </tr>
                    </tbody>
                </table>
            <?php endforeach; ?>
            <div class="info-section">
                <p><strong>Periode:</strong> <?= htmlspecialchars($bulanTampil) ?> | </p>
                <p><strong>Total Hari Sekolah:</strong> <?= $totalHariSekolah ?> hari | </p>
                <p><strong>Data dihasilkan pada:</strong> <?= date('d F Y H:i:s') ?></p>
            </div>
            <div></div>
        <?php else: ?>
            <div class="no-data">
                <p>Tidak ada data absensi untuk periode yang dipilih.</p>
                <p>Silakan kembali dan pilih bulan dan kelas yang berbeda.</p>
            </div>
        <?php endif; ?>
    </div>
    <button class="print-button">🖨️ CETAK / PRINT</button>
    <script>
        document.querySelector('.print-button').addEventListener('click', function() {
            window.print();
        });

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>
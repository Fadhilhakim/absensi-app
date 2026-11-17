<?php
// Izinkan akses dari berbagai sumber (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

// Pastikan variabel penting sudah ada
if (!isset($data) || !isset($dataSiswa) || !isset($tanggalHariIni)) {
    die("Data tidak lengkap untuk menampilkan kartu siswa.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Identitas Siswa</title>
    <?php include 'favicon.php'; ?>

    <!-- JsBarcode -->
    <script src="/absensi/js/JsBarcode.code128.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="/absensi/css/kartu_kelas.css">

    <!-- Non-cache meta -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
</head>

<body>


    <div class="messages">
        <div class="overlay-back"></div>
        <h3>Preview Kartu</h3>
        <p>
            <span>Download kartu depan :
                <button id="download">Download PDF</button></span>
            <span>Download kartu belakang :
                <button id="downloadd">Download PDF</button></span>
        </p>
        <p class='waiting_message'></p>


    </div>

    <br><br><br><br><br>

    <?php
    $totalSiswa = count($dataSiswa);
    $perSheet = 9;
    $jumlahSheet = ceil($totalSiswa / $perSheet);

    for ($i = 0; $i < $jumlahSheet; $i++):
        $awal = $i * $perSheet;
        $siswaBatch = array_slice($dataSiswa, $awal, $perSheet);
        ?>
        <div class="sheet">
            <?php foreach ($siswaBatch as $siswa): ?>
                <div class="card">

                    <div class="overlay-text"></div>
                    <i id="date">dibuat pada: <br><b><?= $tanggalHariIni; ?></b></i>
                    <div class="header">
                        <div class="heading" style="margin-top: -3mm; margin-left: -3mm; display:block; width: 85mm; height: 25mm;">   
                            <img src="logo_sekolah/<?= $data['logo'] ?>" alt="Lambang Sekolah" class="logo" style="border: none; all:revert; width: 100%; padding: 0;">
                        </div>
                    </div>
                    <p class="ks">KARTU SISWA</p>
                    <div class="details">
                        <img src="uploads/<?= htmlspecialchars($siswa['foto']); ?>" alt="Foto siswa"
                            class="foto" crossorigin="anonymous">

                        <table>
                            <tr>
                                <td><strong>NISN</strong></td>
                                <td>:</td>
                                <td><?= htmlspecialchars($siswa['nisn']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nama</strong></td>
                                <td>:</td>
                                <td><strong><?= htmlspecialchars($siswa['nama']); ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Lahir</strong></td>
                                <td>:</td>
                                <td><?= htmlspecialchars($siswa['tanggal_lahir']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kelas</strong></td>
                                <td>:</td>
                                <td><?= htmlspecialchars($siswa['nama_kelas']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>:</td>
                                <td><?= htmlspecialchars($siswa['alamat']); ?></td>
                            </tr>
                        </table>

                        <div class="barcode">
                            <svg class="barcode-item" jsbarcode-format="code128"
                                jsbarcode-value="<?= htmlspecialchars($siswa['nisn']); ?>" jsbarcode-width="1"
                                jsbarcode-height="25" jsbarcode-displayvalue="false" jsbarcode-margin="0">
                            </svg>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endfor; ?>

    <br>
    <h2 style="text-align:center;">HALAMAN BELAKANG</h2>

    <div class="sheet-back">
        <?php for ($j = 1; $j <= 9; $j++) { ?>
            <div class="card">
                <div class="overlay-text"></div>
                <div class="header">
                    <div class="heading">
                        <div class="head-text">
                            <h1 style="padding-top: 10px;">TATA TERTIB SEKOLAH</h1>
                        </div>
                    </div>
                </div>
                <div class="details">
                    <p class="tata-tertib">
                        <?php $i = 0;
                        foreach ($tataTertibList as $i => $tertib): ?>
                            <?php $i++;
                            echo $i . " " . htmlspecialchars($tertib) . "<br>" ?>
                        <?php endforeach; ?>
                    </p>
                    <br>
                    <p style="line-height: 0.9; text-align: right; width: 90%;">
                        Kepala Sekolah:
                        <br><br>
                        <br><br>
                        <strong style="text-decoration: underline; margin-bottom: 2mm;"><?= $kepsek['nama'] ?></strong><br>
                        <span style="font-size: smaller;">NIP: <?= $kepsek['nip']; ?> </span>
                    </p>
                </div>
            </div>
        <?php } ?>
    </div>

    <br>

    <!-- jsPDF dan html2canvas -->
    <script src="js/html2canvas.min.js"></script>
    <script src="js/jspdf.umd.min.js"></script>

    <script>
        window.jsPDF = window.jspdf.jsPDF;
        JsBarcode(".barcode-item").init();

        const waitingMessage = document.querySelector('.waiting_message');
        let dotCount = 0;
        let interval;

        function startWaitingAnimation() {
            const baseText = "Tunggu beberapa detik";

            waitingMessage.innerHTML = `
            <div class="progress-container"><div class="progress-bar"></div></div>
            <strong>UNDUHAN DIMULAI</strong><br>
            <span id="loadingDots">${baseText}.</span>
            <p>Jika unduhan tak kunjung dimulai, tutup tab ini lalu ulangi proses yang sama.</p>
            `;

            interval = setInterval(() => {
                dotCount = (dotCount % 3) + 1;
                let dots = ".".repeat(dotCount);

                const dotsEl = document.getElementById("loadingDots");
                if (dotsEl) {
                    dotsEl.textContent = `${baseText}${dots}`;
                }

            }, 500);
        }

        function stopWaitingAnimation() {
            clearInterval(interval);
            dotCount = 0;
            waitingMessage.innerHTML = "";
        }


        function setProgress(percent) {
            const container = document.querySelector('.progress-container');
            const bar = document.querySelector('.progress-bar');

            container.style.display = "block";
            bar.style.width = percent + "%";
        }

        function resetProgress() {
            const container = document.querySelector('.progress-container');
            const bar = document.querySelector('.progress-bar');

            bar.style.width = "0%";
            container.style.display = "none";
        }


        async function generatePDF(selector, filename) {
            const sheets = document.querySelectorAll(selector);
            const total = sheets.length;
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'landscape',
                unit: 'mm',
                format: 'a4'
            });

            for (let i = 0; i < total; i++) {
                let percentage = Math.round(((i + 1) / total) * 100);
                setProgress(percentage);

                const canvas = await html2canvas(sheets[i], {
                    scale: 4,
                    useCORS: true,
                    logging: false
                });
                const imgData = canvas.toDataURL('image/png');
                const pdfWidth = doc.internal.pageSize.getWidth();
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                if (i > 0) doc.addPage();
                doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
            }

            doc.save(filename);

            resetProgress();
        }

        document.getElementById('download').addEventListener('click', async () => {
            startWaitingAnimation();
            await generatePDF('.sheet', 'kartu-siswa-depan.pdf');
            resetProgress(); 
            stopWaitingAnimation();
        });

        document.getElementById('downloadd').addEventListener('click', async () => {
            startWaitingAnimation();
            await generatePDF('.sheet-back', 'kartu-siswa-belakang.pdf');
            resetProgress(); 
            stopWaitingAnimation();
        });
    </script>


</body>

</html>
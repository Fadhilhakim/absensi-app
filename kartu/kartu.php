<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Identitas Siswa</title>
    <script src="js/JsBarcode.code128.min.js"></script>
    <?php include 'favicon.php'; ?>
    <link rel="stylesheet" href="css/kartu.css">
</head>

<body>
    <div class="card-container">
        <div class="card">

            <div class="overlay-text"></div>
            <i id="date">dibuat pada: <br><b><?= $tanggalHariIni; ?></b></i>
            <div class="header">
                <div class="heading" style="margin-top: -3mm; margin-left: -3mm; display:block; width: 87mm; height: 25mm;">   
                    <img src="logo_sekolah/<?= $data['logo'] ?>" alt="Lambang Sekolah" class="logo" style="border: none; all:revert; width: 100%; padding: 0;">
                </div>
            </div>
            <p class="ks">KARTU SISWA</p>
            <div class="details">

                <img src="uploads/<?= $dataSiswa['foto'] ?>" alt="siswa" class="foto">
                <table>
                    <tr>
                        <td><strong>NISN:</strong></td>
                        <td>:</td>
                        <td><?= $dataSiswa['nisn'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>:</td>
                        <td><strong><?= $dataSiswa['nama'] ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Lahir:</strong></td>
                        <td>:</td>
                        <td><?= $dataSiswa['tanggal_lahir'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Kelas:</strong></td>
                        <td>:</td>
                        <td><?= $dataSiswa['kelas'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td>:</td>
                        <td><?= $dataSiswa['alamat'] ?></td>
                    </tr>
                </table>


                <div class="barcode">
                    <svg id="barcode" jsbarcode-format="code128" jsbarcode-value="<?= $dataSiswa['nisn'] ?>"
                        jsbarcode-width="1" jsbarcode-height="25" jsbarcode-displayvalue="false" jsbarcode-margin="0">
                    </svg>
                </div>
            </div>
        </div>

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
    </div>

    <script src="js/html2pdf.bundle.min.js"></script>
    <script>
        JsBarcode("svg").init();

        window.onload = function() {
            const element = document.body;
            const opt = {
                margin: [0, 0, 0, 0],
                filename: 'kartu siswa - <?= $dataSiswa['nama'] ?>.pdf',
                image: { type: 'jpeg', quality: 1 },
                html2canvas: {
                    scale: 4, // kualitas tinggi
                    useCORS: true,
                    logging: false,
                    windowWidth: document.body.scrollWidth,
                    windowHeight: document.body.scrollHeight
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A4',
                    orientation: 'portrait',
                    precision: 16
                }
            };

            // Tunggu semua font ter-load dulu
            document.fonts.ready.then(() => {
                html2pdf()
                    .set(opt)
                    .from(element)
                    .save()
                    .then(() => {
                        // Setelah PDF selesai di-download, tutup tab
                        setTimeout(() => {
                            window.close();
                            // Kembalikan fokus ke halaman sebelumnya
                            if (window.opener && !window.opener.closed) {
                                window.opener.focus();
                            }
                        }, 1000); // tunggu 1 detik biar proses download stabil
                    });
            });
        };
    </script>

</body>

</html>
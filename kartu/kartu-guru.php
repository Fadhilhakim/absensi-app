<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Identitas Guru</title>
    <?php include 'favicon.php'; ?>
    <script src="js/JsBarcode.code128.min.js"></script>
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
            <p class="ks">KARTU GURU</p>
            <div class="details">

                <?php if (!empty($guruData['foto']) && file_exists('uploads/' . $guruData['foto'])): ?>
                    <img src="uploads/<?= $guruData['foto'] ?>" alt="guru" class="foto">
                <?php else: ?>
                    <div style="width: 100px; height: 120px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; border-radius: 4px; color: #6b7280; font-size: 40px;">👤</div>
                <?php endif; ?>
                
                <table>
                    <tr>
                        <td><strong>NIP:</strong></td>
                        <td>:</td>
                        <td><?= htmlspecialchars($guruData['nip']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>:</td>
                        <td><strong><?= htmlspecialchars($guruData['nama']) ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>Jabatan:</strong></td>
                        <td>:</td>
                        <td><?= htmlspecialchars($guruData['jabatan']) ?></td>
                    </tr>
                </table>


                <div class="barcode">
                    <svg id="barcode" jsbarcode-format="code128" jsbarcode-value="<?= htmlspecialchars($guruData['nip']) ?>"
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
                    <strong style="text-decoration: underline; margin-bottom: 2mm;"><?= htmlspecialchars($kepsek['nama'] ?? 'Kepala Sekolah') ?></strong><br>
                    <span style="font-size: smaller;">NIP: <?= htmlspecialchars($kepsek['nip'] ?? '') ?> </span>
                </p>
            </div>
        </div>
    </div>

    <script src="js/html2pdf.bundle.min.js"></script>
    <script>
        JsBarcode("svg").init();

        window.addEventListener('load', function() {
            window.print();
            
            // Listen to print dialog close/finish
            window.addEventListener('afterprint', function() {
                window.close();
            });
        });
    </script>
</body>

</html>

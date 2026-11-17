<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Kartu Identitas Siswa</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            background: #fff;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            padding: 10mm;
            box-sizing: border-box;
        }

        .sheet {
            height: 210mm;
            width: 297mm;
            box-sizing: border-box;
            margin: auto;
            display: grid;
            padding: 10mm;
            grid-template-rows: repeat(3, 57mm);
            grid-template-columns: repeat(3, 87mm);
            align-content: start;
            gap: 8mm;
            page-break-after: always;
            overflow: hidden;
            background: #fff;

            /* transform: rotate(90deg); */
        }

        .card {
            height: 55mm;
            width: 90mm;
            background-color: rgb(238, 246, 255);
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            position: relative;
        }

        .header {
            border-radius: 2mm 2mm 0 0;
            text-align: center;
            margin-bottom: 5mm;
            background-color: blue;
            width: 90mm;
            height: 17mm;
        }

        .heading {
            display: flex;
            padding: 3mm 5mm 1mm 5mm;
            color: white;
            text-align: center;
            position: relative;
            margin: auto;
            width: 80mm;
        }

        .head-text {
            text-transform: uppercase;
            text-align: center;
            width: 100%;
        }

        .head-text h1 {
            font-size: 10pt;
            line-height: 0.4;
        }

        .head-text p {
            font-size: 6pt;
            text-transform: uppercase;
            text-align: center;
        }

        .details {
            flex-grow: 1;
            font-size: 6pt;
            line-height: 0.8;
            margin-left: 29mm;
            margin-top: 1mm;
        }

        .barcode-back {
            position: absolute;
            bottom: 3mm;
            left: 3mm;
            width: 30mm;
            height: 10mm;
            padding: 5px;
            background-color: white;
            display: inline-block;
            text-align: center;
        }

        svg {
            width: 100%;
            height: 100%;
        }

        .tata-tertib {
            margin-top: -5mm;
            margin-left: -23mm;
            width: 80mm;
            line-height: 1.3;
            font-size: 5pt;
        }

        .overlay-text {
            position: absolute;
            inset: 0;
            font-size: 8pt;
            font-weight: bold;
            color: rgba(0, 0, 0, 0.07);
            line-height: 15px;
            letter-spacing: 6px;
            text-transform: uppercase;
            text-align: center;
            user-select: none;
            overflow: hidden;
            pointer-events: none;
            background: repeating-linear-gradient(45deg,
                    rgba(156, 200, 255, 0.092) 0 20px,
                    transparent 20px 40px);
        }
    </style>
</head>

<body>
    <div class="sheet">

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


    <script>
        JsBarcode("svg").init();
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi</title>
    <?php include 'favicon.php'; ?>
    <link rel="stylesheet" href="css/mainStyles.css">
</head>

<body>

    <?php include __DIR__ . '/layout/kop_sekolah.php'; ?>

    <?php include __DIR__ . '/layout/absensi_form.php'; ?>

    <?php if (!empty($dataSiswa) || !empty($dataGuru)) { ?>

        <div id="clock">
            <p style="position: absolute ; top: 0; right:0; padding: 10px; color: white;" id="date-time"></p>
        </div>

    <?php } ?>

    <p style="text-align: center; font-weight:bold; font-size: 34px; margin-bottom: 20px;">ABSEN <?= $Absensi ?></p>

    <?php if ($isGuru) { ?>
        <?php include __DIR__ . '/layout/absensi_result_guru.php'; ?>
    <?php } else { ?>
        <?php include __DIR__ . '/layout/absensi_result.php'; ?>
    <?php } ?>

    <script src="js/script.js"></script>
</body>

</html>
<?php
// sender.php

// Mengambil nomor dari query string
$nomor = isset($_GET['nomor']) ? htmlspecialchars($_GET['nomor']) : '';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Terkirim</title>
    <script>
        // Mengarahkan kembali ke index.php setelah 5 detik
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 5000);
    </script>
</head>
<body>
    <h1>Pesan Terkirim Whatsapp Orang Tua </h1>
    <p>Pesan dikirim ke  : <?php echo $nomor; ?></p>
    <p>Anda akan diarahkan kembali ke halaman utama dalam 5 detik.</p>
</body>
</html>
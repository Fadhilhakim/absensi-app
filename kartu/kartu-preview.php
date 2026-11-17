    <script src="js/JsBarcode.code128.min.js"></script>
    <link rel="stylesheet" href="css/kartu.css">

    <div class="card-container">
        <div class="card">
        
        <div class="overlay-text"></div>
        <i id="date" style="margin-left: -20px">dibuat pada: <br><b>01 Desember 2025</b></i>
        <div class="header" style="margin-top: -25px; margin-left: -25px;">
            <div class="heading" style="margin-top: -3mm; margin-left: -3mm; display:block; width: 97mm; height: 25mm;">   
                <img src="logo_sekolah/<?= $data['logo'] ?>" alt="Lambang Sekolah" class="logo" style="border: none; all:revert; width: 100%; padding: 0;">
            </div>
        </div>
        <p class="ks" style="margin-top: 10px; margin-bottom: 10px">KARTU SISWA</p>
        <div class="details">
            
            <?php
             $hasPreview = isset($preview) && !empty($preview);
            ?>

<img 
    src="<?= $hasPreview 
        ? 'uploads/' . htmlspecialchars($preview['foto'] ?? 'default.png') 
        : 'img/default.png'; ?>" 
    alt="siswa" 
    class="foto"
>

<table class="html-reset" style="all:revert; margin-left: -30px; margin-top: 10px;">
    <tr style="all:revert;">
        <td style="all:revert;"><strong style="all:revert;">NISN:</strong></td>
        <td style="all:revert;">:</td>
        <td style="all:revert;"><?= $hasPreview ? htmlspecialchars($preview['nisn']) : '-'; ?></td>
    </tr>
    <tr style="all:revert;">
        <td style="all:revert;"><strong style="all:revert;">Nama:</strong></td>
        <td style="all:revert;">:</td>
        <td style="all:revert;"><strong><?= $hasPreview ? htmlspecialchars($preview['nama']) : '-'; ?></strong></td>
    </tr>
    <tr style="all:revert;">
        <td style="all:revert;"><strong style="all:revert;">Tanggal Lahir:</strong></td>
        <td style="all:revert;">:</td>
        <td style="all:revert;"><?= $hasPreview ? htmlspecialchars($preview['tanggal_lahir']) : '-'; ?></td>
    </tr>
    <tr style="all:revert;">
        <td style="all:revert;"><strong style="all:revert;">Kelas:</strong></td>
        <td style="all:revert;">:</td>
        <td style="all:revert;"><?= $hasPreview ? htmlspecialchars($preview['nama_kelas'] ?? '-') : '-'; ?></td>
    </tr>
    <tr style="all:revert;">
        <td style="all:revert;"><strong style="all:revert;">Alamat:</strong></td>
        <td style="all:revert;">:</td>
        <td style="all:revert;"><?= $hasPreview ? htmlspecialchars($preview['alamat'] ?? '-') : '-'; ?></td>
    </tr>
</table>



            <div class="barcode">
                <svg
                    id="barcode"
                    jsbarcode-format="code128"
                    jsbarcode-value="0065623404"
                    jsbarcode-width="1"
                    jsbarcode-height="25"
                    jsbarcode-displayvalue="false"
                    jsbarcode-margin="0">
                </svg>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Inisialisasi JsBarcode
        JsBarcode("svg").init();
    </script>
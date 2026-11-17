<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../../../login.php');
    exit;
}
?>
<link rel="stylesheet" href="css/input.css">

<style>
    #listTertib {
        margin: 10px 0;
        padding-left: 25px;
    }

    #listTertib li {
        margin-bottom: 8px;
    }

    #addTertib {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
    }

    #addTertib:hover {
        background-color: #0056b3;
    }

    .inf-sek {
        background-color: aliceblue;
        width: 100%;
        justify-content: center;
        padding: 20px 20px;
        margin-right: 20px;
        width: 50%;
        height: min-content;
    }

    .nf-sek-a {
        background-color: #89c0fbff;
        padding: 10px;
        margin: 10px 0 10px 0;
    }

    .time-wrapper {
        border: 1px solid #cbd5e1;
        background-color: white;
        border-radius: 6px;
        padding: 5px 8px;
        display: inline-block;
    }

    .time-wrapper input[type="time"] {
        border: none;
        outline: none;
        width: 100%;
        background: transparent;
    }


    .prov-logo-sekolah {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .logo-sekolah {
        background-color: white;
        padding: 10px;
        border-radius: 10px;
        display: block;
        margin: auto;
        justify-content: center;
    }

    #management-sekolah-form {
        all: revert;
    }
</style>

<?php if (isset($_SESSION['error'])) {
    require_once 'error.php';
} ?>
<?php if (isset($_SESSION['success'])) {
    require_once 'success.php';
} ?>

<div class="card">
    <form method="post" id="management-sekolah-form" enctype="multipart/form-data" style="width: 100%;">
        <div class="card-header" style="display:flex; justify-content:space-between">
            <div>
                <h2>🏫 Manajemen Sekolah</h2>
                <p>Kelola dan perbarui informasi data sekolah</p>
            </div>
            <div>
                <input type="submit" class="form-button" value="Simpan Perubahan">
            </div>
        </div>
        <div class="card-content">
            <div style="display: flex;">
                <div class="inf-sek">
                    <label for="foto">Kop Surat Sekolah:</label>
                    <div class="logo-sekolah">
                        <img class="prov-logo-sekolah" id="preview"
                            src="logo_sekolah/<?= $sekolah['logo'] ?>"
                            alt="Preview"
                            style="background-color: #aaaaaaff; border: 2px dashed #e5e7eb; padding:10px">

                        <!-- Catatan Rekomendasi -->
                        <div style="font-size: 13px; color: #374151; margin: 8px 0; line-height: 1.5; background:#f3f4f6; padding:10px; border-radius:6px; font-size: smaller;">
                            <strong>Catatan:</strong><br>
                            • Gambar harus dalam format <strong>SVG</strong> atau <strong>PNG transparan</strong>.<br>
                            • Gambar harus berukuran <strong>210 × 40 mm</strong> <br>
                            • Warna teks harus <strong>putih</strong> agar lebih serasi pada tema aplikasi.<br>
                            • Pastikan gambar tidak pecah, tidak buram, dan memiliki kontras warna yang baik.<br><br>
                            <div style="font-size: smaller;">
                                <strong>Aplikasi yang direkomendasikan untuk membuat SVG:</strong><br>
                                • <em>Inkscape</em> (gratis, open source)<br>
                                • <em>Adobe Illustrator</em><br>
                                • <em>CorelDRAW</em><br>
                            </div>
                        </div>

                        <input type="file" id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
                        <input type="hidden" name="old_photo" id="old_photo" value="<?= htmlspecialchars($sekolah['logo'] ?? '') ?>">
                    </div>

                    <div style="margin-left: 10px; display:flex; justify-content:space-between; align-items:center; gap:10px;">
                        <div style="width: 100%;">
                            <label for="nama_sekolah">Nama Sekolah:</label>
                            <input style="margin-bottom:10px" type="text" name="nama_sekolah" placeholder="Nama Sekolah"
                                value="<?= htmlspecialchars($sekolah['nama_sekolah'] ?? '') ?>" required>

                            <label for="npsn">NPSN:</label>
                            <input style="margin-bottom:10px" type="text" name="npsn" placeholder="NPSN" value="<?= htmlspecialchars($sekolah['npsn'] ?? '') ?>"
                                required>
                        </div>

                        <div class="nf-sek-a" style="height: fit-content;">
                            <label>Jam Operasional Sekolah</label>
                            <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 5px;">
                                <div style="display: flex; align-items: center; gap: 10px;" class="time-wrapper">
                                    <label for="JOS-masuk" style="min-width: 80px;">Masuk:</label>
                                    <input type="time" name="JOS-masuk" id="JOS-masuk"
                                        value="<?= htmlspecialchars($sekolah['jam_masuk'] ?? '') ?>" required>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;" class="time-wrapper">
                                    <label for="JOS-pulang" style="min-width: 80px;">Pulang:</label>
                                    <input type="time" name="JOS-pulang" id="JOS-pulang"
                                        value="<?= htmlspecialchars($sekolah['jam_pulang'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">

                    <div>
                        <label for="alamat">Alamat Sekolah</label>
                        <input type="text" name="alamat" placeholder="Alamat"
                            value="<?= htmlspecialchars($sekolah['alamat'] ?? '') ?>" required>

                        <label for="telp">Telepon Sekolah</label>
                        <input type="text" name="telp" placeholder="Telepon Sekolah"
                            value="<?= htmlspecialchars($sekolah['telp'] ?? '') ?>" required>
                    </div>


                    <div class="">
                        <div style="display:flex; justify-content: space-between; align-items:center;">
                            <label for="tata_tertib">Tata Tertib Sekolah</label>
                            <button type="button" id="addTertib" class="edit-btn">+ Tambah Tata Tertib</button>
                        </div>

                        <ol id="listTertib">
                            <?php foreach ($tataTertibList as $i => $tertib): ?>
                                <li>
                                    <div style="display: flex; gap: 10px;">
                                        <input type="text" name="tata_tertib[]" value="<?= htmlspecialchars($tertib) ?>"
                                            placeholder="Tata tertib ke-<?= $i + 1 ?>">
                                        <button type="button" class="delete-btn" style="width: fit-content; background: white; border: 1px solid red;">❌</button>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>

                </div>
            </div>

    </form>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const list = document.getElementById('listTertib');
            const btnAdd = document.getElementById('addTertib');

            btnAdd.addEventListener('click', function() {
                const count = list.querySelectorAll('li').length;
                if (count >= 7) {
                    alert("Maksimal 7 tata tertib!");
                    return;
                }

                const li = document.createElement('li');
                li.innerHTML = `
            <div style="display: flex; gap: 10px;">
                <input type="text" name="tata_tertib[]" placeholder="Tata tertib ke-${count + 1}">
                <button type="button" class="delete-btn" style="width: fit-content; background: white; border: 1px solid red; min-width:39px">❌</button>
            </div>
        `;
                list.appendChild(li);
            });

            // Delegasi event untuk tombol hapus
            list.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-btn')) {
                    e.target.closest('li').remove();
                }
            });

        });

        document.getElementById('foto').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = "uploads/default.png";
            }
        });
    </script>
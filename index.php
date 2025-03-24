<?php
// index.php

session_start();
include 'conq.php';

// Inisialisasi variabel
$dataSiswa = [];
$statusAbsensi = "";
$bgColor = ""; 

date_default_timezone_set("Asia/Makassar"); // Set zona waktu WITA
$tanggalHariIni = date("Y-m-d"); // Format tanggal hari ini (YYYY-MM-DD)
$waktuSekarang = date("H:i"); // Format jam:menit (misal: 07:34)
$hariSekarang = date("l"); // Mengambil hari dalam bahasa Inggris (Sunday, Monday, etc.)


if ($hariSekarang == "Sunday") {
    $statusAbsensi = "Absen Tidak Valid (Hari Minggu)";
    $bgColor = "gray"; // Warna abu-abu untuk absen tidak valid
} elseif ($waktuSekarang >= "06:30" && $waktuSekarang <= "15:00") {
    $statusAbsensi = "Tepat Waktu";
    $bgColor = "rgb(124, 249, 124)"; // Warna hijau untuk tepat waktu
} else {
    $statusAbsensi = "Terlambat";
    $bgColor = "red"; // Warna merah untuk terlambat
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nisn = $_POST['nisn'];
    $statusKehadiran = "Hadir";

    // Cek apakah siswa sudah absen hari ini
    $cekAbsensi = $conn->prepare("SELECT * FROM absensi WHERE nisn = :nisn AND tanggal = :tanggal");
    $cekAbsensi->bindParam(':nisn', $nisn);
    $cekAbsensi->bindParam(':tanggal', $tanggalHariIni);
    $cekAbsensi->execute();
    
    $stmt = $conn->prepare("SELECT * FROM siswa WHERE nisn = :nisn");
    $stmt->bindParam(':nisn', $nisn);
    $stmt->execute();
    $dataSiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($cekAbsensi->rowCount() > 0) {
        $_SESSION['error'] = "Siswa sudah melakukan absensi hari ini.";
        $_SESSION['dataSiswa'] = $dataSiswa;
        header("Location: index.php"); 
        exit();
    }
    
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['dataSiswa'] = $dataSiswa;
        
        // **INSERT DATA KE TABEL ABSENSI**
        $insertAbsensi = $conn->prepare("INSERT INTO absensi (nisn, tanggal, status, keterangan) VALUES (:nisn, :tanggal, :status, :keterangan)");
        $insertAbsensi->bindParam(':nisn', $nisn);
        $insertAbsensi->bindParam(':tanggal', $tanggalHariIni);
        $insertAbsensi->bindParam(':status', $statusKehadiran);
        $insertAbsensi->bindParam(':keterangan', $statusAbsensi);
        $insertAbsensi->execute();

        $_SESSION['success'] = "Absensi berhasil dicatat!";

        header("Location: index.php"); 
        exit();
    } else {
        $_SESSION['error'] = "Siswa dengan NISN $nisn tidak ditemukan.";
        header("Location: index.php"); 
        exit();
    }
}

// Ambil data siswa dari session jika tersedia
if (isset($_SESSION['dataSiswa'])) {
    $dataSiswa = $_SESSION['dataSiswa'];
    unset($_SESSION['dataSiswa']); // Hapus sesi setelah digunakan
}



// Ambil pesan error jika ada
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']); // Hapus error setelah ditampilkan

// Ambil pesan success jika ada
$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
unset($_SESSION['success']); // Hapus success setelah ditampilkan
?>

<!DOCTYPE html>
<html translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Nomor Berdasarkan ID</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="kop-surat" style="display: flex;">
        <div>
             <img src="img/tutwuri.png" alt="Logo" class="logo">
        </div>
        <div>
            <h2>DINAS PENDIDIKAN KABUPATEN TAKALAR</h2>
            <h1 class="nama-sekolah">UPT SMP NEGERI 1 TAKALAR</h1>
            <p>NPSN 00120101</p>
            <p>Jl. Poros Takalar - Jeneponto Telp (021) 323232132</p>
        </div>
    </nav>
    <hr class="garis">

    <form action="" method="post">
        <label for="nisn">Masukkan NISN Siswa:</label>
        <input type="text" id="nisn" name="nisn" required autofocus>
        <button type="submit">Cari</button>
    </form>

    <br><br><br><br><br><br><br><br>

    <div id="clock">
        <p style="position: absolute ; top: 0; right:0; padding: 10px; " id="date-time"></p>
    </div>
    <br>
    <p style="text-align: center; font-weight:bold; font-size: 34px; margin-bottom: 20px;">ABSEN MASUK</p>
 

    <div class="container">
            <?php foreach ($dataSiswa as $siswa): ?>
                <div class="row">
                    <div class="col">
                        <img class="img" style="width: 300px" src="<?php echo isset($siswa["foto"]) ? htmlspecialchars($siswa["foto"]) : 'default.jpg'; ?>" alt="Foto Siswa" class="foto">
                    </div>
                    <div class="col">
                        <h1><?php echo htmlspecialchars($siswa["nama"] ?? 'Nama Tidak Tersedia'); ?></h1>
                        <table>
                            <tr>
                                <td class="tab">NISN</td> 
                                <td>:</td>
                                <td><?php echo htmlspecialchars($siswa["nisn"] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="tab">Tanggal Lahir</td> 
                                <td>:</td>
                                <td><?php echo htmlspecialchars($siswa["tanggal_lahir"] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="tab">Kelas</td> 
                                <td>:</td>
                                <td><?php echo htmlspecialchars($siswa["kelas"] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="tab">Alamat</td> 
                                <td>:</td>
                                <td><?php echo htmlspecialchars($siswa["alamat"] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="tab">Nama Ibu</td> 
                                <td>:</td>
                                <td><?php echo htmlspecialchars($siswa["nama_ibu"] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="tab">No HP</td> 
                                <td>:</td>
                                <td><?php echo htmlspecialchars($siswa["no_hp_orang_tua"] ?? '-'); ?></td>
                            </tr>
                        </table>



                        <?php if(isset($success)):?>
                            <div class="success" style="background-color: <?php echo $bgColor; ?>;">
                                <strong>Absensi Berhasil</strong>
                                <i><?php echo $waktuSekarang; ?> WITA (<?php echo $statusAbsensi; ?>)</i>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($error)):?>
                            <div class="error">
                                <span style="font-size: 24px"><?php echo $error ?></span>
                            </div>
                        <?php endif; ?>


                    </div>
                    <div class="absensi-container">
                        <h3>Catatan Absensi Siswa</h3>
                        
                        <h4>1. Jadwal Absensi</h4>
                        <ul>
                            <li><strong>📌 Absensi Masuk:</strong> Waktu absensi: <strong>06:30 - 08:00 WITA</strong>. </li>
                            <li><strong>📌 Absensi Pulang:</strong> Waktu absensi: Saat jam pelajaran berakhir (sesuai jadwal).</li>
                        </ul>

                        <h4>2. Sanksi Keterlambatan</h4>
                        <ul>
                            <li><strong>🔴 Terlambat 1 - 2 kali dalam sebulan:</strong> ✅ Teguran lisan dari guru piket atau wali kelas.</li>
                            <li><strong>🔴 Terlambat 3 - 4 kali dalam sebulan:</strong> ✅ Teguran tertulis dan pemberitahuan kepada orang tua/wali siswa.</li>
                            <li><strong>🔴 Terlambat 5 kali atau lebih dalam sebulan:</strong> ✅ Pemanggilan orang tua untuk pembinaan siswa. ✅ Siswa wajib membuat surat pernyataan tidak mengulangi keterlambatan.</li>
                            <li><strong>🔴 Terlambat lebih dari 7 kali dalam sebulan:</strong> ✅ Sanksi lebih lanjut seperti tugas tambahan, skorsing, atau tindakan lain sesuai kebijakan sekolah.</li>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>

    </div>
    <script>
        // Fungsi untuk membuka halaman baru
        function bukaHalaman() {
            window.location.href = 'dashboard.php'; 
        }

        // Event listener untuk mendeteksi penekanan tombol
        document.addEventListener('keydown', function(event) {
            if (event.key === 'f' || event.key === 'F') {
                bukaHalaman();
            }
        });

        window.addEventListener("load", () => {
            clock();
            function clock() {
                const today = new Date();
                const hours = today.getHours();
                const minutes = today.getMinutes();
                const seconds = today.getSeconds();

                const hour = hours < 10 ? "0" + hours : hours;
                const minute = minutes < 10 ? "0" + minutes : minutes;
                const second = seconds < 10 ? "0" + seconds : seconds;

                const ampm = hour >= 12 ? "PM" : "AM";
                const hourTime = hour > 12 ? hour - 12 : hour;
                const time = hourTime + ":" + minute + ":" + second + " " + ampm;

                document.getElementById("date-time").innerHTML = time;
                setTimeout(clock, 1000);
            }
        });
    </script>
</body>
</html>

<?php
session_start();
include 'conq.php'; // Pastikan ini menggunakan PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $nama_ibu = $_POST['nama_ibu'];
    $no_hp_orang_tua = $_POST['no_hp_orang_tua'];

    // Cek apakah file diunggah
    if (!empty($_FILES['foto']['name'])) {
        $targetDir = "uploads/"; // Folder penyimpanan
        $fileName = basename($_FILES['foto']['name']);
        $fileSize = $_FILES['foto']['size'];
        $fileTmpName = $_FILES['foto']['tmp_name'];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validasi ukuran maksimal 2MB
        if ($fileSize > 2 * 1024 * 1024) {
            die("Error: Ukuran file terlalu besar. Maksimum 2MB.");
        }

        // Validasi format file (hanya gambar)
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileType, $allowedTypes)) {
            die("Error: Format file tidak valid. Hanya JPG, JPEG, PNG, atau GIF.");
        }

        // Generate nama file unik
        $newFileName = uniqid("IMG_", true) . "." . $fileType;
        $targetFilePath = $targetDir . $newFileName;

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($fileTmpName, $targetFilePath)) {
            // Simpan ke database (hanya path)
            try {
                $stmt = $conn->prepare("INSERT INTO siswa (nisn, nama, kelas, tanggal_lahir, alamat, nama_ibu, no_hp_orang_tua, foto) 
                                        VALUES (:nisn, :nama, :kelas, :tanggal_lahir, :alamat, :nama_ibu, :no_hp_orang_tua, :foto)");
                $stmt->bindParam(':nisn', $nisn, PDO::PARAM_STR);
                $stmt->bindParam(':nama', $nama, PDO::PARAM_STR);
                $stmt->bindParam(':kelas', $kelas, PDO::PARAM_INT);
                $stmt->bindParam(':tanggal_lahir', $tanggal_lahir, PDO::PARAM_STR);
                $stmt->bindParam(':alamat', $alamat, PDO::PARAM_STR);
                $stmt->bindParam(':nama_ibu', $nama_ibu, PDO::PARAM_STR);
                $stmt->bindParam(':no_hp_orang_tua', $no_hp_orang_tua, PDO::PARAM_STR);
                $stmt->bindParam(':foto', $targetFilePath, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    echo "Data siswa berhasil ditambahkan.";
                } else {
                    echo "Terjadi kesalahan saat menambahkan data.";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            die("Gagal mengunggah foto.");
        }
    } else {
        die("Error: Foto wajib diunggah.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(32, 32, 32);
            margin: 0;
            padding: 20px;
            color: white;
        }

        h1 {
            text-align: center;
        }

        form {
            background-color: black;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1 1 50%; /* Set each group to take up 50% of the width */
            padding: 10px;
            box-sizing: border-box;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            width: 100%; /* Make the submit button full width */
        }

        input[type="submit"]:hover {
            background-color: #4cae4c;
        }

        textarea {
            resize: vertical;
        }
    </style>
</head>
<body>
    <h1>Input Data Siswa</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nisn">NISN:</label>
            <input type="text" id="nisn" name="nisn" required>

            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>

            <label for="kelas">Kelas:</label>
            <input type="number" id="kelas" name="kelas" required>

            <label for="tanggal_lahir">Tanggal Lahir:</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
            <label for="alamat">Alamat:</label>
            <textarea id="alamat" name="alamat" required></textarea>
        </div>

        <div class="form-group">

            <label for="nama_ibu">Nama Ibu:</label>
            <input type="text" id="nama_ibu" name="nama_ibu" required>

            <label for="no_hp_orang_tua">No HP Orang Tua:</label>
            <input type="text" id="no_hp_orang_tua" name="no_hp_orang_tua" required>

            <label for="foto">Foto:</label>
            <input type="file" id="foto" name="foto" accept="image/*">
            <input type="submit" value="Submit">
        </div>

    </form>
</body>
</html>
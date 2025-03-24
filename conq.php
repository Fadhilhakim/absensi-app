<?php
$host = "127.0.0.1";
$username = "root";
$password = "";
$database = "data_siswa";

try {
    $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>

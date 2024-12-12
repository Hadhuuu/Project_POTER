<?php
include('../konek.php'); // Pastikan ../koneksi ke database SQL Server sudah benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id = $_POST['pelanggaranId'];
    $status = $_POST['status'];

    // Query untuk update status pelanggaran
    $sql = "UPDATE pelanggaran SET status = ? WHERE id = ?";
    $params = array($status, $id);

    // Menjalankan query dengan parameter untuk mencegah SQL Injection
    $stmt = sqlsrv_query($conn, $sql, $params);

    // Mengecek apakah query berhasil dieksekusi
    if ($stmt) {
        echo "Status pelanggaran berhasil diupdate!";
    } else {
        echo "Gagal mengupdate status pelanggaran.";
    }
}
?>

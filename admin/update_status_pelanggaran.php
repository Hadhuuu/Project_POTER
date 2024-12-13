<?php
// Include class Database untuk menghubungkan ke database
require_once('../konekOOP.php'); // Pastikan path ke Database.php benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id = $_POST['pelanggaranId'];
    $status = $_POST['status'];

    // Membuat objek Database dan melakukan koneksi
    $db = new Database(); // Anda bisa menyesuaikan parameter koneksi jika diperlukan

    // Query untuk update status pelanggaran
    $sql = "UPDATE pelanggaran SET status = ? WHERE id = ?";
    $params = array($status, $id);

    // Menjalankan query untuk update menggunakan execute (untuk query tanpa hasil)
    if ($db->execute($sql, $params)) {
        echo "Status pelanggaran berhasil diupdate!";
    } else {
        echo "Gagal mengupdate status pelanggaran.";
    }

    // Menutup koneksi setelah selesai
    $db->close();
}
?>

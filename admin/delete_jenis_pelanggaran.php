<?php
session_start();
require_once('../konekOOP.php'); // Pastikan path ke konekOOP.php benar

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if (isset($_POST['id'])) {
    // Mengambil ID jenis pelanggaran yang akan dihapus
    $id = $_POST['id'];
    
    // Membuat objek Database
    $db = new Database();
    
    // Query untuk menghapus data jenis pelanggaran
    $sql = "DELETE FROM jenis_pelanggaran WHERE id = ?";
    $params = array($id);

    // Menjalankan query dan memeriksa apakah berhasil
    if ($db->execute($sql, $params)) {
        echo "Data berhasil dihapus!";
    } else {
        echo "Terjadi kesalahan saat menghapus data.";
    }

    // Menutup koneksi setelah selesai
    $db->close();
}
?>

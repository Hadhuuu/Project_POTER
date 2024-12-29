<?php
session_start();
require_once('../konekOOP.php'); // Pastikan path ke konekOOP.php benar

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil ID yang akan dihapus
    $id = $_POST['id'];
    
    // Membuat objek Database
    $db = new Database();
    
    // Query untuk menghapus data mahasiswa
    $sql = "DELETE FROM mahasiswa WHERE id = ?";
    $params = array($id);

    // Menjalankan query dan memeriksa apakah berhasil
    if ($db->execute($sql, $params)) {
        echo "Mahasiswa berhasil dihapus.";
    } else {
        echo "Gagal menghapus mahasiswa.";
    }

    // Menutup koneksi setelah selesai
    $db->close();
}
?>

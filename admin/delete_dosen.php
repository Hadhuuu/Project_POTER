<?php
session_start();
require_once('../konekOOP.php'); // Pastikan path ke konekOOP.php benar

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil ID dosen yang akan dihapus
    $id = $_POST['id'];

    // Membuat objek Database
    $db = new Database();
    
    // Query untuk menghapus data dosen
    $sql = "DELETE FROM dosen WHERE id = ?";
    $params = array($id);

    // Menjalankan query dan memeriksa apakah berhasil
    if ($db->execute($sql, $params)) {
        echo "Dosen berhasil dihapus.";
    } else {
        echo "Gagal menghapus dosen.";
    }

    // Menutup koneksi setelah selesai
    $db->close();
}
?>

<?php
session_start();
require_once('../konekOOP.php'); // Pastikan path ke Database.php benar

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

header('Content-Type: application/json');

// Pastikan ada data yang dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['ajuBandingId'];
    $keterangan = $_POST['keterangan'];
    $status = $_POST['status'];

    // Validasi input
    if (empty($id) || empty($keterangan) || empty($status)) {
        echo json_encode(["message" => "Data tidak lengkap."]);
        exit();
    }

    // Membuat objek Database dan melakukan koneksi
    $db = new Database();
    
    // Query untuk update data aju banding
    $sql = "UPDATE ajubanding 
            SET keterangan = ?, status = ? 
            WHERE id = ?";
    $params = array($keterangan, $status, $id);

    // Menjalankan query untuk update menggunakan metode execute()
    if ($db->execute($sql, $params)) {
        echo json_encode(["message" => "Data berhasil diperbarui"]);
    } else {
        echo json_encode(["message" => "Terjadi kesalahan saat mengupdate data"]);
    }

    // Menutup koneksi setelah selesai
    $db->close();
} else {
    echo json_encode(["message" => "Metode HTTP tidak valid"]);
}
?>

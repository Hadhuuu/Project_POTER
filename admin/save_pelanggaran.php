<?php
session_start();
require_once('../konekOOP.php'); // Pastikan path ke Database.php benar

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['pelanggaranId'];
    $nama_pelanggaran = $_POST['nama_pelanggaran'];
    $jenis_pelanggaran_id = $_POST['jenis_pelanggaran'];
    $jenis_sanksi_id = $_POST['jenis_sanksi'];

    // Membuat objek Database untuk koneksi
    $db = new Database();

    if (empty($id)) {
        // Tambah pelanggaran
        $sql = "INSERT INTO pelanggaran (nama_pelanggaran, jenis_pelanggaran_id, jenis_sanksi_id) VALUES (?, ?, ?)";
        $params = array($nama_pelanggaran, $jenis_pelanggaran_id, $jenis_sanksi_id);

        // Menjalankan query untuk menambahkan data
        if ($db->execute($sql, $params)) {
            echo "Pelanggaran berhasil ditambahkan.";
        } else {
            echo "Gagal menambahkan pelanggaran.";
        }
    } else {
        // Edit pelanggaran
        $sql = "UPDATE pelanggaran SET nama_pelanggaran = ?, jenis_pelanggaran_id = ?, jenis_sanksi_id = ? WHERE id = ?";
        $params = array($nama_pelanggaran, $jenis_pelanggaran_id, $jenis_sanksi_id, $id);

        // Menjalankan query untuk memperbarui data
        if ($db->execute($sql, $params)) {
            echo "Pelanggaran berhasil diperbarui.";
        } else {
            echo "Gagal memperbarui pelanggaran.";
        }
    }

    // Menutup koneksi setelah selesai
    $db->close();
}
?>

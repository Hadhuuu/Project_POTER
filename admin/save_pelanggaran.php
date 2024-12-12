<?php
session_start();
include('../konek.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')
{
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['pelanggaranId'];
    $nama_pelanggaran = $_POST['nama_pelanggaran'];
    $jenis_pelanggaran_id = $_POST['jenis_pelanggaran'];
    $jenis_sanksi_id = $_POST['jenis_sanksi'];

    if (empty($id)) {
        // Tambah pelanggaran
        $sql = "INSERT INTO pelanggaran (nama_pelanggaran, jenis_pelanggaran_id, jenis_sanksi_id) VALUES (?, ?, ?)";
        $params = array($nama_pelanggaran, $jenis_pelanggaran_id, $jenis_sanksi_id);
        $stmt = sqlsrv_query($conn, $sql, $params);
        echo $stmt ? "Pelanggaran berhasil ditambahkan." : "Gagal menambahkan pelanggaran.";
    } else {
        // Edit pelanggaran
        $sql = "UPDATE pelanggaran SET nama_pelanggaran = ?, jenis_pelanggaran_id = ?, jenis_sanksi_id = ? WHERE id = ?";
        $params = array($nama_pelanggaran, $jenis_pelanggaran_id, $jenis_sanksi_id, $id);
        $stmt = sqlsrv_query($conn, $sql, $params);
        echo $stmt ? "Pelanggaran berhasil diperbarui." : "Gagal memperbarui pelanggaran.";
    }
}
?>
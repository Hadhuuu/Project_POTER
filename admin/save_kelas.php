<?php
session_start();
include('../konek.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['kelasId'];
    $nama_kelas = $_POST['nama_kelas'];

    if (empty($id)) {
        // Tambah kelas
        $sql = "INSERT INTO kelas (nama_kelas) VALUES (?)";
        $params = array($nama_kelas);
        $stmt = sqlsrv_query($conn, $sql, $params);
        echo $stmt ? "Kelas berhasil ditambahkan." : "Gagal menambahkan kelas.";
    } else {
        // Edit kelas
        $sql = "UPDATE kelas SET nama_kelas = ? WHERE id = ?";
        $params = array($nama_kelas, $id);
        $stmt = sqlsrv_query($conn, $sql, $params);
        echo $stmt ? "Kelas berhasil diperbarui." : "Gagal memperbarui kelas.";
    }
}
?>
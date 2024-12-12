<?php
session_start();
include('../konek.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['dosenId'];
    $nidn = $_POST['nidn'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    if (empty($id)) {
        // Tambah dosen
        $sql = "INSERT INTO dosen (nidn, nama, email) VALUES (?, ?, ?)";
        $params = array($nidn, $nama, $email);
        $stmt = sqlsrv_query($conn, $sql, $params);
        echo $stmt ? "Dosen berhasil ditambahkan." : "Gagal menambahkan dosen.";
    } else {
        // Edit dosen
        $sql = "UPDATE dosen SET nidn = ?, nama = ?, email = ? WHERE id = ?";
        $params = array($nidn, $nama, $email, $id);
        $stmt = sqlsrv_query($conn, $sql, $params);
        echo $stmt ? "Dosen berhasil diperbarui." : "Gagal memperbarui dosen.";
    }
}
?>
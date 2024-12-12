<?php
session_start();
include('../konek.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['mahasiswaId'];
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $ttl = $_POST['ttl'];
    $email = $_POST['email'];
    $id_kelas = $_POST['id_kelas'];

    if (empty($id)) {
        // Tambah mahasiswa
        $sql = "INSERT INTO mahasiswa (nim, nama, ttl, email, id_kelas) VALUES (?, ?, ?, ?, ?)";
        $params = array($nim, $nama, $ttl, $email, $id_kelas);
        $stmt = sqlsrv_query($conn, $sql, $params);
        echo $stmt ? "Mahasiswa berhasil ditambahkan." : "Gagal menambahkan mahasiswa.";
    } else {
        // Edit mahasiswa
        $sql = "UPDATE mahasiswa SET nim = ?, nama = ?, ttl = ?, email = ?, id_kelas = ? WHERE id = ?";
        $params = array($nim, $nama, $ttl, $email, $id_kelas, $id);
        $stmt = sqlsrv_query($conn, $sql, $params);
        echo $stmt ? "Mahasiswa berhasil diperbarui." : "Gagal memperbarui mahasiswa.";
    }
}
?>
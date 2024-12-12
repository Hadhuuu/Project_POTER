<?php
session_start();
include('../konek.php');

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['pelanggaranId'];
    $keterangan = $_POST['nama_pelanggaran'];
    $tingkatan = $_POST['tingkatan'];

    if (empty($id)) {
        // Jika ID kosong, berarti menambah data baru
        $sql = "INSERT INTO jenis_pelanggaran (keterangan, tingkatan) VALUES (?, ?)";
        $params = array($keterangan, $tingkatan);
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt) {
            echo "Data berhasil ditambahkan!";
        } else {
            echo "Terjadi kesalahan saat menambahkan data.";
        }
    } else {
        // Jika ID ada, berarti memperbarui data yang sudah ada
        $sql = "UPDATE jenis_pelanggaran SET keterangan = ?, tingkatan = ? WHERE id = ?";
        $params = array($keterangan, $tingkatan, $id);
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt) {
            echo "Data berhasil diperbarui!";
        } else {
            echo "Terjadi kesalahan saat memperbarui data.";
        }
    }
}
?>

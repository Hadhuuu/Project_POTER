<?php
session_start();
include('../konek.php');

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['sanksiId'];
    $keterangan = $_POST['keterangan'];
    $tingkatan = $_POST['tingkatan'];

    if (!empty($id)) {
        // Jika ID ada, berarti memperbarui data yang sudah ada
        $sql = "UPDATE jenis_sanksi SET keterangan = ?, tingkatan = ? WHERE id = ?";
        $params = array($keterangan, $tingkatan, $id);
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt) {
            echo "Data berhasil diperbarui!";
        } else {
            echo "Terjadi kesalahan saat memperbarui data.";
        }
    } else {
        echo "ID tidak valid!";
    }
}
?>

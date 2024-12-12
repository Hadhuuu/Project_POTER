<?php
session_start();
include('../konek.php');

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM jenis_pelanggaran WHERE id = ?";
    $params = array($id);
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt) {
        echo "Data berhasil dihapus!";
    } else {
        echo "Terjadi kesalahan saat menghapus data.";
    }
}
?>

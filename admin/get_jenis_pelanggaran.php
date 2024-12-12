<?php
session_start();
include('../konek.php');

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if (isset($_GET['id'])) {
    // Mengambil data jenis pelanggaran berdasarkan ID
    $id = $_GET['id'];
    $sql = "SELECT * FROM jenis_pelanggaran WHERE id = ?";
    $params = array($id);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    echo json_encode($data);
} else {
    // Mengambil semua data jenis pelanggaran
    $sql = "SELECT * FROM jenis_pelanggaran";
    $stmt = sqlsrv_query($conn, $sql);
    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
}
?>

<?php
session_start();
include('../konek.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

// Mengambil semua data jenis pelanggaran
$sql = "SELECT * FROM jenis_pelanggaran";
$stmt = sqlsrv_query($conn, $sql);
$data = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $data[] = $row;
}
header('Content-Type: application/json');
echo json_encode($data);
?>
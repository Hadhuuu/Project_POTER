<?php
session_start();
include('../konek.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

header('Content-Type: application/json');

// Cek jika ada parameter id untuk mengambil satu data saja
if (isset($_GET['id'])) {
    // Mengambil data aju banding berdasarkan ID
    $id = $_GET['id'];
    $sql = "SELECT aj.id, aj.id_pelanggaran, aj.keterangan, 
                   CONVERT(VARCHAR, aj.tanggal_pengajuan, 120) AS tanggal_pengajuan,
                   aj.status
            FROM ajubanding aj
            WHERE aj.id = ?";
    $params = array($id);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $aju_banding = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    echo json_encode($aju_banding);
} else {
    // Mengambil semua data aju banding
    $sql = "SELECT aj.id, aj.id_pelanggaran, aj.keterangan, 
                   CONVERT(VARCHAR, aj.tanggal_pengajuan, 120) AS tanggal_pengajuan,
                   aj.status
            FROM ajubanding aj";
    $stmt = sqlsrv_query($conn, $sql);
    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
}
?>

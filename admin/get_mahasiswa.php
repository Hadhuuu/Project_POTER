<?php
session_start();
include('../konek.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if (isset($_GET['id'])) {
    // Mengambil data mahasiswa berdasarkan ID
    $id = $_GET['id'];
    $sql = "SELECT m.*, k.nama_kelas, CONVERT(varchar, m.ttl, 23) AS ttl
            FROM mahasiswa m 
            JOIN kelas k ON m.id_kelas = k.id 
            WHERE m.id = ?";
    $params = array($id);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $mahasiswa = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    echo json_encode($mahasiswa);
} else {
    // Mengambil semua data mahasiswa
    $sql = "SELECT m.*, k.nama_kelas, CONVERT(varchar, m.ttl, 23) AS ttl
            FROM mahasiswa m 
            JOIN kelas k ON m.id_kelas = k.id";
    $stmt = sqlsrv_query($conn, $sql);
    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
}
?>

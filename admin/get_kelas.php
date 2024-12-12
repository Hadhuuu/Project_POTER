<?php
session_start();
include('../konek.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if (isset($_GET['id'])) {
    // Mengambil data kelas berdasarkan ID
    $id = $_GET['id'];
    $sql = "SELECT * FROM kelas WHERE id = ?";
    $params = array($id);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $kelas = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    echo json_encode($kelas);
} else {
    // Mengambil semua data kelas
    $sql = "SELECT * FROM kelas";
    $stmt = sqlsrv_query($conn, $sql);
    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
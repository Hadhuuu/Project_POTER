<?php
session_start();
include('../konek.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

$limit = 10; // Menentukan jumlah data per halaman
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // pastikan page adalah integer
$offset = ($page - 1) * $limit; // offset untuk query


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
    // Mengambil data mahasiswa dengan paging
    $sql = "SELECT m.*, k.nama_kelas, CONVERT(varchar, m.ttl, 23) AS ttl
            FROM mahasiswa m 
            JOIN kelas k ON m.id_kelas = k.id
            ORDER BY m.nim
            OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
    $params = array($offset, $limit);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }

    // Mengambil total jumlah mahasiswa untuk menghitung jumlah halaman
    $sqlCount = "SELECT COUNT(*) as total FROM mahasiswa";
    $stmtCount = sqlsrv_query($conn, $sqlCount);
    $count = sqlsrv_fetch_array($stmtCount, SQLSRV_FETCH_ASSOC);
    $totalRows = $count['total'];
    $totalPages = ceil($totalRows / $limit); // Menghitung jumlah halaman

    // Mengembalikan data mahasiswa beserta informasi paging
    header('Content-Type: application/json');
    echo json_encode([
        'data' => $data,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);
}
?>

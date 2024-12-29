<?php
session_start();
include('../konek.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman yang diminta
$offset = ($page - 1) * $limit; // Menghitung offset

if (isset($_GET['id'])) {
    // Mengambil data dosen berdasarkan ID
    $id = $_GET['id'];
    $sql = "SELECT * FROM dosen WHERE id = ?";
    $params = array($id);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $dosen = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    echo json_encode($dosen);
} else {
    // Mengambil data dosen dengan pagination
    $sql = "SELECT * FROM dosen ORDER BY nidn OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
    $params = array($offset, $limit);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }

    // Menghitung total data dosen
    $countSql = "SELECT COUNT(*) AS total FROM dosen";
    $countStmt = sqlsrv_query($conn, $countSql);
    $countRow = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC);
    $totalData = $countRow['total'];
    $totalPages = ceil($totalData / $limit); // Menghitung total halaman

    // Mengirimkan data dan informasi pagination
    header('Content-Type: application/json');
    echo json_encode([
        'data' => $data,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);
}
?>

<?php
session_start();
include('../konek.php');

// Pastikan hanya dosen yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
    header("Location: index.html");
    exit();
}

// Ambil id dosen dari session
$dosen_id = $_SESSION['user_id'];

// Hitung jumlah pelanggaran yang dilaporkan oleh dosen
$query_pelanggaran = "SELECT COUNT(*) AS count FROM pelanggaran WHERE id_pelapor = ?";
$params = array($dosen_id);
$stmt_pelanggaran = sqlsrv_query($conn, $query_pelanggaran, $params);

if (!$stmt_pelanggaran) {
    die(print_r(sqlsrv_errors(), true));  // Jika query gagal, tampilkan error SQL
}

$row = sqlsrv_fetch_array($stmt_pelanggaran, SQLSRV_FETCH_ASSOC);
if (!$row) {
    die("Data pelanggaran tidak ditemukan!");
}

// Kembalikan jumlah pelanggaran yang dilaporkan
echo json_encode(['count' => $row['count']]);
?>

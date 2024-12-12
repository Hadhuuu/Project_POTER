<?php
session_start();
include('../konek.php');

// Pastikan hanya dosen yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
    header("Location: index.html");
    exit();
}

// Ambil data dosen berdasarkan id_dosen dari session
$dosen_id = $_SESSION['user_id']; // Gunakan $_SESSION['user_id'] karena yang digunakan adalah id_dosen

// Query untuk mengambil data dosen berdasarkan id_dosen
$query_dosen = "SELECT * FROM dosen WHERE id = ?";
$params = array($dosen_id);
$stmt_dosen = sqlsrv_query($conn, $query_dosen, $params);

if (!$stmt_dosen) {
    die(print_r(sqlsrv_errors(), true));  // Jika query gagal, tampilkan error SQL
}

$dosen = sqlsrv_fetch_array($stmt_dosen, SQLSRV_FETCH_ASSOC);
if (!$dosen) {
    die("Data dosen tidak ditemukan!");
}

// Query untuk mengambil kelas yang diampu oleh dosen (DPA)
$query_kelas = "SELECT nama_kelas FROM kelas WHERE id_dpa = ?";
$stmt_kelas = sqlsrv_query($conn, $query_kelas, $params);

if (!$stmt_kelas) {
    die(print_r(sqlsrv_errors(), true));  // Jika query gagal, tampilkan error SQL
}

$kelas = [];
while ($row = sqlsrv_fetch_array($stmt_kelas, SQLSRV_FETCH_ASSOC)) {
    $kelas[] = $row['nama_kelas'];
}

if (empty($kelas)) {
    $kelas_str = "Tidak ada kelas yang diampu";
} else {
    $kelas_str = implode(', ', $kelas);
}

// Kembalikan data dosen dan kelas
echo json_encode([
    'nidn' => $dosen['nidn'],
    'nama' => $dosen['nama'],
    'email' => $dosen['email'],
    'kelas' => $kelas_str
]);
?>

<?php
session_start();
include('../konek.php');

// Pastikan hanya dosen yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: index.html");
    exit();
}

// Ambil parameter filter tingkatan dari query string
$tingkatan = isset($_GET['tingkatan']) ? $_GET['tingkatan'] : '';

// Query untuk mengambil data jenis pelanggaran
$sql = "SELECT * FROM jenis_pelanggaran";
$params = [];

if ($tingkatan) {
    $sql .= " WHERE tingkatan = ?";
    $params[] = $tingkatan;
}

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));  // Debugging jika query gagal
}

$jenis_pelanggaran = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $jenis_pelanggaran[] = [
        'tingkatan' => $row['tingkatan'],
        'keterangan' => $row['keterangan']
    ];
}

// Kirim data dalam format JSON
echo json_encode($jenis_pelanggaran);
?>

<?php
session_start();
include('../konek.php');

// Pastikan yang mengakses adalah dosen
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
    header("Location: index.html");
    exit();
}

$dosen_id = $_SESSION['user_id'];  // ID Dosen yang login

// Jika ada filter status
$status = isset($_GET['status']) ? $_GET['status'] : '';

$sql = "SELECT p.*, m.nim, m.nama AS nama_mahasiswa, d.nama AS nama_dosen, 
jp.tingkatan AS tingkatan_pelanggaran, js.keterangan AS sanksi 
FROM pelanggaran p 
JOIN mahasiswa m ON p.id_mahasiswa = m.id 
JOIN dosen d ON p.id_pelapor = d.id
JOIN jenis_pelanggaran jp ON p.tingkatan_pelanggaran = jp.id
JOIN jenis_sanksi js ON p.id_sanksi = js.id
WHERE m.id_kelas = (
SELECT id_kelas FROM kelas WHERE id_dpa = ?
)";


// Menambahkan filter status jika ada
$params = [$dosen_id];
if ($status != '') {
    $sql .= " AND p.status = ?";  // Jika ada filter status, tambahkan ke query
    $params[] = $status;
}

// Eksekusi query
$stmt = sqlsrv_query($conn, $sql, $params);


// Cek apakah ada data yang ditemukan
$data = [];
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Format tanggal sebelum dikirimkan ke frontend
    $tanggal = $row['tanggal'];
    $formattedDate = $tanggal ? $tanggal->format('Y-m-d') : '';

    // Menambahkan formatted date
    $row['tanggal'] = $formattedDate;

    // Menambahkan data ke array
    $data[] = $row;
}

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($data);
?>

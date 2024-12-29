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
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Halaman yang sedang aktif, default halaman 1
$limit = 10;  // Jumlah data per halaman
$offset = ($page - 1) * $limit;  // Menghitung offset untuk query SQL

$sql = "
SELECT p.*, 
       m.nim, 
       m.nama AS nama_mahasiswa, 
       d.nama AS nama_dosen, 
       jp.tingkatan AS tingkatan_pelanggaran, 
       js.keterangan AS sanksi 
FROM pelanggaran p
JOIN mahasiswa m ON p.id_mahasiswa = m.id
JOIN dosen d ON p.id_pelapor = d.id
JOIN jenis_pelanggaran jp ON p.tingkatan_pelanggaran = jp.id
JOIN jenis_sanksi js ON p.id_sanksi = js.id
WHERE m.id_kelas IN (
    SELECT id 
    FROM kelas 
    WHERE id_dpa = ?
)";

// Menambahkan filter status jika ada
$params = [$dosen_id];
if ($status != '') {
    $sql .= " AND p.status = ?";  // Jika ada filter status, tambahkan ke query
    $params[] = $status;
}

// Query untuk mengambil data sesuai dengan halaman
$sql .= " ORDER BY p.tanggal DESC OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
$params[] = $offset;
$params[] = $limit;

// Eksekusi query untuk mendapatkan data pelanggaran
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

// Menghitung jumlah total data untuk paginasi
$totalQuery = "
SELECT COUNT(*) AS total
FROM pelanggaran p
JOIN mahasiswa m ON p.id_mahasiswa = m.id
JOIN dosen d ON p.id_pelapor = d.id
JOIN jenis_pelanggaran jp ON p.tingkatan_pelanggaran = jp.id
JOIN jenis_sanksi js ON p.id_sanksi = js.id
WHERE m.id_kelas IN (
    SELECT id 
    FROM kelas 
    WHERE id_dpa = ?
)";
if ($status != '') {
    $totalQuery .= " AND p.status = ?";
    $params[] = $status;
}

// Eksekusi query untuk menghitung total data
$totalStmt = sqlsrv_query($conn, $totalQuery, $params);
$totalRow = sqlsrv_fetch_array($totalStmt, SQLSRV_FETCH_ASSOC);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);  // Menghitung total halaman

// Menambahkan total halaman ke dalam data
$response = [
    'data' => $data,
    'totalPages' => $totalPages,
    'currentPage' => $page
];

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>

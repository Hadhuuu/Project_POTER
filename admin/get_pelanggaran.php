<?php
session_start();
include('../konek.php');

// Cek jika user bukan admin, arahkan ke halaman login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

// Mengatur jumlah data per halaman
$limit = 10;  // Jumlah data per halaman

// Mengambil parameter halaman dari URL (default halaman 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Mengecek apakah ada parameter 'id' yang diterima
if (isset($_GET['id'])) {
    // Mengambil data pelanggaran berdasarkan ID
    $id = $_GET['id'];
    $sql = "SELECT pel.id, pel.keterangan, pel.status, 
                   mahasiswa.nama AS nama_mahasiswa, 
                   dosen.nama AS nama_dosen, 
                   pel.foto_bukti_pelanggaran, pel.foto_bukti_sanksi, pel.document_sp
            FROM pelanggaran pel
            JOIN mahasiswa ON pel.id_mahasiswa = mahasiswa.id
            JOIN dosen ON pel.id_pelapor = dosen.id
            WHERE pel.id = ?";
    $params = array($id);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $pelanggaran = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    echo json_encode($pelanggaran);
} else {
    // Mengambil data pelanggaran dengan pagination
    $sql = "SELECT pel.id, pel.keterangan, pel.status, 
                   mahasiswa.nama AS nama_mahasiswa, 
                   dosen.nama AS nama_dosen, 
                   pel.foto_bukti_pelanggaran, pel.foto_bukti_sanksi, pel.document_sp
            FROM pelanggaran pel
            JOIN mahasiswa ON pel.id_mahasiswa = mahasiswa.id
            JOIN dosen ON pel.id_pelapor = dosen.id
            ORDER BY pel.id
            OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
    
    $params = array($offset, $limit);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }

    // Menghitung jumlah total data untuk pagination
    $countSql = "SELECT COUNT(*) AS total FROM pelanggaran";
    $countStmt = sqlsrv_query($conn, $countSql);
    $countRow = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC);
    $totalData = $countRow['total'];
    $totalPages = ceil($totalData / $limit);

    header('Content-Type: application/json');
    echo json_encode(['data' => $data, 'totalPages' => $totalPages, 'currentPage' => $page]);
}
?>

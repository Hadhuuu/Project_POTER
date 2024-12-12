<?php
session_start();
include('../konek.php');

// Cek jika user bukan admin, arahkan ke halaman login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

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
    // Mengambil semua data pelanggaran
    $sql = "SELECT pel.id, pel.keterangan, pel.status, 
                   mahasiswa.nama AS nama_mahasiswa, 
                   dosen.nama AS nama_dosen, 
                   pel.foto_bukti_pelanggaran, pel.foto_bukti_sanksi, pel.document_sp
            FROM pelanggaran pel
            JOIN mahasiswa ON pel.id_mahasiswa = mahasiswa.id
            JOIN dosen ON pel.id_pelapor = dosen.id";
    
    $stmt = sqlsrv_query($conn, $sql);
    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
}
?>

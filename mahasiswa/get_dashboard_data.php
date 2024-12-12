<?php
include '../konek.php';

if (isset($_GET['id_mahasiswa'])) {
    $id_mahasiswa = $_GET['id_mahasiswa'];

    // Query untuk mendapatkan data mahasiswa
    $queryMahasiswa = "
        SELECT 
            m.nama, 
            m.nim, 
            FORMAT(m.ttl, 'yyyy-MM-dd') AS ttl, 
            m.email, 
            k.nama_kelas, 
            k.prodi, 
            k.angkatan, 
            d.nama AS nama_dpa
        FROM mahasiswa m
        JOIN kelas k ON m.id_kelas = k.id
        LEFT JOIN dosen d ON k.id_dpa = d.id
        WHERE m.id = ?";
    
    // Query untuk statistik pelanggaran
    $queryStats = "
        SELECT 
            COUNT(*) AS total_pelanggaran,
            SUM(CASE WHEN p.status = 'unresolved' THEN 1 ELSE 0 END) AS unresolved,
            SUM(CASE WHEN p.status = 'resolved' THEN 1 ELSE 0 END) AS resolved
        FROM pelanggaran p
        WHERE p.id_mahasiswa = ?";

    $stmtMahasiswa = sqlsrv_query($conn, $queryMahasiswa, [$id_mahasiswa]);
    $stmtStats = sqlsrv_query($conn, $queryStats, [$id_mahasiswa]);

    $dataMahasiswa = sqlsrv_fetch_array($stmtMahasiswa, SQLSRV_FETCH_ASSOC);
    $dataStats = sqlsrv_fetch_array($stmtStats, SQLSRV_FETCH_ASSOC);

    $response = array_merge($dataMahasiswa, $dataStats);
    echo json_encode($response);
}
?>

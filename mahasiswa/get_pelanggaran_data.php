<?php
include '../konek.php';

if (isset($_GET['id_mahasiswa'])) {
    $id_mahasiswa = $_GET['id_mahasiswa'];

    // Query to get violation data for the student
    $query = "
        SELECT 
            p.id, 
            p.keterangan, 
            FORMAT(p.tanggal, 'yyyy-MM-dd') AS tanggal, 
            d.nama AS dosen_pelapor, 
            jp.tingkatan, 
            p.status, 
            p.foto_bukti_pelanggaran, 
            p.foto_bukti_sanksi, 
            p.document_sp
        FROM pelanggaran p
        JOIN dosen d ON p.id_pelapor = d.id
        JOIN jenis_pelanggaran jp ON p.tingkatan_pelanggaran = jp.id
        WHERE p.id_mahasiswa = ?
    ";

    // Execute the query
    $stmt = sqlsrv_query($conn, $query, [$id_mahasiswa]);

    $pelanggaran_data = [];

    // Fetch all violation data
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $pelanggaran_data[] = $row;
    }

    // Return the data as JSON
    echo json_encode($pelanggaran_data);
}
?>

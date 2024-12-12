<?php
include '../konek.php';

if (isset($_GET['id_mahasiswa'])) {
    $id_mahasiswa = $_GET['id_mahasiswa'];

    // Query to get violation data for the student
    $query = "
        SELECT 
            p.id, 
            p.keterangan
        FROM pelanggaran p
        WHERE p.id_mahasiswa = ? AND p.status = 'unresolved'
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

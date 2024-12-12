<?php
include '../konek.php';

if (isset($_GET['id_mahasiswa'])) {
    $id_mahasiswa = $_GET['id_mahasiswa'];

    // Query to get aju banding history for the student
    $query = "
        SELECT 
            ab.id_pelanggaran,
            ab.keterangan,
            FORMAT(ab.tanggal_pengajuan, 'yyyy-MM-dd') AS tanggal_pengajuan,
            ab.status
        FROM ajubanding ab
        JOIN pelanggaran p ON ab.id_pelanggaran = p.id
        WHERE p.id_mahasiswa = ?
    ";

    $stmt = sqlsrv_query($conn, $query, [$id_mahasiswa]);

    $banding_data = [];

    // Fetch all aju banding history
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $banding_data[] = $row;
    }

    // Return the data as JSON
    echo json_encode($banding_data);
}
?>

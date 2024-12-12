<?php
include '../konek.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pelanggaran = $_POST['pelanggaran_id'];
    $keterangan = $_POST['keterangan'];

    // Insert the aju banding data
    $query = "
        INSERT INTO ajubanding (id_pelanggaran, keterangan)
        VALUES (?, ?)
    ";

    $stmt = sqlsrv_query($conn, $query, [$id_pelanggaran, $keterangan]);

    if ($stmt) {
        echo "Pengajuan banding berhasil!";
    } else {
        echo "Gagal mengajukan banding!";
    }
}
?>

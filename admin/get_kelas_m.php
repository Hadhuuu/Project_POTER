<?php
include('../konek.php');

$sql = "SELECT id, nama_kelas FROM kelas";
$stmt = sqlsrv_query($conn, $sql);

$kelas = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $kelas[] = $row;
}

header('Content-Type: application/json');
echo json_encode($kelas);
?>

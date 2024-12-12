<?php
include('../konek.php');

$type = isset($_GET['type']) ? $_GET['type'] : '';

// Untuk jenis_pelanggaran
if ($type == 'tingkatan_pelanggaran') {
    $query = "SELECT id, keterangan, tingkatan FROM jenis_pelanggaran";
    $result = sqlsrv_query($conn, $query);
    $data = [];
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        // Kembalikan ID, bukan tingkatan
        $data[] = [
            'id' => $row['id'],
            'keterangan' => $row['keterangan'],
            'tingkatan' => $row['tingkatan']
        ];
    }
    echo json_encode($data);
} elseif ($type == 'nim' && isset($_GET['id'])) {
    // Ambil NIM berdasarkan ID mahasiswa
    $idMahasiswa = $_GET['id'];
    $query = "SELECT nim FROM mahasiswa WHERE id = ?";
    $params = array($idMahasiswa);
    $result = sqlsrv_query($conn, $query, $params);
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    echo json_encode(['nim' => $row['nim']]);
} elseif ($type == 'jenis_sanksi') {
    // Ambil data jenis sanksi
    $query = "SELECT id, keterangan, tingkatan FROM jenis_sanksi";
    $result = sqlsrv_query($conn, $query);
    $data = [];
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    // Ambil data mahasiswa
    $query = "SELECT id, nama FROM mahasiswa";
    $result = sqlsrv_query($conn, $query);
    $data = [];
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
}
?>

<?php
include('../konek.php');
session_start();

// Pastikan hanya dosen yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
    header("Location: index.html");
    exit();
}

$id_pelapor = $_SESSION['user_id'];
$tanggal = $_POST['tanggal'];
$id_mahasiswa = $_POST['id_mahasiswa'];
$keterangan = $_POST['keterangan'];
$tingkatan_pelanggaran = $_POST['tingkatan_pelanggaran'];
$foto_bukti_pelanggaran = $_FILES['foto_bukti']['name'];
$foto_bukti_tmp = $_FILES['foto_bukti']['tmp_name'];
$foto_bukti_path = '../uploads/bukti_pelanggaran/' . basename($foto_bukti_pelanggaran);

// Memindahkan file foto bukti pelanggaran
move_uploaded_file($foto_bukti_tmp, $foto_bukti_path);

// Ambil tingkatan pelanggaran berdasarkan ID jenis pelanggaran
$query_tingkatan = "SELECT tingkatan FROM jenis_pelanggaran WHERE id = ?";
$params_tingkatan = array($tingkatan_pelanggaran);
$result_tingkatan = sqlsrv_query($conn, $query_tingkatan, $params_tingkatan);

if ($result_tingkatan === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row_tingkatan = sqlsrv_fetch_array($result_tingkatan, SQLSRV_FETCH_ASSOC);
$tingkatan = $row_tingkatan['tingkatan']; // Misalnya 'III'

// Ambil ID sanksi berdasarkan tingkatan
$query_sanksi = "SELECT id FROM jenis_sanksi WHERE tingkatan = ?";
$params_sanksi = array($tingkatan);
$result_sanksi = sqlsrv_query($conn, $query_sanksi, $params_sanksi);

if ($result_sanksi === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row_sanksi = sqlsrv_fetch_array($result_sanksi, SQLSRV_FETCH_ASSOC);
$id_sanksi = $row_sanksi['id'];



// Menyimpan data pelanggaran
$query = "INSERT INTO pelanggaran (keterangan, tanggal, id_mahasiswa, id_pelapor, tingkatan_pelanggaran, id_sanksi, status, foto_bukti_pelanggaran)
          VALUES (?, ?, ?, ?, ?, ?, 'Unresolved', ?)";

$params = array($keterangan, $tanggal, $id_mahasiswa, $id_pelapor, $tingkatan_pelanggaran, $id_sanksi, $foto_bukti_path);

$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    header('Location: pelanggaran.php?status=success');
}
?>

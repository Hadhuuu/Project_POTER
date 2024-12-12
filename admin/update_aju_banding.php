<?php
session_start();
include('../konek.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

header('Content-Type: application/json');

// Pastikan ada data yang dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['ajuBandingId'];
    $keterangan = $_POST['keterangan'];
    $status = $_POST['status'];

    // Validasi input
    if (empty($id) || empty($keterangan) || empty($status)) {
        echo json_encode(["message" => "Data tidak lengkap."]);
        exit();
    }

    // Query untuk update data aju banding
    $sql = "UPDATE ajubanding 
            SET keterangan = ?, status = ? 
            WHERE id = ?";
    $params = array($keterangan, $status, $id);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        // Tangani error query
        $errors = sqlsrv_errors();
        echo json_encode(["message" => "Terjadi kesalahan: " . $errors[0]['message']]);
    } else {
        echo json_encode(["message" => "Data berhasil diperbarui"]);
    }
} else {
    echo json_encode(["message" => "Metode HTTP tidak valid"]);
}
?>

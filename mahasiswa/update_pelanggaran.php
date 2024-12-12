<?php
include('../konek.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pelanggaran_id = $_POST['pelanggaran_id'];
    $foto_bukti_sanksi = $_FILES['foto_bukti_sanksi']['name'];
    $document_sp = $_FILES['document_sp']['name'];

    if ($foto_bukti_sanksi) {
        move_uploaded_file($_FILES['foto_bukti_sanksi']['tmp_name'], '../uploads/' . $foto_bukti_sanksi);
    }

    if ($document_sp) {
        move_uploaded_file($_FILES['document_sp']['tmp_name'], '../uploads/' . $document_sp);
    }

    $update_query = "UPDATE pelanggaran SET foto_bukti_sanksi = ?, document_sp = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $foto_bukti_sanksi, $document_sp, $pelanggaran_id);
    $stmt->execute();

    echo json_encode(['status' => 'success']);
}
?>

<?php
include '../konek.php';

if (isset($_FILES['document_sp']) && isset($_POST['id_pelanggaran'])) {
    $id_pelanggaran = $_POST['id_pelanggaran'];
    $file = $_FILES['document_sp'];

    // Check for upload errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Define the upload directory
        $uploadDir = '../uploads/';
        $filePath = $uploadDir . basename($file['name']);

        // Move the uploaded file to the server
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Update the database with the new file path
            $query = "UPDATE pelanggaran SET document_sp = ? WHERE id = ?";
            $stmt = sqlsrv_query($conn, $query, [$filePath, $id_pelanggaran]);

            if ($stmt) {
                echo "Document SP berhasil di-upload.";
            } else {
                echo "Gagal mengupdate database.";
            }
        } else {
            echo "Gagal mengupload file.";
        }
    } else {
        echo "Terjadi kesalahan saat meng-upload file.";
    }
}
?>

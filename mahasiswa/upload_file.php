<?php
include '../konek.php';

if (isset($_FILES['file']) && isset($_POST['id_pelanggaran']) && isset($_POST['upload_type'])) {
    $id_pelanggaran = $_POST['id_pelanggaran'];
    $uploadType = $_POST['upload_type'];
    $file = $_FILES['file'];

    // Check for upload errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Define the upload directory based on file type
        $uploadDir = '../uploads/';
        $filePath = $uploadDir . basename($file['name']);

        // Move the uploaded file to the server
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            if ($uploadType === 'foto_bukti_sanksi') {
                // Update database for foto_bukti_sanksi
                $query = "UPDATE pelanggaran SET foto_bukti_sanksi = ? WHERE id = ?";
            } elseif ($uploadType === 'document_sp') {
                // Update database for document_sp
                $query = "UPDATE pelanggaran SET document_sp = ? WHERE id = ?";
            }

            $stmt = sqlsrv_query($conn, $query, [$filePath, $id_pelanggaran]);

            if ($stmt) {
                echo "File berhasil di-upload.";
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

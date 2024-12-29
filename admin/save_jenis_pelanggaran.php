<?php
session_start();
require_once('../konekOOP.php'); // Pastikan path ke Database.php benar

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['pelanggaranId'];
    $keterangan = $_POST['nama_pelanggaran'];
    $tingkatan = $_POST['tingkatan'];

    // Validasi input
    if (empty($keterangan) || empty($tingkatan)) {
        echo "Semua field harus diisi.";
        exit();
    }

    // Membuat objek Database dan melakukan koneksi
    $db = new Database();

    if (empty($id)) {
        // Menambah data baru jika ID kosong
        $sql = "INSERT INTO jenis_pelanggaran (keterangan, tingkatan) VALUES (?, ?)";
        $params = array($keterangan, $tingkatan);

        // Menjalankan query menggunakan metode execute()
        if ($db->execute($sql, $params)) {
            echo "Data berhasil ditambahkan!";
        } else {
            echo "Terjadi kesalahan saat menambahkan data.";
        }
    } else {
        // Memperbarui data jika ID ada
        $sql = "UPDATE jenis_pelanggaran SET keterangan = ?, tingkatan = ? WHERE id = ?";
        $params = array($keterangan, $tingkatan, $id);

        // Menjalankan query menggunakan metode execute()
        if ($db->execute($sql, $params)) {
            echo "Data berhasil diperbarui!";
        } else {
            echo "Terjadi kesalahan saat memperbarui data.";
        }
    }

    // Menutup koneksi setelah selesai
    $db->close();
}
?>

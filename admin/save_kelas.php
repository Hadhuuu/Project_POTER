<?php
session_start();
require_once('../konekOOP.php'); // Pastikan path ke Database.php benar

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['kelasId'];
    $nama_kelas = $_POST['nama_kelas'];

    // Validasi input
    if (empty($nama_kelas)) {
        echo "Nama kelas tidak boleh kosong.";
        exit();
    }

    // Membuat objek Database dan melakukan koneksi
    $db = new Database();

    if (empty($id)) {
        // Menambahkan kelas baru
        $sql = "INSERT INTO kelas (nama_kelas) VALUES (?)";
        $params = array($nama_kelas);

        // Menjalankan query menggunakan metode execute()
        if ($db->execute($sql, $params)) {
            echo "Kelas berhasil ditambahkan.";
        } else {
            echo "Gagal menambahkan kelas.";
        }
    } else {
        // Mengedit kelas yang sudah ada
        $sql = "UPDATE kelas SET nama_kelas = ? WHERE id = ?";
        $params = array($nama_kelas, $id);

        // Menjalankan query menggunakan metode execute()
        if ($db->execute($sql, $params)) {
            echo "Kelas berhasil diperbarui.";
        } else {
            echo "Gagal memperbarui kelas.";
        }
    }

    // Menutup koneksi setelah selesai
    $db->close();
}
?>

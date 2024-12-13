<?php
session_start();
require_once('../konekOOP.php'); // Pastikan path ke Database.php benar

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['mahasiswaId'];
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $ttl = $_POST['ttl'];
    $email = $_POST['email'];
    $id_kelas = $_POST['id_kelas'];

    // Membuat objek Database untuk koneksi
    $db = new Database();

    if (empty($id)) {
        // Tambah mahasiswa
        $sql = "INSERT INTO mahasiswa (nim, nama, ttl, email, id_kelas) VALUES (?, ?, ?, ?, ?)";
        $params = array($nim, $nama, $ttl, $email, $id_kelas);

        // Menjalankan query untuk menambahkan data
        if ($db->execute($sql, $params)) {
            echo "Mahasiswa berhasil ditambahkan.";
        } else {
            echo "Gagal menambahkan mahasiswa.";
        }
    } else {
        // Edit mahasiswa
        $sql = "UPDATE mahasiswa SET nim = ?, nama = ?, ttl = ?, email = ?, id_kelas = ? WHERE id = ?";
        $params = array($nim, $nama, $ttl, $email, $id_kelas, $id);

        // Menjalankan query untuk memperbarui data
        if ($db->execute($sql, $params)) {
            echo "Mahasiswa berhasil diperbarui.";
        } else {
            echo "Gagal memperbarui mahasiswa.";
        }
    }

    // Menutup koneksi setelah selesai
    $db->close();
}
?>

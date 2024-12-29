<?php
session_start();
require_once('../konekOOP.php'); // Pastikan path ke Database.php benar

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['dosenId'];
    $nidn = $_POST['nidn'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    // Validasi input
    if (empty($nidn) || empty($nama) || empty($email)) {
        echo "Semua field harus diisi.";
        exit();
    }

    // Membuat objek Database dan melakukan koneksi
    $db = new Database();

    if (empty($id)) {
        // Menambah dosen baru jika ID kosong
        $sql = "INSERT INTO dosen (nidn, nama, email) VALUES (?, ?, ?)";
        $params = array($nidn, $nama, $email);

        // Menjalankan query menggunakan metode execute()
        if ($db->execute($sql, $params)) {
            echo "Dosen berhasil ditambahkan.";
        } else {
            echo "Gagal menambahkan dosen.";
        }
    } else {
        // Memperbarui data dosen jika ID ada
        $sql = "UPDATE dosen SET nidn = ?, nama = ?, email = ? WHERE id = ?";
        $params = array($nidn, $nama, $email, $id);

        // Menjalankan query menggunakan metode execute()
        if ($db->execute($sql, $params)) {
            echo "Dosen berhasil diperbarui.";
        } else {
            echo "Gagal memperbarui dosen.";
        }
    }

    // Menutup koneksi setelah selesai
    $db->close();
}
?>

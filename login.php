<?php
session_start();
include('konek.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM akun WHERE username = ? AND password = ?";
    $params = array($username, $password);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $_SESSION['role'] = $row['role'];
        $_SESSION['username'] = $row['username'];

        // Cek role dan simpan ID sesuai role
        if ($row['role'] === 'mahasiswa') {
            $_SESSION['user_id'] = $row['id_mahasiswa'];
            echo "mahasiswa";
        } elseif ($row['role'] === 'dosen') {
            $_SESSION['user_id'] = $row['id_dosen']; // Pastikan kolom `id_dosen` ada
            echo "dosen";
        } elseif ($row['role'] === 'admin') {
            echo "admin";
        } else {
            echo "Invalid role";
        }
    } else {
        echo "Invalid credentials";
    }
}
?>

<?php
session_start();
include('../konek.php');

// Pastikan hanya dosen yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
    header("Location: index.html");
    exit();
}

// Ambil data dosen berdasarkan id_dosen dari session
$dosen_id = $_SESSION['user_id'];  // Gunakan $_SESSION['user_id'] karena yang digunakan adalah id_dosen
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    /* Posisikan icon di pojok kanan atas */
    .top-right-icons {
        position: absolute;
        top: 20px;
        right: 20px;
        display: flex;
        gap: 20px;
    }
    .icon-btn {
        background-color: transparent;
        border: none;
        padding: 10px;
        font-size: 24px;
        cursor: pointer;
        transition: transform 0.3s ease-in-out;
    }
    .icon-btn:hover {
        transform: scale(1.2);
    }
    .notification-count {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: red;
        color: white;
        font-size: 12px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

</head>
<body class="bg-gray-100">
    <div class="flex h-screen px-4 py-4">
        <!-- Sidebar -->
        <div class="bg-gradient-to-b from-blue-900 via-blue-700 to-blue-600 text-white w-64 p-6 space-y-6 rounded-2xl shadow-2xl" style="background-image: url('../assets/background.png'); background-size: cover; background-position: center;">
            <div class="text-center">
                <img src="../assets/logo.png" alt="Logo" class="mx-auto mb-4 h-20 w-auto">
                <h1 class="text-3xl font-semibold">POTER</h1>
                <p class="text-sm text-gray-300">Polinema Tata Tertib</p>
            </div>

            <nav>
                <ul class="space-y-4">
                    <li><a href="dashboard.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-tachometer-alt mr-3"></i> Dashboard</a></li>
                    <li><a href="kelas.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-school mr-3"></i> Kelas</a></li>
                    <li><a href="jenis_pelanggaran.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-exclamation-triangle mr-3"></i> Jenis Pelanggaran</a></li>
                    <li><a href="jenis_sanksi.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-gavel mr-3"></i> Jenis Sanksi</a></li>
                    <li><a href="pelanggaran.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-file-alt mr-3"></i> Data Pelanggaran</a></li>
                    <li><a href="laporkan.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-bell mr-3"></i> Laporkan</a></li>
                    <li><a href="logout.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-sign-out-alt mr-3"></i> Logout</a></li>
                </ul>
            </nav>

        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Dashboard Dosen</h2>

            <!-- Profile Section -->
            <div class="bg-white p-8 rounded-lg shadow-md mb-6 flex items-center">
                <img src="../assets/dosenprofile.png" alt="Foto Dosen" class="w-24 h-24 rounded-full mr-6">
                <div>
                    <h3 class="text-xl font-semibold mb-2">Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                    <p><strong>NIDN:</strong> <span id="nidn"></span></p>
                    <p><strong>Nama:</strong> <span id="nama"></span></p>
                    <p><strong>Email:</strong> <span id="email"></span></p>
                    <p><strong>Kelas Diampu:</strong> <span id="kelas"></span></p>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-4">Statistik Pelanggaran yang Dilaporkan</h3>
                <p id="pelanggaran_count">Pelanggaran yang telah dilaporkan: <span class="font-bold" id="count">0</span></p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Ambil data dosen dan tampilkan
        $.ajax({
            url: 'get_dosen_data.php',
            method: 'GET',
            success: function(data) {
                // Pastikan data valid
                try {
                    let dosen = JSON.parse(data);
                    $('#nidn').text(dosen.nidn);
                    $('#nama').text(dosen.nama);
                    $('#email').text(dosen.email);
                    $('#kelas').text(dosen.kelas);
                } catch (e) {
                    console.error("Error parsing JSON", e);
                    alert("Terjadi kesalahan dalam mengambil data dosen.");
                }
            },
            error: function() {
                alert("Gagal mengambil data dosen.");
            }
        });

        // Ambil statistik pelanggaran yang telah dilaporkan
        $.ajax({
            url: 'get_stats.php',
            method: 'GET',
            success: function(data) {
                // Pastikan data valid
                try {
                    let stats = JSON.parse(data);
                    $('#count').text(stats.count);
                } catch (e) {
                    console.error("Error parsing JSON", e);
                    alert("Terjadi kesalahan dalam mengambil data statistik.");
                }
            },
            error: function() {
                alert("Gagal mengambil statistik pelanggaran.");
            }
        });
    });
    </script>

</body>
</html>

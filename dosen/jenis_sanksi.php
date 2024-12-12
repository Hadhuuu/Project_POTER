<?php
session_start();
include('../konek.php');

// Pastikan hanya dosen yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenis Sanksi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Tabel dengan scroll dan efek hover */
        #jenisSanksiTable {
            max-height: 400px;
            overflow-y: auto;
            display: block;
        }
        #jenisSanksiTable tbody tr:hover {
            background-color: #f3f4f6;
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
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Jenis Sanksi</h2>

            <!-- Filter dan Tabel Jenis Sanksi -->
            <div class="bg-white p-8 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-4">Data Jenis Sanksi</h3>

                <!-- Filter -->
                <div class="mb-4">
                    <label for="tingkatanSanksiFilter" class="block text-sm font-semibold text-gray-700">Filter Berdasarkan Tingkatan:</label>
                    <select id="tingkatanSanksiFilter" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="">Semua Tingkatan</option>
                        <option value="I">Tingkatan I</option>
                        <option value="II">Tingkatan II</option>
                        <option value="III">Tingkatan III</option>
                        <option value="IV">Tingkatan IV</option>
                        <option value="V">Tingkatan V</option>
                    </select>
                </div>

                <!-- Tabel Jenis Sanksi -->
                <div id="jenisSanksiTable">
                    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                        <thead>
                            <tr>
                                <th class="px-6 py-3">Tingkatan</th>
                                <th class="px-6 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="jenisSanksiTbody">
                            <!-- Data akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load data jenis sanksi saat halaman dimuat
            loadJenisSanksi();

            // Filter berdasarkan tingkatan
            $('#tingkatanSanksiFilter').change(function() {
                loadJenisSanksi();
            });

            function loadJenisSanksi() {
                let tingkatan = $('#tingkatanSanksiFilter').val();

                $.ajax({
                    url: 'get_jenis_sanksi.php',
                    method: 'GET',
                    data: { tingkatan: tingkatan },  // Kirim parameter filter jika ada
                    dataType: 'json',
                    success: function(data) {
                        $('#jenisSanksiTbody').empty();
                        data.forEach(function(item) {
                            $('#jenisSanksiTbody').append(`
                                <tr>
                                    <td class="px-6 py-3">${item.tingkatan}</td>
                                    <td class="px-6 py-3">${item.keterangan}</td>
                                </tr>
                            `);
                        });
                    },
                    error: function() {
                        alert("Gagal mengambil data jenis sanksi.");
                    }
                });
            }
        });
    </script>
</body>
</html>

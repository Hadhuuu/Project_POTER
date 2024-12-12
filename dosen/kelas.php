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
    <title>Kelas Dosen</title>
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

    /* Scrollable Table */
    .scrollable-table {
        max-height: 755px; /* Adjust the max height as needed */
        overflow-y: auto;
    }

    /* Hover effect for table rows */
    tr:hover {
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
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Kelas yang Diampu</h2>

            <!-- Nama Kelas DPA -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-2">Nama Kelas: <span id="nama_kelas"></span></h3>
                <p><strong>Dosen Pengampu:</strong> <span id="dosen_pengampu"></span></p>
            </div>

            <!-- Tabel Mahasiswa -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6 scrollable-table">
                <h3 class="text-xl font-semibold mb-4">Daftar Mahasiswa</h3>
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">NIM</th>
                            <th class="px-4 py-2 border">Nama</th>
                            <th class="px-4 py-2 border">TTL</th>
                            <th class="px-4 py-2 border">Email</th>
                        </tr>
                    </thead>
                    <tbody id="mahasiswa_tabel">
                        <!-- Data Mahasiswa akan ditampilkan di sini -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Ambil data kelas dan daftar mahasiswa
            $.ajax({
                url: 'get_kelas_data.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    try {
                        console.log(data);  // Debugging untuk melihat data yang diterima

                        let kelas = data;  // Data yang diterima adalah objek JavaScript (bukan string JSON)

                        if (!kelas || !kelas.nama_kelas || !kelas.dosen_pengampu || !kelas.mahasiswa) {
                            alert('Data kelas tidak valid.');
                            return;
                        }

                        // Menampilkan informasi kelas
                        $('#nama_kelas').text(kelas.nama_kelas);
                        $('#dosen_pengampu').text(kelas.dosen_pengampu);

                        // Menampilkan daftar mahasiswa dalam tabel
                        let mahasiswaRows = '';
                        kelas.mahasiswa.forEach(function(mahasiswa) {
                            // Format tanggal TTL menjadi DD-MM-YYYY
                            let ttl = mahasiswa.ttl;
                            let ttlFormatted = formatTanggal(ttl);

                            mahasiswaRows += `<tr>
                                <td class="px-6 py-3">${mahasiswa.nim}</td>
                                <td class="px-6 py-3">${mahasiswa.nama}</td>
                                <td class="px-6 py-3">${ttlFormatted}</td>
                                <td class="px-6 py-3">${mahasiswa.email}</td>
                            </tr>`;
                        });
                        $('#mahasiswa_tabel').html(mahasiswaRows);

                    } catch (e) {
                        console.error("Error parsing JSON", e);
                        alert("Terjadi kesalahan dalam mengambil data kelas.");
                    }
                },
                error: function() {
                    alert("Gagal mengambil data kelas.");
                }
            });

            // Fungsi untuk mengonversi tanggal menjadi format DD-MM-YYYY
            function formatTanggal(ttl) {
                if (!ttl) return "Tidak diketahui";

                let ttlArray = ttl.split('-'); // Format: YYYY-MM-DD
                return ttlArray[2] + '-' + ttlArray[1] + '-' + ttlArray[0];  // Format: DD-MM-YYYY
            }
        });
    </script>

</body>
</html>

<?php
session_start();
include('../konek.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: index.html");
    exit();
}

$id_mahasiswa = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - POTER</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

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
                    <li><a href="dashboard.php" class="flex items-center p-2 rounded-md bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-tachometer-alt mr-3"></i> Dashboard</a></li>
                    <li><a href="pelanggaran_saya.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-exclamation-triangle mr-3"></i> Pelanggaran Saya</a></li>
                    <li><a href="download_suratSP.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-file-download mr-3"></i> Download Surat SP</a></li>
                    <li><a href="aju_banding.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-gavel mr-3"></i> Aju Banding</a></li>
                    <li><a href="jenis_pelanggaran.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-exclamation-triangle mr-3"></i> Jenis Pelanggaran</a></li>
                    <li><a href="jenis_sanksi.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-gavel mr-3"></i> Jenis Sanksi</a></li>
                    <li><a href="logout.php" class="flex items-center p-2 rounded-md bg-red-500 hover:bg-red-600 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-sign-out-alt mr-3"></i> Logout</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-gray-50 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <img src="../assets/studentprofile.png" alt="Mahasiswa Profile" class="w-12 h-12 rounded-full">
                    <h2 id="namaMahasiswa" class="ml-4 text-2xl font-semibold">Mahasiswa</h2>
                </div>
            </div>

            <!-- Data Mahasiswa -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informasi Mahasiswa</h3>
                <div class="space-y-4">
                    <div class="flex">
                        <strong class="w-1/4">NIM</strong>
                        <span id="nimMahasiswa" class="w-3/4">-</span>
                    </div>
                    <div class="flex">
                        <strong class="w-1/4">Email</strong>
                        <span id="emailMahasiswa" class="w-3/4">-</span>
                    </div>
                    <div class="flex">
                        <strong class="w-1/4">Kelas</strong>
                        <span id="kelasMahasiswa" class="w-3/4">-</span>
                    </div>
                    <div class="flex">
                        <strong class="w-1/4">Program Studi</strong>
                        <span id="prodiMahasiswa" class="w-3/4">-</span>
                    </div>
                    <div class="flex">
                        <strong class="w-1/4">Dosen Pembina Akademik</strong>
                        <span id="dpaMahasiswa" class="w-3/4">-</span>
                    </div>
                </div>
            </div>

            <!-- Statistik Pelanggaran -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition transform duration-300 ease-in-out hover:scale-105">
                    <h3 class="text-lg font-semibold text-gray-700">Total Pelanggaran</h3>
                    <p id="totalPelanggaran" class="text-3xl font-bold text-blue-900">0</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition transform duration-300 ease-in-out hover:scale-105">
                    <h3 class="text-lg font-semibold text-gray-700">Pelanggaran Unresolved</h3>
                    <p id="unresolvedPelanggaran" class="text-3xl font-bold text-blue-900">0</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition transform duration-300 ease-in-out hover:scale-105">
                    <h3 class="text-lg font-semibold text-gray-700">Pelanggaran Resolved</h3>
                    <p id="resolvedPelanggaran" class="text-3xl font-bold text-blue-900">0</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Mengambil data dashboard mahasiswa
            $.ajax({
                url: 'get_dashboard_data.php',
                method: 'GET',
                data: { id_mahasiswa: <?php echo $id_mahasiswa; ?> },
                dataType: 'json',
                success: function(data) {
                    $('#namaMahasiswa').text(data.nama);
                    $('#nimMahasiswa').text(data.nim);
                    $('#emailMahasiswa').text(data.email);
                    $('#kelasMahasiswa').text(data.nama_kelas);
                    $('#prodiMahasiswa').text(data.prodi);
                    $('#dpaMahasiswa').text(data.nama_dpa);

                    $('#totalPelanggaran').text(data.total_pelanggaran);
                    $('#unresolvedPelanggaran').text(data.unresolved);
                    $('#resolvedPelanggaran').text(data.resolved);
                }
            });
        });
    </script>
</body>
</html>
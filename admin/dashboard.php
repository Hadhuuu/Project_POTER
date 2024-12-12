<?php
session_start();
include('../konek.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - POTER</title>
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
                <li><a href="mahasiswa.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-user-graduate mr-3"></i> Mahasiswa</a></li>
                <li><a href="dosen.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-chalkboard-teacher mr-3"></i> Dosen</a></li>
                <li><a href="kelas.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-school mr-3"></i> Kelas</a></li>
                <li><a href="jenis_pelanggaran.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-exclamation-triangle mr-3"></i> Jenis Pelanggaran</a></li>
                <li><a href="jenis_sanksi.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-balance-scale mr-3"></i> Jenis Sanksi</a></li>
                <li><a href="pelanggaran.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-file-alt mr-3"></i> Data Pelanggaran</a></li>
                <li><a href="aju_banding.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-gavel mr-3"></i> Aju Banding</a></li>
                <li><a href="logout.php" class="flex items-center p-2 rounded-md bg-red-500 hover:bg-red-600 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-sign-out-alt mr-3"></i> Logout</a></li>
            </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-gray-50 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <img src="../assets/adminprofile.png" alt="Admin Profile" class="w-12 h-12 rounded-full">
                    <h2 class="ml-4 text-2xl font-semibold text-gray-800">Admin</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition transform duration-300 ease-in-out hover:scale-105">
                    <h3 class="text-lg font-semibold text-gray-700">Jumlah Dosen</h3>
                    <p id="jumlahDosen" class="text-3xl font-bold text-blue-900"></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition transform duration-300 ease-in-out hover:scale-105">
                    <h3 class="text-lg font-semibold text-gray-700">Jumlah Mahasiswa</h3>
                    <p id="jumlahMahasiswa" class="text-3xl font-bold text-blue-900"></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition transform duration-300 ease-in-out hover:scale-105">
                    <h3 class="text-lg font-semibold text-gray-700">Jumlah Kelas</h3>
                    <p id="jumlahKelas" class="text-3xl font-bold text-blue-900"></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition transform duration-300 ease-in-out hover:scale-105">
                    <h3 class="text-lg font-semibold text-gray-700">Jumlah Akun</h3>
                    <p id="jumlahAkun" class="text-3xl font-bold text-blue-900"></p>
                </div>
            </div>

            <div class="mt-8">
                <h4 class="text-xl font-semibold text-gray-700">Statistik Pelanggaran Mahasiswa</h4>
                <div class="mt-4 flex gap-8">
                    <!-- Pie Chart -->
                    <div class="w-1/2 bg-white p-6 rounded-lg shadow-md">
                        <canvas id="myChart" width="400" height="400"></canvas>
                    </div>
                    <!-- Line Chart -->
                    <div class="w-1/2 bg-white p-6 rounded-lg shadow-md">
                        <canvas id="lineChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h4 class="text-xl font-semibold text-gray-700">Jumlah Aju Banding</h4>
                <div class="mt-4 bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition transform duration-300 ease-in-out hover:scale-105">
                    <p id="jumlahAjuBanding" class="text-3xl font-bold text-blue-900"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Mengambil data dashboard
            $.ajax({
                url: 'get_dashboard_data.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#jumlahDosen').text(data.jumlahDosen);
                    $('#jumlahMahasiswa').text(data.jumlahMahasiswa);
                    $('#jumlahKelas').text(data.jumlahKelas);
                    $('#jumlahAkun').text(data.jumlahAkun);
                    $('#jumlahAjuBanding').text(data.jumlahAjuBanding);

                    // Pie chart
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ['Mahasiswa dengan Pelanggaran', 'Mahasiswa Tanpa Pelanggaran'],
                            datasets: [{
                                label: 'Statistik Pelanggaran',
                                data: [data.mahasiswaDenganPelanggaran, data.mahasiswaTanpaPelanggaran],
                                backgroundColor: ['#ff4600', '#2980B9'],
                                borderColor: ['#fff', '#fff'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Statistik Pelanggaran Mahasiswa'
                                }
                            }
                        }
                    });

                    // Line chart - Pelanggaran per bulan
                    var months = data.pelanggaranPerBulan.map(function(item) {
                        var date = new Date(item.tahun, item.bulan - 1); // JavaScript month is 0-indexed
                        return date.toLocaleString('default', { month: 'short' }) + ' ' + item.tahun;
                    });

                    var pelanggaranCounts = data.pelanggaranPerBulan.map(function(item) {
                        return item.jumlahPelanggaran;
                    });

                    var maxCount = Math.max(...pelanggaranCounts);
                    var maxValue = maxCount * 2;  // 200% of the maximum value

                    var ctx2 = document.getElementById('lineChart').getContext('2d');
                    var lineChart = new Chart(ctx2, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Jumlah Pelanggaran Per Bulan',
                                data: pelanggaranCounts,
                                fill: false,
                                borderColor: '#2980B9',
                                tension: 0.1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    suggestedMax: maxValue  // Set max value to 200% of the highest data point
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Pelanggaran Mahasiswa per Bulan'
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>

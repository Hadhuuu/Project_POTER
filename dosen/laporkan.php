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
    <title>Laporkan Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                    <li><a href="dashboard.php" class="flex items-center p-2 rounded-md hover:bg-blue-700"><i class="fas fa-tachometer-alt mr-3"></i> Dashboard</a></li>
                    <li><a href="kelas.php" class="flex items-center p-2 rounded-md hover:bg-blue-700"><i class="fas fa-school mr-3"></i> Kelas</a></li>
                    <li><a href="jenis_pelanggaran.php" class="flex items-center p-2 rounded-md hover:bg-blue-700"><i class="fas fa-exclamation-triangle mr-3"></i> Jenis Pelanggaran</a></li>
                    <li><a href="jenis_sanksi.php" class="flex items-center p-2 rounded-md hover:bg-blue-700"><i class="fas fa-gavel mr-3"></i> Jenis Sanksi</a></li>
                    <li><a href="pelanggaran.php" class="flex items-center p-2 rounded-md hover:bg-blue-700"><i class="fas fa-file-alt mr-3"></i> Data Pelanggaran</a></li>
                    <li><a href="laporkan.php" class="flex items-center p-2 rounded-md hover:bg-blue-700"><i class="fas fa-bell mr-3"></i> Laporkan</a></li>
                    <li><a href="logout.php" class="flex items-center p-2 rounded-md hover:bg-blue-700"><i class="fas fa-sign-out-alt mr-3"></i> Logout</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Laporkan Pelanggaran</h2>

            <!-- Form Pelaporan -->
            <form action="save_laporan.php" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow-md mb-6">
                <div class="grid grid-cols-2 gap-6">
                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-semibold text-gray-700">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" value="<?php echo date('Y-m-d'); ?>" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>

                    <!-- Nama Mahasiswa -->
                    <div>
                        <label for="id_mahasiswa" class="block text-sm font-semibold text-gray-700">Nama Mahasiswa</label>
                        <select id="id_mahasiswa" name="id_mahasiswa" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                            <!-- Data Mahasiswa akan dimuat di sini menggunakan AJAX -->
                        </select>
                    </div>

                    <!-- NIM Mahasiswa -->
                    <div>
                        <label for="nim" class="block text-sm font-semibold text-gray-700">NIM Mahasiswa</label>
                        <input type="text" id="nim" name="nim" readonly class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>

                    <!-- Keterangan Pelanggaran -->
                    <div>
                        <label for="keterangan" class="block text-sm font-semibold text-gray-700">Keterangan Pelanggaran</label>
                        <textarea id="keterangan" name="keterangan" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>
                    </div>

                    <!-- Tingkatan Pelanggaran -->
                    <div>
                        <label for="tingkatan_pelanggaran" class="block text-sm font-semibold text-gray-700">Tingkatan Pelanggaran</label>
                        <select id="tingkatan_pelanggaran" name="tingkatan_pelanggaran" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                            <!-- Data Tingkatan Pelanggaran akan dimuat di sini menggunakan AJAX -->
                        </select>
                    </div>

                    <!-- Pilihan Sanksi -->
                    <div>
                        <label for="id_sanksi" class="block text-sm font-semibold text-gray-700">Jenis Sanksi</label>
                        <select id="id_sanksi" name="id_sanksi" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                            <!-- Data Jenis Sanksi akan dimuat di sini menggunakan AJAX -->
                        </select>
                    </div>


                    <!-- Foto Bukti Pelanggaran -->
                    <div>
                        <label for="foto_bukti" class="block text-sm font-semibold text-gray-700">Foto Bukti Pelanggaran</label>
                        <input type="file" id="foto_bukti" name="foto_bukti" accept="image/*" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 text-white p-3 rounded-md w-full">Laporkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Memuat data mahasiswa
            $.ajax({
                url: 'get_data.php',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    data.forEach(function (mahasiswa) {
                        $('#id_mahasiswa').append(`<option value="${mahasiswa.id}">${mahasiswa.nama}</option>`);
                    });
                }
            });

            // Memuat data tingkatan pelanggaran
            $.ajax({
                url: 'get_data.php',
                method: 'GET',
                dataType: 'json',
                data: { type: 'tingkatan_pelanggaran' },
                success: function (data) {
                    data.forEach(function (pelanggaran) {
                        // Pastikan value adalah ID, bukan tingkatan string
                        $('#tingkatan_pelanggaran').append(`<option value="${pelanggaran.id}">${pelanggaran.keterangan} (${pelanggaran.tingkatan})</option>`);
                    });
                }
            });

            // Jika memilih jenis_sanksi, pastikan value adalah ID sanksi
            $.ajax({
                url: 'get_data.php',
                method: 'GET',
                dataType: 'json',
                data: { type: 'jenis_sanksi' },
                success: function (data) {
                    data.forEach(function (sanksi) {
                        // Pastikan value adalah ID sanksi, bukan tingkatan string
                        $('#id_sanksi').append(`<option value="${sanksi.id}">${sanksi.keterangan} (Tingkatan: ${sanksi.tingkatan})</option>`);
                    });
                }
            });


            // Menampilkan NIM berdasarkan mahasiswa yang dipilih
            $('#id_mahasiswa').change(function () {
                var idMahasiswa = $(this).val();
                $.ajax({
                    url: 'get_data.php',
                    method: 'GET',
                    dataType: 'json',
                    data: { type: 'nim', id: idMahasiswa },
                    success: function (data) {
                        $('#nim').val(data.nim);
                    }
                });
            });

            
            
        });
    </script>
</body>
</html>

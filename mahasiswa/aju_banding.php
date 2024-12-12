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
    <title>Aju Banding - POTER</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <li><a href="dashboard.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-tachometer-alt mr-3"></i> Dashboard</a></li>
                    <li><a href="pelanggaran_saya.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-exclamation-triangle mr-3"></i> Pelanggaran Saya</a></li>
                    <li><a href="download_suratSP.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-file-download mr-3"></i> Download Surat SP</a></li>
                    <li><a href="aju_banding.php" class="flex items-center p-2 rounded-md bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-gavel mr-3"></i> Aju Banding</a></li>
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
                    <h2 class="ml-4 text-2xl font-semibold">Aju Banding</h2>
                </div>
            </div>

            <!-- Form Aju Banding -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Form Pengajuan Banding</h3>
                <form id="ajuBandingForm">
                    <div class="mb-4">
                        <label for="pelanggaran_id" class="block text-sm font-medium text-gray-700">Pilih Pelanggaran</label>
                        <select id="pelanggaran_id" name="pelanggaran_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></select>
                    </div>
                    <div class="mb-4">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan Banding</label>
                        <textarea id="keterangan" name="keterangan" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Ajukan Banding</button>
                </form>
            </div>

            <!-- Riwayat Aju Banding -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Riwayat Aju Banding</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600">
                            <th class="px-4 py-2 text-left">ID Pelanggaran</th>
                            <th class="px-4 py-2 text-left">Keterangan</th>
                            <th class="px-4 py-2 text-left">Tanggal Pengajuan</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody id="riwayatAjuBandingList"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Fetch pelanggaran data for dropdown
            $.ajax({
                url: 'get_pelanggaran_for_banding.php',
                method: 'GET',
                data: { id_mahasiswa: <?php echo $id_mahasiswa; ?> },
                dataType: 'json',
                success: function(data) {
                    let pelanggaranOptions = '<option value="">Pilih Pelanggaran</option>';
                    data.forEach(function(pelanggaran) {
                        pelanggaranOptions += `<option value="${pelanggaran.id}">${pelanggaran.keterangan}</option>`;
                    });
                    $('#pelanggaran_id').html(pelanggaranOptions);
                }
            });

            // Handle form submission for aju banding
            $('#ajuBandingForm').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: 'submit_aju_banding.php',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response);
                        $('#ajuBandingForm')[0].reset();
                        loadRiwayatAjuBanding(); // Reload the history table
                    }
                });
            });

            // Fetch riwayat aju banding
            function loadRiwayatAjuBanding() {
                $.ajax({
                    url: 'get_riwayat_aju_banding.php',
                    method: 'GET',
                    data: { id_mahasiswa: <?php echo $id_mahasiswa; ?> },
                    dataType: 'json',
                    success: function(data) {
                        let riwayatHtml = '';
                        data.forEach(function(banding) {
                            let statusClass = banding.status === 'pending' ? 'bg-blue-500' :
                                              (banding.status === 'accepted' ? 'bg-green-500' : 'bg-red-500');
                            riwayatHtml += `
                                <tr>
                                    <td class="px-4 py-2">${banding.id_pelanggaran}</td>
                                    <td class="px-4 py-2">${banding.keterangan}</td>
                                    <td class="px-4 py-2">${banding.tanggal_pengajuan}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-4 py-2 text-white rounded ${statusClass}">${banding.status}</span>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#riwayatAjuBandingList').html(riwayatHtml);
                    }
                });
            }

            // Load riwayat banding on page load
            loadRiwayatAjuBanding();
        });
    </script>
</body>
</html>

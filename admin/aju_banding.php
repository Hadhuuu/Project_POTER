<?php
session_start();
include('../konek.php');

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aju Banding</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .status-box {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            color: white;
            text-transform: capitalize;
        }
        .status-pending { background-color: #1e40af; } /* Blue */
        .status-accepted { background-color: #16a34a; } /* Green */
        .status-rejected { background-color: #dc2626; } /* Red */
    </style>
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
                    <li><a href="mahasiswa.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-user-graduate mr-3"></i> Mahasiswa</a></li>
                    <li><a href="dosen.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-chalkboard-teacher mr-3"></i> Dosen</a></li>
                    <li><a href="kelas.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-school mr-3"></i> Kelas</a></li>
                    <li><a href="jenis_pelanggaran.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-exclamation-triangle mr-3"></i> Jenis Pelanggaran</a></li>
                    <li><a href="jenis_sanksi.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-balance-scale mr-3"></i> Jenis Sanksi</a></li>
                    <li><a href="pelanggaran.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-file-alt mr-3"></i> Data Pelanggaran</a></li>
                    <li><a href="aju_banding.php" class="flex items-center p-2 rounded-md bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-gavel mr-3"></i> Aju Banding</a></li>
                    <li><a href="logout.php" class="flex items-center p-2 rounded-md bg-red-500 hover:bg-red-600 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-sign-out-alt mr-3"></i> Logout</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Aju Banding</h2>
            <table id="ajuBandingTable" class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">ID Pelanggaran</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3">Tanggal Pengajuan</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data aju banding akan dimuat di sini -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk Edit Aju Banding -->
    <div id="ajuBandingModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 id="modalTitle" class="text-xl font-semibold mb-4">Edit Aju Banding</h2>
            <form id="ajuBandingForm">
                <input type="hidden" id="ajuBandingId" name="ajuBandingId">
                <textarea id="keterangan" name="keterangan" placeholder="Keterangan" class="w-full p-2 border border-gray-300 rounded-md mb-4" required></textarea>
                <input type="date" id="tanggal_pengajuan" name="tanggal_pengajuan" class="w-full p-2 border border-gray-300 rounded-md mb-4" required disabled>
                
                <select id="status" name="status" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
                    <option value="pending" class="bg-blue-500 text-white">Pending</option>
                    <option value="accepted" class="bg-green-500 text-white">Accepted</option>
                    <option value="rejected" class="bg-red-500 text-white">Rejected</option>
                </select>

                <button type="submit" id="saveAjuBandingBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mr-2">Simpan</button>
                <button type="button" id="closeModalBtn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">Tutup</button>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        loadAjuBanding();

            function loadAjuBanding() {
                $.ajax({
                    url: 'get_aju_banding.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#ajuBandingTable tbody').empty();
                        data.forEach(function(aju) {
                            let statusClass = '';
                            if (aju.status === 'pending') statusClass = 'status-pending';
                            else if (aju.status === 'accepted') statusClass = 'status-accepted';
                            else if (aju.status === 'rejected') statusClass = 'status-rejected';

                            $('#ajuBandingTable tbody').append(`
                                <tr>
                                    <td class="px-6 py-4">${aju.id}</td>
                                    <td class="px-6 py-4">${aju.id_pelanggaran}</td>
                                    <td class="px-6 py-4">${aju.keterangan}</td>
                                    <td class="px-6 py-4">${aju.tanggal_pengajuan}</td>
                                    <td class="px-6 py-4"><span class="status-box ${statusClass}">${aju.status}</span></td>
                                    <td class="px-6 py-4">
                                        <button class="editBtn bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200" data-id="${aju.id}">Edit</button>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                });
            }

            $(document).on('click', '.editBtn', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: 'get_aju_banding.php',
                    method: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(aju) {
                        // Mengisi modal dengan data yang benar
                        $('#ajuBandingId').val(aju.id);
                        $('#keterangan').val(aju.keterangan);  // Mengisi kolom Keterangan
                        $('#tanggal_pengajuan').val(aju.tanggal_pengajuan); // Mengisi kolom Tanggal Pengajuan

                        // Set status dropdown
                        $('#status').val(aju.status);  // Mengatur pilihan status pada dropdown
                        $('#ajuBandingModal').show();
                    }
                });
            });

            // Submit form untuk update data
            $('#ajuBandingForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'update_aju_banding.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                        loadAjuBanding();
                        $('#ajuBandingModal').hide();
                    }
                });
            });

            // Menutup modal ketika tombol "Tutup" ditekan
            $('#closeModalBtn').click(function() {
                $('#ajuBandingModal').hide();
            });
        });
    </script>

</body>
</html>

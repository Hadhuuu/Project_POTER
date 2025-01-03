<?php
session_start();
include('../konek.php');

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
    <title>Data Jenis Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #pelanggaranTable {
            width: 100%;
            overflow: hidden;
        }
        #pelanggaranTable tbody {
            display: block;
            max-height: 700px; /* Batas tinggi untuk scroll */
            overflow-y: auto;
        }
        #pelanggaranTable thead, #pelanggaranTable tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
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
                    <li><a href="jenis_pelanggaran.php" class="flex items-center p-2 rounded-md bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-exclamation-triangle mr-3"></i> Jenis Pelanggaran</a></li>
                    <li><a href="jenis_sanksi.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-balance-scale mr-3"></i> Jenis Sanksi</a></li>
                    <li><a href="pelanggaran.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-file-alt mr-3"></i> Data Pelanggaran</a></li>
                    <li><a href="aju_banding.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-gavel mr-3"></i> Aju Banding</a></li>
                    <li><a href="logout.php" class="flex items-center p-2 rounded-md bg-red-500 hover:bg-red-600 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-sign-out-alt mr-3"></i> Logout</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Data Jenis Pelanggaran</h2>
            <button id="addPelanggaranBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 hover:scale-105 transition transform duration-300 mb-6">Tambah Jenis Pelanggaran</button>
            <table id="pelanggaranTable" class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Nama Pelanggaran</th>
                        <th class="px-6 py-3">Tingkatan</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data jenis pelanggaran akan dimuat di sini -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk Tambah/Edit Jenis Pelanggaran -->
    <div id="pelanggaranModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 mt-48 ml-auto mr-auto">
        <h2 id="modalTitle" class="text-xl font-semibold mb-4">Tambah Jenis Pelanggaran</h2>
        <form id="pelanggaranForm">
            <input type="hidden" id="pelanggaranId" name="pelanggaranId">
            
            <!-- Input Keterangan -->
            <input type="text" id="nama_pelanggaran" name="nama_pelanggaran" placeholder="Nama Pelanggaran" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
            
            <!-- Dropdown Tingkatan -->
            <select id="tingkatan" name="tingkatan" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
                <option value="I">Tingkatan I</option>
                <option value="II">Tingkatan II</option>
                <option value="III">Tingkatan III</option>
                <option value="IV">Tingkatan IV</option>
                <option value="V">Tingkatan V</option>
            </select>

            <button type="submit" id="savePelanggaranBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mr-2">Simpan</button>
            <button type="button" id="closeModalBtn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">Tutup</button>
        </form>
    </div>
</div>


    <script>
        $(document).ready(function() {
            loadPelanggaran();

            function loadPelanggaran() {
                $.ajax({
                    url: 'get_jenis_pelanggaran.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#pelanggaranTable tbody').empty();
                        data.forEach(function(pelanggaran) {
                            $('#pelanggaranTable tbody').append(`
                                <tr>
                                    <td class="px-6 py-3">${pelanggaran.id}</td>
                                    <td class="px-6 py-3">${pelanggaran.keterangan}</td>
                                    <td class="px-6 py-3">${pelanggaran.tingkatan}</td>
                                    <td class="px-6 py-3">
                                        <button class="editBtn bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600" data-id="${pelanggaran.id}">Edit</button>
                                        <button class="deleteBtn bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600" data-id="${pelanggaran.id}">Hapus</button>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                });
            }

            $('#addPelanggaranBtn').click(function() {
                $('#modalTitle').text('Tambah Jenis Pelanggaran');
                $('#pelanggaranForm')[0].reset();
                $('#pelanggaranId').val('');
                $('#pelanggaranModal').show();
            });

            $('#pelanggaranForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'save_jenis_pelanggaran.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        loadPelanggaran();
                        $('#pelanggaranModal').hide();
                    }
                });
            });

            $(document).on('click', '.editBtn', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: 'get_jenis_pelanggaran.php',
                    method: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(pelanggaran) {
                        $('#modalTitle').text('Edit Jenis Pelanggaran');
                        $('#pelanggaranId').val(pelanggaran.id);
                        $('#nama_pelanggaran').val(pelanggaran.keterangan);
                        $('#tingkatan').val(pelanggaran.tingkatan);  // Memilih tingkatan yang sesuai
                        $('#pelanggaranModal').show();
                    }
                });
            });

            $(document).on('click', '.deleteBtn', function() {
                const id = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus jenis pelanggaran ini?')) {
                    $.ajax({
                        url: 'delete_jenis_pelanggaran.php',
                        method: 'POST',
                        data: { id: id },
                        success: function(response) {
                            alert(response);
                            loadPelanggaran();
                        }
                    });
                }
            });

            $('#closeModalBtn').click(function() {
                $('#pelanggaranModal').hide();
            });
        });

    </script>
</body>
</html>

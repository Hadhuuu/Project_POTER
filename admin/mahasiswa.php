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
    <title>Mahasiswa - POTER</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Scroll Tabel */
        #mahasiswaTable {
            width: 100%;
            overflow: hidden;
        }
        #mahasiswaTable tbody {
            display: block;
            max-height: 700px; /* Batas tinggi untuk scroll */
            overflow-y: auto;
        }
        #mahasiswaTable thead, #mahasiswaTable tbody tr {
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
                    <li><a href="mahasiswa.php" class="flex items-center p-2 rounded-md bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-user-graduate mr-3"></i> Mahasiswa</a></li>
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
        <div class="flex-1 p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Data Mahasiswa</h2>
            <button id="addMahasiswaBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 hover:scale-105 transition transform duration-300 mb-6">Tambah Mahasiswa</button>
            <table id="mahasiswaTable" class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-6 py-3">NIM</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Tanggal Lahir</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Kelas</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data mahasiswa akan dimuat di sini -->
                </tbody>
            </table>
        </div>
    </div>

<!-- Modal untuk Tambah/Edit Mahasiswa -->
<div id="mahasiswaModal" class="fixed hidden bg-black bg-opacity-50 inset-0 flex justify-center items-start z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm lg:max-w-md w-full mt-48 ml-auto mr-auto"> <!-- Adjust ml-auto and mr-auto -->
        <h2 id="modalTitle" class="text-xl font-semibold mb-4 text-center">Tambah Mahasiswa</h2>
        <form id="mahasiswaForm">
            <input type="hidden" id="mahasiswaId" name="mahasiswaId">
            <input type="text" id="nim" name="nim" placeholder="NIM" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
            <input type="text" id="nama" name="nama" placeholder="Nama" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
            <input type="date" id="ttl" name="ttl" placeholder="Tanggal Lahir" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
            <input type="email" id="email" name="email" placeholder="Email" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
            <select id="id_kelas" name="id_kelas" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
                <option value="">Pilih Kelas</option>
                <?php
                $sql = "SELECT * FROM kelas";
                $stmt = sqlsrv_query($conn, $sql);
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<option value='{$row['id']}'>{$row['nama_kelas']}</option>";
                }
                ?>
            </select>
            <div class="flex justify-between">
                <button type="submit" id="saveMahasiswaBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Simpan</button>
                <button type="button" id="closeModalBtn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">Tutup</button>
            </div>
        </form>
    </div>
</div>


    <script>
        $(document).ready(function() {
            loadMahasiswa();

            // Fungsi untuk memuat data mahasiswa
            function loadMahasiswa() {
                $.ajax({
                    url: 'get_mahasiswa.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#mahasiswaTable tbody').empty();
                        data.forEach(function(mahasiswa) {
                            $('#mahasiswaTable tbody').append(`
                                <tr>
                                    <td class="px-6 py-3">${mahasiswa.nim}</td>
                                    <td class="px-6 py-3">${mahasiswa.nama}</td>
                                    <td class="px-6 py-3">${mahasiswa.ttl}</td>
                                    <td class="px-6 py-3">${mahasiswa.email}</td>
                                    <td class="px-6 py-3">${mahasiswa.nama_kelas}</td>
                                    <td class="px-6 py-3">
                                        <button class="editBtn bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600" data-id="${mahasiswa.id}">Edit</button>
                                        <button class="deleteBtn bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600" data-id="${mahasiswa.id}">Hapus</button>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                });
            }

            // Menangani klik tombol tambah mahasiswa
            $('#addMahasiswaBtn').click(function() {
                $('#modalTitle').text('Tambah Mahasiswa');
                $('#mahasiswaForm')[0].reset();
                $('#mahasiswaId').val('');
                $('#mahasiswaModal').show();
            });

            // Menangani klik tombol simpan mahasiswa
            $('#mahasiswaForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'save_mahasiswa.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        loadMahasiswa();
                        $('#mahasiswaModal').hide();
                    }
                });
            });

            // Menangani klik tombol edit
            $(document).on('click', '.editBtn', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: 'get_mahasiswa.php',
                    method: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(mahasiswa) {
                        $('#modalTitle').text('Edit Mahasiswa');
                        $('#mahasiswaId').val(mahasiswa.id);
                        $('#nim').val(mahasiswa.nim);
                        $('#nama').val(mahasiswa.nama);
                        $('#ttl').val(mahasiswa.ttl);
                        $('#email').val(mahasiswa.email);
                        $('#id_kelas').val(mahasiswa.id_kelas);
                        $('#mahasiswaModal').show();
                    }
                });
            });

            // Menangani klik tombol hapus
            $(document).on('click', '.deleteBtn', function() {
                const id = $(this).data('id');
                if (confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?')) {
                    $.ajax({
                        url: 'delete_mahasiswa.php',
                        method: 'POST',
                        data: { id: id },
                        success: function(response) {
                            alert(response);
                            loadMahasiswa();
                        }
                    });
                }
            });

            // Menangani klik tombol tutup modal
            $('#closeModalBtn').click(function() {
                $('#mahasiswaModal').hide();
            });
        });
    </script>
</body>
</html>

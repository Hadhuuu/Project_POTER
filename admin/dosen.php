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
    <title>Data Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #dosenTable {
            width: 100%;
            overflow: hidden;
        }
        #dosenTable tbody {
            display: block;
            max-height: 700px; /* Batas tinggi untuk scroll */
            overflow-y: auto;
        }
        #dosenTable thead, #dosenTable tbody tr {
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
                    <li><a href="dosen.php" class="flex items-center p-2 rounded-md bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-chalkboard-teacher mr-3"></i> Dosen</a></li>
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
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Data Dosen</h2>
            <button id="addDosenBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 hover:scale-105 transition transform duration-300 mb-6">Tambah Dosen</button>
            <table id="dosenTable" class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-6 py-3">NIDN</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data dosen akan dimuat di sini -->
                </tbody>
            </table>
            <div id="pagination" class="mt-6 flex items-center justify-between">
                <div>
                    <button id="prevBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" disabled>
                        Prev
                    </button>
                </div>
                <div class="text-center">
                    <span id="pageInfo" class="text-lg font-medium text-gray-700">Halaman 1 dari 1</span>
                </div>
                <div>
                    <button id="nextBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Next
                    </button>
                </div>
            </div>


        </div>
        

    </div>

    <!-- Modal untuk Tambah/Edit Dosen -->
    <div id="dosenModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 mt-48 ml-auto mr-auto">
            <h2 id="modalTitle" class="text-xl font-semibold mb-4">Tambah Dosen</h2>
            <form id="dosenForm">
                <input type="hidden" id="dosenId" name="dosenId">
                <input type="text" id="nidn" name="nidn" placeholder="NIDN" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
                <input type="text" id="nama" name="nama" placeholder="Nama" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
                <input type="email" id="email" name="email" placeholder="Email" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
                <button type="submit" id="saveDosenBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mr-2">Simpan</button>
                <button type="button" id="closeModalBtn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">Tutup</button>
            </form>
        </div>
    </div>


    <script>
        $(document).ready(function() {
    let currentPage = 1; // Halaman saat ini

    // Fungsi untuk memuat data dosen
    function loadDosen(page = 1) {
        $.ajax({
            url: 'get_dosen.php',
            method: 'GET',
            data: { page: page },
            dataType: 'json',
            success: function(response) {
                $('#dosenTable tbody').empty();
                response.data.forEach(function(dosen) {
                    $('#dosenTable tbody').append(`
                        <tr>
                            <td class="px-6 py-3">${dosen.nidn}</td>
                            <td class="px-6 py-3">${dosen.nama}</td>
                            <td class="px-6 py-3">${dosen.email}</td>
                            <td class="px-6 py-3">
                                <button class="editBtn bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600" data-id="${dosen.id}">Edit</button>
                                <button class="deleteBtn bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600" data-id="${dosen.id}">Hapus</button>
                            </td>
                        </tr>
                    `);
                });

                // Menampilkan informasi halaman
                const totalPages = response.totalPages;
                $('#pageInfo').text(`Halaman ${response.currentPage} dari ${totalPages}`);

                // Menonaktifkan tombol Next/Prev jika sudah mencapai batas
                if (currentPage <= 1) {
                    $('#prevBtn').attr('disabled', true);
                } else {
                    $('#prevBtn').attr('disabled', false);
                }

                if (currentPage >= totalPages) {
                    $('#nextBtn').attr('disabled', true);
                } else {
                    $('#nextBtn').attr('disabled', false);
                }
            }
        });
    }

    // Memuat data dosen pada halaman pertama
    loadDosen(currentPage);

    // Tombol Next untuk halaman berikutnya
    $('#nextBtn').click(function() {
        currentPage++;
        loadDosen(currentPage);
    });

    // Tombol Prev untuk halaman sebelumnya
    $('#prevBtn').click(function() {
        currentPage--;
        loadDosen(currentPage);
    });

    // Fungsi untuk menambahkan dosen baru
    $('#addDosenBtn').click(function() {
        $('#modalTitle').text('Tambah Dosen');
        $('#dosenForm')[0].reset();
        $('#dosenId').val('');
        $('#dosenModal').show();
    });

    // Form submit untuk menyimpan data dosen
    $('#dosenForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'save_dosen.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                alert(response);
                loadDosen(currentPage); // Memuat ulang data dosen setelah disimpan
                $('#dosenModal').hide();
            }
        });
    });

    // Edit dan delete dosen
    $(document).on('click', '.editBtn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'get_dosen.php',
            method: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(dosen) {
                $('#modalTitle').text('Edit Dosen');
                $('#dosenId').val(dosen.id);
                $('#nidn').val(dosen.nidn);
                $('#nama').val(dosen.nama);
                $('#email').val(dosen.email);
                $('#dosenModal').show();
            }
        });
    });

    $(document).on('click', '.deleteBtn', function() {
        const id = $(this).data('id');
        if (confirm('Apakah Anda yakin ingin menghapus dosen ini?')) {
            $.ajax({
                url: 'delete_dosen.php',
                method: 'POST',
                data: { id: id },
                success: function(response) {
                    alert(response);
                    loadDosen(currentPage); // Memuat ulang data dosen setelah dihapus
                }
            });
        }
    });

    $('#closeModalBtn').click(function() {
        $('#dosenModal').hide();
    });
});

    </script>
</body>
</html>

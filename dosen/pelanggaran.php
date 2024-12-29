<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Tabel dengan scroll dan efek hover */
        #pelanggaranTable {
            max-height: 650px; /* Batas tinggi tabel */
            overflow-y: auto; /* Scroll vertikal */
            display: block;
        }

        #pelanggaranTable tbody tr:hover {
            background-color: #f3f4f6;
        }

        /* Status kotak */
        .status-box {
            width: 100px;
            padding: 5px;
            text-align: center;
            color: white;
            border-radius: 4px;
        }
        .resolved {
            background-color: #4CAF50; /* Green */
        }
        .unresolved {
            background-color: #FF6347; /* Red */
        }
        .innocent {
            background-color: #A9A9A9; /* Grey */
        }

        /* Tombol pagination */
        .btn-blue {
            background-color: #007BFF;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-blue:hover {
            background-color: #0056b3;
        }

        /* Letakkan tombol di bawah tabel */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
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
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Pelanggaran Mahasiswa</h2>

            <!-- Filter dan Tabel Pelanggaran -->
            <div class="bg-white p-8 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-4">Data Pelanggaran</h3>

                <!-- Filter Status Pelanggaran -->
                <div class="mb-4">
                    <label for="statusPelanggaranFilter" class="block text-sm font-semibold text-gray-700">Filter Berdasarkan Status:</label>
                    <select id="statusPelanggaranFilter" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="">Semua Status</option>
                        <option value="resolved">Resolved</option>
                        <option value="unresolved">Unresolved</option>
                        <option value="innocent">Innocent</option>
                    </select>
                </div>

                <!-- Tabel Pelanggaran -->
                <div id="pelanggaranTable">
                    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                        <thead>
                            <tr>
                                <th class="px-6 py-3">Keterangan</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Nama Mahasiswa</th>
                                <th class="px-6 py-3">NIM Mahasiswa</th>
                                <th class="px-6 py-3">Nama Dosen Pelapor</th>
                                <th class="px-6 py-3">Tingkatan Pelanggaran</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Foto Bukti Pelanggaran</th>
                                <th class="px-6 py-3">Foto Bukti Sanksi</th>
                                <th class="px-6 py-3">Dokumen SP</th>
                            </tr>
                        </thead>
                        <tbody id="pelanggaranTbody">
                            <!-- Data akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>
                <!-- Paginasi -->
                <div class="pagination-container">
                    <button id="prevPage" class="btn-blue disabled" disabled>Previous</button>
                    <span id="pageInfo" class="text-gray-600">Page 1 of 1</span>
                    <button id="nextPage" class="btn-blue">Next</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentPage = 1;

            function loadPelanggaran(status = '', page = 1) {
                $.ajax({
                    url: 'get_pelanggaran.php',
                    method: 'GET',
                    dataType: 'json',
                    data: { status: status, page: page },
                    success: function(response) {
                        $('#pelanggaranTbody').empty();
                        response.data.forEach(function(pelanggaran) {
                            var statusClass = '';
                            if (pelanggaran.status == 'Resolved' || pelanggaran.status == 'resolved') {
                                statusClass = 'resolved';
                            } else if (pelanggaran.status == 'Unresolved' || pelanggaran.status == 'unresolved') {
                                statusClass = 'unresolved';
                            } else {
                                statusClass = 'innocent';
                            }

                            $('#pelanggaranTbody').append(`
                                <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-2">${pelanggaran.keterangan}</td>
                                    <td class="px-4 py-2">${pelanggaran.tanggal}</td>
                                    <td class="px-4 py-2">${pelanggaran.nama_mahasiswa}</td>
                                    <td class="px-4 py-2">${pelanggaran.nim}</td>
                                    <td class="px-4 py-2">${pelanggaran.nama_dosen}</td>
                                    <td class="px-4 py-2">${pelanggaran.tingkatan_pelanggaran}</td>
                                    <td class="px-4 py-2">
                                        <div class="status-box ${statusClass}">
                                            ${pelanggaran.status}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="${pelanggaran.foto_bukti_pelanggaran}" target="_blank">
                                            <img src="${pelanggaran.foto_bukti_pelanggaran}" alt="Bukti Pelanggaran" width="50" class="cursor-pointer">
                                        </a>
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="${pelanggaran.foto_bukti_sanksi}" target="_blank">
                                            <img src="${pelanggaran.foto_bukti_sanksi}" alt="Bukti Sanksi" width="50" class="cursor-pointer">
                                        </a>
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="${pelanggaran.document_sp}" target="_blank" class="btn-blue">Lihat SP</a>
                                    </td>
                                </tr>
                            `);
                        });

                        $('#pageInfo').text(`Page ${response.currentPage} of ${response.totalPages}`);
                        currentPage = response.currentPage;

                        // Update tombol Previous dan Next
                        if (currentPage === 1) {
                            $('#prevPage').prop('disabled', true);
                        } else {
                            $('#prevPage').prop('disabled', false);
                        }
                        if (currentPage === response.totalPages) {
                            $('#nextPage').prop('disabled', true);
                        } else {
                            $('#nextPage').prop('disabled', false);
                        }
                    }
                });
            }

            // Memuat data pelanggaran saat halaman pertama kali dibuka
            loadPelanggaran();

            // Event listener untuk filter status
            $('#statusPelanggaranFilter').change(function() {
                var selectedStatus = $(this).val();
                loadPelanggaran(selectedStatus, 1);  // Muat halaman pertama dengan filter
            });

            // Event listener untuk tombol Previous
            $('#prevPage').click(function() {
                if (currentPage > 1) {
                    loadPelanggaran($('#statusPelanggaranFilter').val(), currentPage - 1);
                }
            });

            // Event listener untuk tombol Next
            $('#nextPage').click(function() {
                loadPelanggaran($('#statusPelanggaranFilter').val(), currentPage + 1);
            });
        });
    </script>
</body>
</html>

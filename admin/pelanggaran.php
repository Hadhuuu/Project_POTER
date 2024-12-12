<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Animasi Hover */
        tr:hover {
            background-color: #f0f0f0;
            transition: background-color 0.3s ease;
        }
        .editBtn:hover {
            background-color: #e5b70d;
            transition: background-color 0.3s ease;
        }
        .keterangan {
            cursor: pointer;
            color: #1D4ED8;
            text-decoration: underline;
        }
        .modal-content {
            max-width: 80%;
            max-height: 80vh;
            overflow-y: auto;
        }

        /* Scroll Tabel */
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
                    <li><a href="mahasiswa.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-user-graduate mr-3"></i> Mahasiswa</a></li>
                    <li><a href="dosen.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-chalkboard-teacher mr-3"></i> Dosen</a></li>
                    <li><a href="kelas.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-school mr-3"></i> Kelas</a></li>
                    <li><a href="jenis_pelanggaran.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-exclamation-triangle mr-3"></i> Jenis Pelanggaran</a></li>
                    <li><a href="jenis_sanksi.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-balance-scale mr-3"></i> Jenis Sanksi</a></li>
                    <li><a href="pelanggaran.php" class="flex items-center p-2 rounded-md bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-file-alt mr-3"></i> Data Pelanggaran</a></li>
                    <li><a href="aju_banding.php" class="flex items-center p-2 rounded-md hover:bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-gavel mr-3"></i> Aju Banding</a></li>
                    <li><a href="logout.php" class="flex items-center p-2 rounded-md bg-red-500 hover:bg-red-600 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-sign-out-alt mr-3"></i> Logout</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Data Pelanggaran</h2>

            <!-- Filter Search Box -->
            <div class="mb-4">
                <input type="text" id="searchInput" class="p-2 border border-gray-300 rounded-md" placeholder="Cari Nama Mahasiswa...">
            </div>

            <table id="pelanggaranTable" class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Nama Mahasiswa</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Nama Dosen Pelapor</th>
                        <th class="px-6 py-3">Tingkatan</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Foto Bukti Pelanggaran</th>
                        <th class="px-6 py-3">Foto Bukti Sanksi</th>
                        <th class="px-6 py-3">Dokumen SP</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data pelanggaran akan dimuat di sini -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk Lihat Keterangan -->
    <div id="keteranganModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 modal-content">
            <h2 class="text-xl font-semibold mb-4">Keterangan Pelanggaran</h2>
            <p id="keteranganText" class="text-gray-700"></p>
            <button type="button" id="closeModalBtn" class="bg-gray-600 text-white px-4 py-2 rounded-md">Tutup</button>
        </div>
    </div>

    <!-- Modal untuk Edit Status -->
    <div id="pelanggaranModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Edit Status Pelanggaran</h2>
            <form id="pelanggaranForm">
                <input type="hidden" id="pelanggaranId" name="pelanggaranId">
                <label for="status" class="block text-gray-700">Status</label>
                <select id="status" name="status" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
                    <option value="Resolved">Resolved</option>
                    <option value="Unresolved">Unresolved</option>
                    <option value="Innocent">Innocent</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Simpan</button>
                <button type="button" id="closeModalBtnStatus" class="bg-gray-600 text-white px-4 py-2 rounded-md">Tutup</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            loadPelanggaran();

            // Menampilkan data pelanggaran
            function loadPelanggaran() {
                $.ajax({
                    url: 'get_pelanggaran.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        const tableBody = $('#pelanggaranTable tbody');
                        tableBody.empty();
                        data.forEach(function(pelanggaran) {
                            const row = `
                                <tr>
                                    <td class="px-6 py-3">${pelanggaran.id}</td>
                                    <td class="px-6 py-3">${pelanggaran.nama_mahasiswa}</td>
                                    <td class="px-6 py-3"><span class="keterangan" onclick="showKeterangan('${pelanggaran.keterangan}')">${pelanggaran.keterangan.slice(0, 50)}...</span></td>
                                    <td class="px-6 py-3">${pelanggaran.tanggal}</td>
                                    <td class="px-6 py-3">${pelanggaran.nama_dosen}</td>
                                    <td class="px-6 py-3">${pelanggaran.tingkatan}</td>
                                    <td class="px-6 py-3">
                                        <span class="px-4 py-2 rounded-full ${
                                            pelanggaran.status === 'Resolved' ? 'bg-green-500' :
                                            pelanggaran.status === 'Unresolved' ? 'bg-red-500' :
                                            pelanggaran.status === 'Innocent' ? 'bg-gray-500' : ''}">
                                            ${pelanggaran.status}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <img src="${pelanggaran.foto_bukti_pelanggaran}" alt="Foto Bukti Pelanggaran" class="w-12 h-12 object-cover rounded border cursor-pointer" onclick="window.open('${pelanggaran.foto_bukti_pelanggaran}', '_blank')">
                                    </td>
                                    <td class="px-6 py-3">
                                        <img src="../uploads/${pelanggaran.foto_bukti_sanksi}" alt="Foto Bukti Sanksi" class="w-12 h-12 object-cover rounded border cursor-pointer" onclick="window.open('../uploads/${pelanggaran.foto_bukti_sanksi}', '_blank')">
                                    </td>
                                    <td class="px-6 py-3">
                                        <a href="../uploads/${pelanggaran.document_sp}" class="text-blue-600 underline" target="_blank">Lihat Surat Pernyataan</a>
                                    </td>
                                    <td class="px-6 py-3">
                                        <button class="editBtn bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600" data-id="${pelanggaran.id}">Edit</button>
                                    </td>
                                </tr>
                            `;
                            tableBody.append(row);
                        });
                    }
                });
            }

            // Filter berdasarkan nama mahasiswa
            $('#searchInput').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('#pelanggaranTable tbody tr').each(function() {
                    const studentName = $(this).find('td').eq(1).text().toLowerCase();
                    if (studentName.indexOf(searchTerm) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Tampilkan modal untuk keterangan
            window.showKeterangan = function(keterangan) {
                $('#keteranganText').text(keterangan);
                $('#keteranganModal').removeClass('hidden');
            };

            // Tutup modal keterangan
            $('#closeModalBtn').click(function() {
                $('#keteranganModal').addClass('hidden');
            });

            // Edit status pelanggaran
            $(document).on('click', '.editBtn', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: 'get_pelanggaran.php',
                    method: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(pelanggaran) {
                        $('#pelanggaranId').val(pelanggaran.id);
                        $('#status').val(pelanggaran.status);
                        $('#pelanggaranModal').removeClass('hidden');
                    }
                });
            });

            // Submit form untuk update status
            $('#pelanggaranForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'update_status_pelanggaran.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        loadPelanggaran();
                        $('#pelanggaranModal').addClass('hidden');
                    }
                });
            });

            // Tutup modal edit status
            $('#closeModalBtnStatus').click(function() {
                $('#pelanggaranModal').addClass('hidden');
            });
        });
    </script>
</body>
</html>

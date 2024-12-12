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
    <title>Pelanggaran Saya - POTER</title>
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
                    <li><a href="pelanggaran_saya.php" class="flex items-center p-2 rounded-md bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-exclamation-triangle mr-3"></i> Pelanggaran Saya</a></li>
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
                    <h2 class="ml-4 text-2xl font-semibold">Pelanggaran Saya</h2>
                </div>
            </div>
            <!-- Data Pelanggaran -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Daftar Pelanggaran</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600">
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Keterangan</th>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Dosen Pelapor</th>
                            <th class="px-4 py-2 text-left">Tingkatan Pelanggaran</th>
                            <th class="px-4 py-2 text-left">Bukti Pelanggaran</th>
                            <th class="px-4 py-2 text-left">Document SP</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pelanggaranList">
                        <!-- List of violations will be loaded here via AJAX -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal for Upload Foto Sanksi & Document SP -->
    <div id="uploadModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-xl font-semibold mb-4" id="modalTitle">Upload Bukti Sanksi</h3>
            <form id="uploadForm" method="POST" enctype="multipart/form-data">
                <input type="file" name="file" id="fileInput" class="mb-4 p-2 border border-gray-300 rounded-md w-full" accept="image/*,application/pdf" required>
                <div id="filePreview" class="mb-4"></div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Upload</button>
                <button type="button" id="closeModal" class="ml-2 bg-red-600 text-white px-4 py-2 rounded-md">Close</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Fetch violation data
            // Fetch violation data
$.ajax({
    url: 'get_pelanggaran_data.php',
    method: 'GET',
    data: { id_mahasiswa: <?php echo $id_mahasiswa; ?> },
    dataType: 'json',
    success: function(data) {
        let pelanggaranHtml = '';
        data.forEach(function(pelanggaran) {
            let statusClass = pelanggaran.status === 'Resolved' ? 'bg-green-500' : (pelanggaran.status === 'Unresolved' ? 'bg-red-500' : 'bg-gray-500');
            
            // HTML for each violation
            pelanggaranHtml += `
                <tr class="border-b">
                    <!-- Status -->
                    <td class="px-4 py-2">
                        <span class="px-5 py-5 text-white rounded ${statusClass} text-sm font-bold">${pelanggaran.status}</span>
                    </td>

                    <!-- Keterangan -->
                    <td class="px-4 py-2">${pelanggaran.keterangan}</td>

                    <!-- Tanggal -->
                    <td class="px-4 py-2">${pelanggaran.tanggal}</td>

                    <!-- Dosen Pelapor -->
                    <td class="px-4 py-2">${pelanggaran.dosen_pelapor}</td>

                    <!-- Tingkatan Pelanggaran -->
                    <td class="px-4 py-2">${pelanggaran.tingkatan}</td>

                    <!-- Foto Bukti Pelanggaran -->
                    <td class="px-4 py-2">
                        ${pelanggaran.foto_bukti_pelanggaran ? `<a href="${pelanggaran.foto_bukti_pelanggaran}" target="_blank">
                            <img src="${pelanggaran.foto_bukti_pelanggaran}" alt="Bukti Pelanggaran" class="w-20 h-20 object-cover rounded-md cursor-pointer">
                        </a>` : 'Belum di-upload'}
                    </td>

                    <!-- Document SP -->
                    <td class="px-4 py-2">
                        ${pelanggaran.document_sp ? `<a href="${pelanggaran.document_sp}" target="_blank" class="text-blue-600 underline">Lihat Document SP</a>` : 'Belum di-upload'}
                    </td>

                    <!-- Action Buttons -->
                    <td class="px-2 py-2">
                        <div class="flex space-x-2">
                            <button class="text-white bg-blue-600 p-2 rounded-md open-modal" data-upload-type="foto_bukti_sanksi" data-pelanggaran-id="${pelanggaran.id}">Upload Sanksi</button>
                            <button class="text-white bg-blue-600 p-2 rounded-md open-modal" data-upload-type="document_sp" data-pelanggaran-id="${pelanggaran.id}">Upload Document SP</button>
                        </div>
                    </td>
                </tr>
            `;
        });
        $('#pelanggaranList').html(pelanggaranHtml);
    }
});


            // Open modal when upload button is clicked
            $(document).on('click', '.open-modal', function() {
                let pelanggaranId = $(this).data('pelanggaran-id');
                let uploadType = $(this).data('upload-type');

                $('#uploadModal').removeClass('hidden');
                $('#uploadForm').data('pelanggaran-id', pelanggaranId);
                $('#uploadForm').data('upload-type', uploadType);

                // Change modal title based on upload type
                if (uploadType === 'foto_bukti_sanksi') {
                    $('#modalTitle').text('Upload Foto Bukti Sanksi');
                } else {
                    $('#modalTitle').text('Upload Document SP');
                }
            });

            // Close modal when close button is clicked
            $('#closeModal').on('click', function() {
                $('#uploadModal').addClass('hidden');
            });

            // Preview file before uploading
            $('#fileInput').on('change', function() {
                let file = this.files[0];
                let reader = new FileReader();

                if (file) {
                    if (file.type.startsWith('image/')) {
                        // Image preview
                        reader.onload = function(e) {
                            let preview = `<img src="${e.target.result}" class="w-40 h-40 object-cover rounded-md" />`;
                            $('#filePreview').html(preview);
                        };
                    } else if (file.type === 'application/pdf') {
                        // PDF preview
                        reader.onload = function(e) {
                            let preview = `<embed src="${e.target.result}" class="w-full h-40" />`;
                            $('#filePreview').html(preview);
                        };
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Handle form submission for file upload
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let pelanggaranId = $(this).data('pelanggaran-id');
                let uploadType = $(this).data('upload-type');
                formData.append('id_pelanggaran', pelanggaranId);
                formData.append('upload_type', uploadType);

                $.ajax({
                    url: 'upload_file.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response);
                        $('#uploadModal').addClass('hidden');
                    }
                });
            });
        });
    </script>
</body>
</html>

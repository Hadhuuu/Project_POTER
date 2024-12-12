<?php
session_start();
include('../konek.php');
require_once 'dompdf/autoload.inc.php'; // Assuming Dompdf is installed via Composer
use Dompdf\Dompdf;

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: index.html");
    exit();
}

$id_mahasiswa = $_SESSION['user_id'];

// Fetch data for dropdown
$pelanggaranQuery = "SELECT id, keterangan FROM pelanggaran WHERE id_mahasiswa = ?";
$params = array($id_mahasiswa);
$stmt = sqlsrv_query($conn, $pelanggaranQuery, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$pelanggaranList = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $pelanggaranList[] = $row;
}


$days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

$currentDay = $days[date('w')];  
$currentMonth = $months[date('n') - 1];  

define("DOMPDF_ENABLE_REMOTE", true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pelanggaran = $_POST['id_pelanggaran'];

    // Fetch necessary data
    $pelanggaranDataQuery = "
        SELECT p.keterangan, p.tanggal, m.nama AS mahasiswa_nama, m.nim, k.nama_kelas, d.nama AS dpa_nama
        FROM pelanggaran p
        JOIN mahasiswa m ON p.id_mahasiswa = m.id
        JOIN kelas k ON m.id_kelas = k.id
        JOIN dosen d ON k.id_dpa = d.id
        WHERE p.id = ?";
    $params = array($id_pelanggaran);
    $stmt = sqlsrv_query($conn, $pelanggaranDataQuery, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    // Generate the PDF
    $dompdf = new Dompdf();

    $currentDate = date('d');
    
    $currentYear = date('Y');
    
    $imagePath = '../assets/logo.png';
    $imageData = base64_encode(file_get_contents($imagePath));
    $imageSrc = 'data:image/png;base64,' . $imageData;

    $html = "<html lang='id'><body>
        <div style='font-family: Arial, sans-serif; font-size: 14px;'>
            <div style='text-align: center; position: relative;'>
                <div style='position: absolute; top: 0; left: 0;'>
                    <img src='$imageSrc' alt='Logo' style='width: 110px; height: auto;'>
                </div>
                <div style='margin-left: 100px;'>
                    <h3 style='margin: 0; font-size: 18px;'>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h3>
                    <h3 style='margin: 0; font-size: 18px;'>JURUSAN TEKNOLOGI INFORMASI</h3>
                    <h3 style='margin: 0; font-size: 18px;'>POLITEKNIK NEGERI MALANG</h3>
                    <p style='margin: 0; font-size: 12px;'>Jl. Soekarno Hatta No.9 Jatimulyo, Lowokwaru, Malang, 65141</p>
                    <p style='margin: 0; font-size: 12px;'>Telp. (0341) 404424 - 404425, Fax (0341) 404420</p>
                    <p style='margin: 0; font-size: 12px;'>http://www.polinema.ac.id</p>
                </div>
                <hr style='margin-top: 10px; height: 2px; background-color: black;'>
            </div>

            <h2 style='text-align: center; margin-top: 20px; font-size: 18px;'>BERITA ACARA</h2>
            <h3 style='text-align: center; font-size: 16px;'>PERTEMUAN DPA DENGAN WALI MAHASISWA</h3>

            <p>Pada hari ini, {$currentDay}, Tanggal {$currentDate}, Bulan {$currentMonth}, Tahun {$currentYear}, telah bertemu Dosen Pembina Akademik (DPA):</p>
            <p>Nama: {$data['dpa_nama']}</p>
            <p>DPA Kelas: {$data['nama_kelas']}</p>

            <p>Melakukan pertemuan dengan orang tua/wali mahasiswa:</p>
            <p>Nama: {$data['mahasiswa_nama']}</p>
            <p>NIM/Kelas: {$data['nim']} / {$data['nama_kelas']}</p>

            <p>Pertemuan dilakukan karena mahasiswa yang bersangkutan:</p>
            <ol>
                <li>Menerima status SP1 / SP2 / SP3 / SPK / PS</li>
                <li>Melanggar Tata Tertib: {$data['keterangan']}</li>
                <li>Mendapat nilai tengah semester/akhir semester ...............</li>
            </ol>

            <p>Hasil pertemuan memberikan rekomendasi sebagai berikut:</p>
            <p>.....................................................................................................................................................</p>
            <p>.....................................................................................................................................................</p>

            
            <table style='width: 100%; margin-top: 40px; font-size: 14px;'>
                <tr>
                    <td style='text-align: left; width: 33%;'>
                        <p>DPA</p>
                        <p style='margin-top: 100px;'>.........................................</p>
                        <p style='margin-bottom: 155px'>NIP.</p>
                    </td>
                    <td style='text-align: center; width: 33%; padding-top: 100px;'>
                        <p>Mengetahui</p>
                        <p>Kaprodi D4-SIB</p>
                        <p style='margin-top: 90px;'>Hendra Pradibta, S.E., M.Sc.</p>
                        <p>NIP. 198305212006041003</p>
                    </td>

                    <td style='text-align: right; width: 33%;'>
                        <p>Malang, ..........</p>
                        <p>Mahasiswa</p>
                        <p style='margin-top: 80px; margin-bottom: 200px'>.........................................</p>
                    </td>
                </tr>
            </table>
        </div>
    </body></html>";

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('Berita_Acara.pdf', ["Attachment" => 0]);
    exit();
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Surat SP</title>
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
                    <li><a href="download_suratSP.php" class="flex items-center p-2 rounded-md bg-blue-700 transition transform duration-300 ease-in-out hover:scale-105"><i class="fas fa-file-download mr-3"></i> Download Surat SP</a></li>
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
                    <h2 class="ml-4 text-2xl font-semibold">Download dan Cetak Surat Pernyataan</h2>
                </div>
            </div>

            <!-- Formulir Cetak Surat SP -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Cetak Surat SP</h3>
                <form method="POST">
                    <label for="id_pelanggaran" class="block mb-2 text-gray-700">Pilih Pelanggaran:</label>
                    <select name="id_pelanggaran" id="id_pelanggaran" class="w-full p-2 border border-gray-300 rounded-md mb-4" required>
                        <option value="">-- Pilih Pelanggaran --</option>
                        <?php foreach ($pelanggaranList as $pelanggaran): ?>
                            <option value="<?php echo $pelanggaran['id']; ?>"><?php echo $pelanggaran['keterangan']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Cetak Surat SP</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
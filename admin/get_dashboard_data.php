<?php
session_start();
include('../konek.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

$data = [];

// Menghitung jumlah dosen
$sql = "SELECT COUNT(*) AS jumlahDosen FROM dosen";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$data['jumlahDosen'] = $row['jumlahDosen'];

// Menghitung jumlah mahasiswa
$sql = "SELECT COUNT(*) AS jumlahMahasiswa FROM mahasiswa";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$data['jumlahMahasiswa'] = $row['jumlahMahasiswa'];

// Menghitung jumlah kelas
$sql = "SELECT COUNT(*) AS jumlahKelas FROM kelas";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$data['jumlahKelas'] = $row['jumlahKelas'];

// Menghitung jumlah akun
$sql = "SELECT COUNT(*) AS jumlahAkun FROM akun";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$data['jumlahAkun'] = $row['jumlahAkun'];

// Menghitung jumlah aju banding
$sql = "SELECT COUNT(*) AS jumlahAjuBanding FROM ajubanding";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$data['jumlahAjuBanding'] = $row['jumlahAjuBanding'];

// Menghitung mahasiswa dengan pelanggaran
$sql = "SELECT COUNT(DISTINCT id_mahasiswa) AS mahasiswaDenganPelanggaran FROM pelanggaran";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$data['mahasiswaDenganPelanggaran'] = $row['mahasiswaDenganPelanggaran'];

// Menghitung mahasiswa tanpa pelanggaran
$data['mahasiswaTanpaPelanggaran'] = $data['jumlahMahasiswa'] - $data['mahasiswaDenganPelanggaran'];

// Mengambil pelanggaran per bulan
$sql = "
    SELECT 
        MONTH(tanggal) AS bulan,
        YEAR(tanggal) AS tahun,
        COUNT(*) AS jumlahPelanggaran
    FROM pelanggaran
    WHERE YEAR(tanggal) = YEAR(GETDATE())  -- Sesuaikan dengan tahun saat ini
    GROUP BY MONTH(tanggal), YEAR(tanggal)
    ORDER BY YEAR(tanggal), MONTH(tanggal)
";
$stmt = sqlsrv_query($conn, $sql);
$pelanggaranPerBulan = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $pelanggaranPerBulan[] = [
        'bulan' => $row['bulan'],
        'tahun' => $row['tahun'],
        'jumlahPelanggaran' => $row['jumlahPelanggaran']
    ];
}

// Menambahkan data pelanggaran per bulan ke dalam response
$data['pelanggaranPerBulan'] = $pelanggaranPerBulan;

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
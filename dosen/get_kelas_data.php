<?php
session_start();
include('../konek.php');

// Pastikan hanya dosen yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dosen') {
    header("Location: index.html");
    exit();
}

// Ambil ID Dosen dari sesi
$dosen_id = $_SESSION['user_id'];

// Query untuk mengambil kelas dan mahasiswa terkait dosen yang sedang login
$sql = "
    SELECT 
        k.id AS kelas_id, 
        k.nama_kelas, 
        d.nama AS dosen_pengampu,
        m.id AS mahasiswa_id, 
        m.nim, 
        m.nama AS mahasiswa_nama, 
        CONVERT(varchar, m.ttl, 23) AS ttl,  -- Format tanggal
        m.email
    FROM kelas k
    LEFT JOIN dosen d ON k.id_dpa = d.id
    LEFT JOIN mahasiswa m ON m.id_kelas = k.id
    WHERE k.id_dpa = ?
";
$params = array($dosen_id);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));  // Debugging jika query gagal
}

// Menyusun data untuk kelas dan mahasiswa
$kelas_data = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Menyusun data kelas
    $kelas_data['nama_kelas'] = $row['nama_kelas'];
    $kelas_data['dosen_pengampu'] = $row['dosen_pengampu'];

    // Menyusun data mahasiswa
    if (!isset($kelas_data['mahasiswa'])) {
        $kelas_data['mahasiswa'] = [];
    }

    // Tambahkan data mahasiswa ke dalam array
    $kelas_data['mahasiswa'][] = [
        'nim' => $row['nim'],
        'nama' => $row['mahasiswa_nama'],
        'ttl' => $row['ttl'],  // Pastikan formatnya sudah benar (YYYY-MM-DD)
        'email' => $row['email']
    ];
}

// Kirim data dalam format JSON
echo json_encode($kelas_data);
?>

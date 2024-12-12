<?php
$host = "HUDHA"; // nama server\nama_instance
$connInfo = array("Database" => "DB_POTER", "UID" => "", "PWD" => "");
$conn = sqlsrv_connect($host, $connInfo);

if (!$conn) {
    echo "Koneksi gagal!";
    die(print_r(sqlsrv_errors(), true));
}
?>

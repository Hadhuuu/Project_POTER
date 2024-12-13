<?php
class Database {
    private $host;
    private $dbName;
    private $username;
    private $password;
    private $conn;

    // Konstruktor untuk inisialisasi parameter koneksi
    public function __construct($host = "localhost", $dbName = "DB_POTER", $username = "", $password = "") {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->username = $username;
        $this->password = $password;
        $this->conn = null;
    }

    // Fungsi untuk melakukan koneksi ke database
    public function connect() {
        if ($this->conn === null) {
            $connInfo = array(
                "Database" => $this->dbName,
                "UID" => $this->username,
                "PWD" => $this->password
            );
            $this->conn = sqlsrv_connect($this->host, $connInfo);

            if (!$this->conn) {
                echo "Koneksi gagal!";
                die(print_r(sqlsrv_errors(), true));
            }
        }
        return $this->conn;
    }

    // Fungsi untuk menutup koneksi
    public function close() {
        if ($this->conn) {
            sqlsrv_close($this->conn);
        }
    }

    // Fungsi untuk menjalankan query yang mengembalikan hasil (misalnya SELECT)
    public function executeQuery($sql, $params = array()) {
        $conn = $this->connect();
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $results = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }

    // Fungsi untuk menjalankan query yang tidak mengembalikan hasil (misalnya INSERT, UPDATE, DELETE)
    public function execute($sql, $params = array()) {
        $conn = $this->connect();
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        return true; // Kembali true jika query berhasil dieksekusi
    }
}
?>

<?php
// Mulai sesi
session_start();

// Hapus semua data sesi
session_unset();
session_destroy();

// Redirect ke ../index.html
header("Location: ../index.html");
exit;
?>

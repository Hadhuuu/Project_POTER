$(document).ready(function () {
    $("#loginForm").submit(function (e) {
        e.preventDefault(); // Mencegah refresh halaman

        // Ambil data dari form
        const formData = $(this).serialize();

        // Kirim data ke `login.php` menggunakan AJAX
        $.ajax({
            type: "POST",
            url: "login.php", // Pastikan path benar
            data: formData,
            success: function (response) {
                if (response.includes("mahasiswa")) {
                    window.location.href = "mahasiswa/dashboard.php";
                } else if (response.includes("dosen")) {
                    window.location.href = "dosen/dashboard.php";
                } else if (response.includes("admin")) {
                    window.location.href = "admin/dashboard.php";
                } else {
                    alert("Username atau password salah!");
                }
            },
            error: function () {
                alert("Terjadi kesalahan pada server.");
            },
        });
    });
});

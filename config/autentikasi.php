<?php
session_start();
include('config.php');

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Cek apakah POST data tersedia
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Persiapkan query untuk memeriksa username
    if ($stmt = $koneksi->prepare("SELECT * FROM pengunjung WHERE username = ?")) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['pengunjung_id'] = $user['id']; // Menyimpan ID pengguna di session

                // Redirect berdasarkan role
                if ($user['role'] === 'admin') {
                    header('Location: ../app/');
                } else {
                    header('Location: ../app/');
                }
                exit();
            } else {
                // Password salah
                header('Location: ../index.php?error=1');
                exit();
            }
        } else {
            // Username tidak ditemukan
            header('Location: ../index.php?error=1');
            exit();
        }

        $stmt->close();
    } else {
        die("Query preparation failed: " . $koneksi->error);
    }
} else {
    // Jika POST data tidak tersedia, arahkan kembali ke halaman login
    header('Location: ../index.php?error=2');
    exit();
}
?>

<?php
session_start();
include('../config/config.php');

// Cek jika pengguna tidak memiliki akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: http://localhost/coba/app/");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $umur = (int)$_POST['umur'];
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    
    // Query untuk menambahkan data pengunjung
    $query = "INSERT INTO pengunjung (username, email, no_telepon, alamat, umur, role, tanggal_daftar) 
              VALUES ('$username', '$email', '$no_telepon', '$alamat', $umur, '$role', NOW())";
    
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'message' => 'Data pengunjung berhasil ditambahkan!'
        ];
        // Alihkan ke halaman dengan parameter status
        header("Location: http://localhost/coba/app/tambah_data_pengunjung.php?status=added");
        exit();
    } else {
        $_SESSION['flash_message'] = [
            'type' => 'danger',
            'message' => 'Gagal menambahkan data pengunjung: ' . mysqli_error($koneksi)
        ];
        header("Location: http://localhost/coba/app/tambah_data_pengunjung.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Pengunjung</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            // Menampilkan pesan konfirmasi jika ada
            var urlParams = new URLSearchParams(window.location.search);
            var status = urlParams.get('status');

            if (status === 'added') {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: 'Data berhasil ditambahkan!',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "http://localhost/coba/app/data_admin.php";
                });
            }
        });
    </script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Tambah Data Pengunjung</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="no_telepon">No Telepon:</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat:</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="umur">Umur:</label>
            <input type="number" class="form-control" id="umur" name="umur" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select class="form-control" id="role" name="role" required>
                <option value="pengunjung">Pengunjung</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="http://localhost/coba/app/data_admin.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>

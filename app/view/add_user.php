<?php
include '../../config/config.php';

// Variabel untuk pesan
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $umur = $_POST['umur'];
    $role = $_POST['role'];
    $tanggal_daftar = date('Y-m-d');

    $sql = "INSERT INTO pengunjung (username, email, password, no_telepon, alamat, umur, role, tanggal_daftar)
            VALUES ('$username', '$email', '$password', '$no_telepon', '$alamat', '$umur', '$role', '$tanggal_daftar')";

    if ($koneksi->query($sql) === TRUE) {
        // Set pesan sukses
        $successMessage = 'Data berhasil ditambahkan.';
        // Redirect dengan JavaScript setelah menyimpan pesan sukses
        echo "<script>
            window.onload = function() {
                var successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-custom';
                successAlert.role = 'alert';
                successAlert.innerText = '$successMessage';
                document.body.appendChild(successAlert);
                setTimeout(function() {
                    successAlert.style.opacity = '0';
                    setTimeout(function() {
                        window.location.href = 'http://localhost/coba/app/index.php';
                    }, 1000);
                }, 3000);
            };
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .alert-custom {
            position: fixed;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: 80%;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Tambah User</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="no_telepon">No Telepon:</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat:</label>
            <textarea class="form-control" id="alamat" name="alamat" required></textarea>
        </div>
        <div class="form-group">
            <label for="umur">Umur:</label>
            <input type="number" class="form-control" id="umur" name="umur" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select class="form-control" id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="pengunjung">Pengunjung</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="user.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
session_start();
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $umur = mysqli_real_escape_string($koneksi, $_POST['umur']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    $tanggal_daftar = mysqli_real_escape_string($koneksi, $_POST['tanggal_daftar']);

    $query = "INSERT INTO pengunjung (username, email, no_telepon, alamat, umur, role, tanggal_daftar) VALUES ('$username', '$email', '$no_telepon', '$alamat', '$umur', '$role', '$tanggal_daftar')";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'message' => 'Data berhasil ditambahkan!'
        ];
    } else {
        $_SESSION['flash_message'] = [
            'type' => 'danger',
            'message' => 'Gagal menambahkan data: ' . mysqli_error($koneksi)
        ];
    }

    header('Location: ../Data_admin.php');
    exit();
}
